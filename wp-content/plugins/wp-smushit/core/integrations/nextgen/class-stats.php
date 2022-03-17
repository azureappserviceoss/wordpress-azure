<?php
/**
 * Handles all the stats related functions
 *
 * @package Smush\Core\Integrations\NextGen
 * @version 1.0
 *
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2016, Incsub (http://incsub.com)
 */

namespace Smush\Core\Integrations\NextGen;

use C_Component_Registry;
use C_Gallery_Storage;
use C_NextGen_Serializable;
use Exception;
use Ngg_Serializable;
use Smush\App\Media_Library;
use Smush\Core\Integrations\NextGen;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Stats
 */
class Stats extends NextGen {

	/**
	 * Contains the total Stats, for displaying it on bulk page
	 *
	 * @var array
	 */
	public $stats = array();

	/**
	 * PRO user status.
	 *
	 * @var bool
	 */
	private $is_pro_user;

	/**
	 * Stats constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->is_pro_user = WP_Smush::is_pro();

		// Clear stats cache when an image is restored.
		add_action( 'wp_smush_image_nextgen_restored', array( $this, 'clear_cache' ) );

		// Add the resizing stats to Global stats.
		add_action( 'wp_smush_image_nextgen_resized', array( $this, 'update_stats' ), '', 2 );

		// Get the stats for single image, update the global stats.
		add_action( 'wp_smush_nextgen_image_stats', array( $this, 'update_stats' ), '', 2 );
	}

	/**
	 * Returns/Updates the number of images Super Smushed.
	 *
	 * @param array $attachments  Optional, By default Media attachments will be fetched.
	 *
	 * @return array|mixed
	 *
	 * @todo Refactor Method, Separate Media Library and Nextgen, moreover nextgen functionality is broken
	 */
	public function nextgen_super_smushed_count( $attachments = array() ) {
		$key = 'wp-smush-super_smushed_nextgen';

		// Clear up the stats, if there are no images.
		if ( method_exists( 'Smush\\Core\\Integrations\\NextGen\\Stats', 'total_count' ) && 0 === self::total_count() ) {
			delete_option( $key );
		}

		// Flag to check if we need to re-evaluate the count.
		$reevaluate = false;

		$super_smushed = get_option( $key, false );

		// Check if need to revalidate.
		if ( ! $super_smushed || empty( $super_smushed ) || empty( $super_smushed['ids'] ) ) {
			$super_smushed = array(
				'ids' => array(),
			);

			$reevaluate = true;
		} else {
			$last_checked = $super_smushed['timestamp'];

			$diff = $last_checked - current_time( 'timestamp' );

			// Difference in hour.
			$diff_h = $diff / 3600;

			// if last checked was more than 1 hours.
			if ( $diff_h > 1 ) {
				$reevaluate = true;
			}
		}

		// Do not reevaluate stats if nextgen attachments are not provided.
		if ( empty( $attachments ) && $reevaluate ) {
			$reevaluate = false;
		}

		// Need to scan all the image.
		if ( $reevaluate ) {
			// Get all the Smushed attachments ids
			// Note: Wrong Method called, it'll fetch media images and not NextGen images
			// Should be $attachments, in place of $super_smushed_images.
			$super_smushed_images = WP_Smush::get_instance()->core()->get_super_smushed_attachments();

			if ( ! empty( $super_smushed_images ) && is_array( $super_smushed_images ) ) {
				// Iterate over all the attachments to check if it's already there in list, else add it.
				foreach ( $super_smushed_images as $id ) {
					if ( ! in_array( $id, $super_smushed['ids'], true ) ) {
						$super_smushed['ids'][] = $id;
					}
				}
			}

			$super_smushed['timestamp'] = current_time( 'timestamp' );

			update_option( $key, $super_smushed, false );
		}

		return ! empty( $super_smushed['ids'] ) ? count( $super_smushed['ids'] ) : 0;
	}

	/**
	 * Get the images id for nextgen gallery
	 *
	 * @param bool $force_refresh Optional. Whether to force the cache to be refreshed.
	 * Default false.
	 *
	 * @param bool $return_ids Whether to return the ids array, set to false by default.
	 *
	 * @return int|mixed Returns the images ids or the count
	 */
	public static function total_count( $force_refresh = false, $return_ids = false ) {
		// Check for the  wp_smush_images in the 'nextgen' group.
		$attachment_ids = wp_cache_get( 'wp_smush_images', 'nextgen' );

		// If nothing is found, build the object.
		if ( true === $force_refresh || false === $attachment_ids ) {
			// Get the nextgen image IDs.
			$attachment_ids = self::get_nextgen_attachments();

			if ( ! is_wp_error( $attachment_ids ) ) {
				// In this case we don't need a timed cache expiration.
				wp_cache_set( 'wp_smush_images', $attachment_ids, 'nextgen' );
			}
		}

		return $return_ids ? $attachment_ids : count( $attachment_ids );
	}

	/**
	 * Returns the ngg images list(id and meta ) or count
	 *
	 * @param string     $type          Whether to return smushed images or unsmushed images.
	 * @param bool|false $count         Return count only.
	 * @param bool|false $force_update  True/false to update the cache or not.
	 *
	 * @return bool|mixed Returns assoc array of image ids and meta or Image count
	 *
	 * @throws Exception  Exception.
	 */
	public function get_ngg_images( $type = 'smushed', $count = false, $force_update = false ) {
		global $wpdb;

		$limit  = apply_filters( 'wp_smush_nextgen_query_limit', 1000 );
		$offset = 0;

		// Check type of images being queried.
		if ( ! in_array( $type, array( 'smushed', 'unsmushed' ), true ) ) {
			return false;
		}

		// Check for the  wp_smush_images_smushed in the 'nextgen' group.
		$images = wp_cache_get( 'wp_smush_images_' . $type, 'nextgen' );

		// If nothing is found, build the object.
		if ( ! $images || $force_update ) {
			// Query Attachments for meta key.
			while ( $attachments = $wpdb->get_results( "SELECT pid, meta_data FROM $wpdb->nggpictures LIMIT {$offset}, {$limit}" ) ) {
				foreach ( $attachments as $attachment ) {
					// Check if it has `wp_smush` key.
					if ( class_exists( 'Ngg_Serializable' ) ) {
						$meta = ( new Ngg_Serializable() )->unserialize( $attachment->meta_data );
					} elseif ( class_exists( 'C_NextGen_Serializable' ) && method_exists( 'C_NextGen_Serializable', 'unserialize' ) ) {
						$meta = C_NextGen_Serializable::unserialize( $attachment->meta_data );
					} else {
						// If you can't parse it without NextGen - don't parse at all.
						continue;
					}

					// Store pid in image meta.
					if ( is_array( $meta ) && empty( $meta['pid'] ) ) {
						$meta['pid'] = $attachment->pid;
					} elseif ( is_object( $meta ) && empty( $meta->pid ) ) {
						$meta->pid = $attachment->pid;
					}

					// Check meta for wp_smush.
					if ( ! is_array( $meta ) || empty( $meta['wp_smush'] ) ) {
						$unsmushed_images[ $attachment->pid ] = $meta;
						continue;
					}
					$smushed_images[ $attachment->pid ] = $meta;
				}
				// Set the offset.
				$offset += $limit;
			}
			if ( ! empty( $smushed_images ) ) {
				wp_cache_set( 'wp_smush_images_smushed', $smushed_images, 'nextgen', 300 );
			}
			if ( ! empty( $unsmushed_images ) ) {
				wp_cache_set( 'wp_smush_images_unsmushed', $unsmushed_images, 'nextgen', 300 );
			}
		}

		if ( 'smushed' === $type ) {
			$smushed_images = ! empty( $smushed_images ) ? $smushed_images : $images;
			if ( ! $smushed_images ) {
				return 0;
			}
			return $count ? count( $smushed_images ) : $smushed_images;
		}

		$unsmushed_images = ! empty( $unsmushed_images ) ? $unsmushed_images : $images;
		if ( ! $unsmushed_images ) {
			return 0;
		}
		return $count ? count( $unsmushed_images ) : $unsmushed_images;
	}

	/**
	 * Display the smush stats for the image
	 *
	 * @param int        $pid            Image Id stored in nextgen table.
	 * @param bool|array $wp_smush_data  Stats, stored after smushing the image.
	 * @param string     $image_type     Used for determining if not gif, to show the Super Smush button.
	 *
	 * @uses Admin::column_html(), WP_Smush::get_restore_link(), WP_Smush::get_resmush_link()
	 *
	 * @return bool|array|string
	 */
	public function show_stats( $pid, $wp_smush_data = false, $image_type = '' ) {
		if ( empty( $wp_smush_data ) ) {
			return false;
		}
		$button_txt   = '';
		$show_button  = false;
		$show_resmush = false;

		$bytes          = isset( $wp_smush_data['stats']['bytes'] ) ? $wp_smush_data['stats']['bytes'] : 0;
		$bytes_readable = ! empty( $bytes ) ? size_format( $bytes, 1 ) : '';
		$percent        = isset( $wp_smush_data['stats']['percent'] ) ? $wp_smush_data['stats']['percent'] : 0;
		$percent        = $percent < 0 ? 0 : $percent;

		$status_txt = '';
		if ( isset( $wp_smush_data['stats']['size_before'] ) && $wp_smush_data['stats']['size_before'] == 0 && ! empty( $wp_smush_data['sizes'] ) ) {
			$status_txt = __( 'Already Optimized', 'wp-smushit' );
		} else {
			if ( 0 === (int) $bytes || 0 === (int) $percent ) {
				$status_txt = __( 'Already Optimized', 'wp-smushit' );

				// Add resmush option if needed.
				$show_resmush = $this->show_resmush( $show_resmush, $wp_smush_data );
				if ( $show_resmush ) {
					$status_txt .= '<div class="sui-smush-media smush-status-links">';
					$status_txt .= Media_Library::get_resmsuh_link( $pid, 'nextgen' );
					$status_txt .= '</div>';
				}
			} elseif ( ! empty( $percent ) && ! empty( $bytes_readable ) ) {
				$status_txt  = sprintf( /* translators: %1$s: reduced by bytes, %2$s: size format */
					__( 'Reduced by %1$s (%2$01.1f%%)', 'wp-smushit' ),
					$bytes_readable,
					number_format_i18n( $percent, 2 )
				);
				$status_txt .= '<div class="sui-smush-media smush-status-links">';

				$show_resmush = $this->show_resmush( $show_resmush, $wp_smush_data );

				if ( $show_resmush ) {
					$status_txt .= Media_Library::get_resmsuh_link( $pid, 'nextgen' );
				}

				// Restore Image: Check if we need to show the restore image option.
				$show_restore = $this->show_restore_option( $pid, $wp_smush_data );

				if ( $show_restore ) {
					if ( $show_resmush ) {
						// Show Separator.
						$status_txt .= ' | ';
					}
					$status_txt .= Media_Library::get_restore_link( $pid, 'nextgen' );
				}
				// Show detailed stats if available.
				if ( ! empty( $wp_smush_data['sizes'] ) ) {
					if ( $show_resmush || $show_restore ) {
						// Show Separator.
						$status_txt .= ' | ';
					} else {
						// Show the link in next line.
						$status_txt .= '<br />';
					}
					// Detailed Stats Link.
					$status_txt .= '<a href="#" class="smush-stats-details">' . esc_html__( 'Smush stats', 'wp-smushit' ) . ' [<span class="stats-toggle">+</span>]</a>';

					// Get metadata For the image
					// Registry Object for NextGen Gallery.
					$registry = C_Component_Registry::get_instance();

					/**
					 * Gallery Storage Object.
					 *
					 * @var C_Gallery_Storage $storage
					 */
					$storage = $registry->get_utility( 'I_Gallery_Storage' );

					// get an array of sizes available for the $image.
					$sizes = $storage->get_image_sizes();

					$image = $storage->object->_image_mapper->find( $pid );

					$full_image = $storage->get_image_abspath( $image, 'full' );

					// Stats.
					$stats = $this->get_detailed_stats( $pid, $wp_smush_data, array( 'sizes' => $sizes ), $full_image );

					$status_txt .= $stats;
					$status_txt .= '</div>';
				}
			}
		}

		// IF current compression is lossy.
		if ( ! empty( $wp_smush_data ) && ! empty( $wp_smush_data['stats'] ) ) {
			$lossy    = ! empty( $wp_smush_data['stats']['lossy'] ) ? $wp_smush_data['stats']['lossy'] : '';
			$is_lossy = $lossy == 1 ? true : false;
		}

		// Check if Lossy enabled.
		$opt_lossy_val = $this->settings->get( 'lossy' );

		// Check if premium user, compression was lossless, and lossy compression is enabled.
		if ( ! $show_resmush && $this->is_pro_user && ! $is_lossy && $opt_lossy_val && ! empty( $image_type ) && 'image/gif' !== $image_type ) {
			// the button text.
			$button_txt  = __( 'Super-Smush', 'wp-smushit' );
			$show_button = true;
		}

		// If show button is true for some reason, column html can print out the button for us.
		return WP_Smush::get_instance()->core()->nextgen->ng_admin->column_html( $pid, $status_txt, $button_txt, $show_button, true );
	}

	/**
	 * Updated the global smush stats for NextGen gallery
	 *
	 * @param int   $image_id  Image ID.
	 * @param array $stats     Compression stats fo respective image.
	 */
	public function update_stats( $image_id, $stats ) {
		$stats = ! empty( $stats['stats'] ) ? $stats['stats'] : '';

		$smush_stats = get_option( 'wp_smush_stats_nextgen', array() );

		if ( ! empty( $stats ) ) {
			// Human Readable.
			$smush_stats['human'] = ! empty( $smush_stats['bytes'] ) ? size_format( $smush_stats['bytes'], 1 ) : '';

			// Size of images before the compression.
			$smush_stats['size_before'] = ! empty( $smush_stats['size_before'] ) ? ( $smush_stats['size_before'] + $stats['size_before'] ) : $stats['size_before'];

			// Size of image after compression.
			$smush_stats['size_after'] = ! empty( $smush_stats['size_after'] ) ? ( $smush_stats['size_after'] + $stats['size_after'] ) : $stats['size_after'];

			$smush_stats['bytes'] = ! empty( $smush_stats['size_before'] ) && ! empty( $smush_stats['size_after'] ) ? ( $smush_stats['size_before'] - $smush_stats['size_after'] ) : 0;

			// Compression Percentage.
			$smush_stats['percent'] = ! empty( $smush_stats['size_before'] ) && ! empty( $smush_stats['size_after'] ) && $smush_stats['size_before'] > 0 ? ( $smush_stats['bytes'] / $smush_stats['size_before'] ) * 100 : $stats['percent'];
		}

		update_option( 'wp_smush_stats_nextgen', $smush_stats, false );
		$this->clear_cache();
	}

	/**
	 * Clears the object cache for NextGen stats.
	 *
	 * @since 3.7.0
	 */
	public function clear_cache() {
		wp_cache_delete( 'wp_smush_images_smushed', 'nextgen' );
		wp_cache_delete( 'wp_smush_images_unsmushed', 'nextgen' );
		wp_cache_delete( 'wp_smush_images', 'nextgen' );
	}

	/**
	 * Updated the global smush stats for NextGen gallery
	 *
	 * @param int   $image_id  Image ID.
	 * @param array $stats     Compression stats for respective image.
	 */
	private function update_resize_stats( $image_id, $stats ) {
		$stats = ! empty( $stats['stats'] ) ? $stats['stats'] : '';

		$smush_stats = get_option( 'wp_smush_stats_nextgen', array() );

		if ( ! empty( $stats ) ) {
			// Compression Bytes.
			$smush_stats['bytes'] = ! empty( $smush_stats['bytes'] ) ? ( $smush_stats['bytes'] + $stats['bytes'] ) : $stats['bytes'];

			// Human Readable.
			$smush_stats['human'] = ! empty( $smush_stats['bytes'] ) ? size_format( $smush_stats['bytes'], 1 ) : '';

			// Size of images before the compression.
			$smush_stats['size_before'] = ! empty( $smush_stats['size_before'] ) ? ( $smush_stats['size_before'] + $stats['size_before'] ) : $stats['size_before'];

			// Size of image after compression.
			$smush_stats['size_after'] = ! empty( $smush_stats['size_after'] ) ? ( $smush_stats['size_after'] + $stats['size_after'] ) : $stats['size_after'];

			// Compression Percentage.
			$smush_stats['percent'] = ! empty( $smush_stats['size_before'] ) && ! empty( $smush_stats['size_after'] ) && $smush_stats['size_before'] > 0 ? ( $smush_stats['bytes'] / $smush_stats['size_before'] ) * 100 : $stats['percent'];
		}
		update_option( 'wp_smush_stats_nextgen', $smush_stats, false );
	}

	/**
	 * Get the attachment stats for a image
	 *
	 * @param object|array|int $id  Attachment ID.
	 *
	 * @return null
	 */
	private function get_attachment_stats( $id ) {
		// We'll get the image object in $id itself, else fetch it using Gallery Storage.
		if ( is_object( $id ) || is_array( $id ) ) {
			$image = $id;
		} else {
			// Registry Object for NextGen Gallery.
			$registry = C_Component_Registry::get_instance();

			// Gallery Storage Object.
			$storage = $registry->get_utility( 'I_Gallery_Storage' );

			// get an image object.
			$image = $storage->object->_image_mapper->find( $id );
		}

		// Check if we've smush stats, return it.
		if ( is_object( $image ) ) {
			if ( ! empty( $image->meta_data ) && ! empty( $image->meta_data['wp_smush'] ) ) {
				return $image->meta_data['wp_smush'];
			}
		} elseif ( is_array( $image ) ) {
			if ( ! empty( $image['wp_smush'] ) ) {
				return $image['wp_smush'];
			} elseif ( ! empty( $image['meta_data'] ) && ! empty( $image['meta_data']['wp_smush'] ) ) {
				return $image['meta_data']['wp_smush'];
			}
		}

		return null;
	}

	/**
	 * Get the Nextgen Smush stats
	 *
	 * @return bool|mixed|void
	 */
	public function get_smush_stats() {
		$smushed_stats = array(
			'savings_bytes'   => 0,
			'size_before'     => 0,
			'size_after'      => 0,
			'savings_percent' => 0,
		);

		// Clear up the stats.
		if ( 0 == $this->total_count() ) {
			delete_option( 'wp_smush_stats_nextgen' );
		}

		// Check for the  wp_smush_images in the 'nextgen' group.
		$stats = get_option( 'wp_smush_stats_nextgen', array() );

		if ( empty( $stats['bytes'] ) || $stats['bytes'] < 0 ) {
			$stats['bytes'] = 0;
		}

		if ( ! empty( $stats['size_before'] ) && $stats['size_before'] > 0 ) {
			$stats['percent'] = ( $stats['bytes'] / $stats['size_before'] ) * 100;
		}

		// Round off precentage.
		$stats['percent'] = ! empty( $stats['percent'] ) ? round( $stats['percent'], 1 ) : 0;

		$stats['human'] = size_format( $stats['bytes'], 1 );

		$smushed_stats = array_merge( $smushed_stats, $stats );

		// Gotta remove the stats for re-smush ids.
		if ( is_array( WP_Smush::get_instance()->core()->nextgen->ng_admin->resmush_ids ) && ! empty( WP_Smush::get_instance()->core()->nextgen->ng_admin->resmush_ids ) ) {
			$resmush_stats = $this->get_stats_for_ids( WP_Smush::get_instance()->core()->nextgen->ng_admin->resmush_ids );
			// Recalculate stats, Remove stats for resmush ids.
			$smushed_stats = $this->recalculate_stats( 'sub', $smushed_stats, $resmush_stats );
		}

		return $smushed_stats;
	}

	/**
	 * Updates the cache for Smushed and Unsmushed images
	 */
	public function update_cache() {
		$this->get_ngg_images( 'smushed', '', true );
	}

	/**
	 * Returns the Stats for a image formatted into a nice table
	 *
	 * @param int    $image_id             Image ID.
	 * @param array  $wp_smush_data        Smush data.
	 * @param array  $attachment_metadata  Attachment metadata.
	 * @param string $full_image           Full sized image.
	 *
	 * @return string
	 */
	private function get_detailed_stats( $image_id, $wp_smush_data, $attachment_metadata, $full_image ) {
		$stats      = '<div id="smush-stats-' . $image_id . '" class="smush-stats-wrapper hidden">
			<table class="wp-smush-stats-holder">
				<thead>
					<tr>
						<th><strong>' . esc_html__( 'Image size', 'wp-smushit' ) . '</strong></th>
						<th><strong>' . esc_html__( 'Savings', 'wp-smushit' ) . '</strong></th>
					</tr>
				</thead>
				<tbody>';
		$size_stats = $wp_smush_data['sizes'];

		// Reorder Sizes as per the maximum savings.
		uasort( $size_stats, array( $this, 'cmp' ) );

		if ( ! empty( $attachment_metadata['sizes'] ) ) {
			// Get skipped images.
			$skipped = $this->get_skipped_images( $size_stats, $full_image );

			if ( ! empty( $skipped ) ) {
				foreach ( $skipped as $img_data ) {
					$skip_class = 'size_limit' === $img_data['reason'] ? ' error' : '';
					$stats     .= '<tr>
				<td>' . strtoupper( $img_data['size'] ) . '</td>
				<td class="smush-skipped' . $skip_class . '">' . WP_Smush::get_instance()->library()->skip_reason( $img_data['reason'] ) . '</td>
			</tr>';
				}
			}
		}
		// Show Sizes and their compression.
		foreach ( $size_stats as $size_key => $size_value ) {
			$size_value = ! is_object( $size_value ) ? (object) $size_value : $size_value;
			if ( $size_value->bytes > 0 ) {
				$stats .= '<tr>
				<td>' . strtoupper( $size_key ) . '</td>
				<td>' . size_format( $size_value->bytes, 1 );

			}

			// Add percentage if set.
			if ( isset( $size_value->percent ) && $size_value->percent > 0 ) {
				$stats .= " ( $size_value->percent% )";
			}

			$stats .= '</td>
			</tr>';
		}
		$stats .= '</tbody>
			</table>
		</div>';

		return $stats;
	}

	/**
	 * Compare Values
	 *
	 * @param object|array $a  First object.
	 * @param object|array $b  Second object.
	 *
	 * @return int
	 */
	public function cmp( $a, $b ) {
		if ( is_object( $a ) ) {
			// Check and typecast $b if required.
			$b = is_object( $b ) ? $b : (object) $b;

			return $a->bytes < $b->bytes;
		} elseif ( is_array( $a ) ) {
			$b = is_array( $b ) ? $b : (array) $b;

			return $a['bytes'] < $b['bytes'];
		}
	}

	/**
	 * Return a list of images not smushed and reason
	 *
	 * @param array  $size_stats  Size stats.
	 * @param string $full_image  Full size image.
	 *
	 * @return array
	 */
	private function get_skipped_images( $size_stats, $full_image ) {
		$skipped = array();

		// If full image was not smushed, reason 1. Large Size logic, 2. Free and greater than 5Mb.
		if ( ! array_key_exists( 'full', $size_stats ) ) {
			// For free version, Check the image size.
			if ( ! $this->is_pro_user ) {
				// For free version, check if full size is greater than 5 Mb, show the skipped status.
				$file_size = file_exists( $full_image ) ? filesize( $full_image ) : '';
				if ( ! empty( $file_size ) && ( $file_size / WP_SMUSH_MAX_BYTES ) > 1 ) {
					$skipped[] = array(
						'size'   => 'full',
						'reason' => 'size_limit',
					);
				} else {
					$skipped[] = array(
						'size'   => 'full',
						'reason' => 'large_size',
					);
				}
			} else {
				// Paid version, Check if we have large size.
				if ( array_key_exists( 'large', $size_stats ) ) {
					$skipped[] = array(
						'size'   => 'full',
						'reason' => 'large_size',
					);
				}
			}
		}

		return $skipped;
	}

	/**
	 * Check if image can be resmushed
	 *
	 * @param bool  $show_resmush   Show resmush button.
	 * @param array $wp_smush_data  Smush data.
	 *
	 * @return bool
	 */
	private function show_resmush( $show_resmush, $wp_smush_data ) {
		// Resmush: Show resmush link, Check if user have enabled smushing the original and full image was skipped.
		if ( $this->settings->get( 'original' ) && WP_Smush::is_pro() ) {
			// IF full image was not smushed.
			if ( ! empty( $wp_smush_data ) && empty( $wp_smush_data['sizes']['full'] ) ) {
				$show_resmush = true;
			}
		}
		if ( $this->settings->get( 'strip_exif' ) ) {
			// If Keep Exif was set to tru initially, and since it is set to false now.
			if ( ! empty( $wp_smush_data['stats']['keep_exif'] ) && 1 == $wp_smush_data['stats']['keep_exif'] ) {
				$show_resmush = true;
			}
		}

		return $show_resmush;
	}

	/**
	 * Get the combined stats for given Ids
	 *
	 * @param array $ids  Image IDs.
	 *
	 * @return array|bool Array of Stats for the given ids
	 */
	public function get_stats_for_ids( $ids = array() ) {
		// Return if we don't have an array or no ids.
		if ( ! is_array( $ids ) || empty( $ids ) ) {
			return false;
		}

		// Initialize the Stats array.
		$stats = array(
			'size_before' => 0,
			'size_after'  => 0,
		);
		// Calculate the stats, Expensive Operation.
		foreach ( $ids as $id ) {
			$image_stats = $this->get_attachment_stats( $id );
			// Add the stats to $stats.
			foreach ( $stats as $k => $value ) {
				if ( empty( $image_stats['stats'] ) || empty( $image_stats['stats'][ $k ] ) ) {
					continue;
				}
				$stats[ $k ] += $image_stats['stats'][ $k ];
			}
		}

		// Calculate savings.
		if ( ! empty( $stats['size_before'] ) && ! empty( $stats['size_after'] ) ) {
			$stats['bytes'] = $stats['size_before'] - $stats['size_after'];
		}

		return $stats;
	}

	/**
	 * Add/Subtract the values from 2nd array to First array
	 * This function is very specific to current requirement of stats re-calculation
	 *
	 * @param string $op 'add', 'sub' Add or Subtract the values.
	 * @param array  $a1  First array.
	 * @param array  $a2  Second array.
	 *
	 * @return array Return $a1
	 */
	private function recalculate_stats( $op = 'add', $a1 = array(), $a2 = array() ) {
		// If the first array itself is not set, return.
		if ( empty( $a1 ) ) {
			return $a1;
		}

		// Iterate over keys in first array, and add/subtract the values.
		foreach ( $a1 as $k => $v ) {
			// If the key is not set in 2nd array, skip.
			if ( empty( $a2[ $k ] ) ) {
				continue;
			}
			// Else perform the operation, Considers the value to be integer, doesn't performs a check.
			if ( 'sub' === $op ) {
				// Subtract the value.
				$a1[ $k ] -= $a2[ $k ];
			} elseif ( 'add' === $op ) {
				// Add the value.
				$a1[ $k ] += $a2[ $k ];
			}
		}

		// Recalculate percentage and human savings.
		$a1['percent'] = ! empty( $a1['size_before'] ) ? ( ( $a1['bytes'] / $a1['size_before'] ) * 100 ) : 0;
		$a1['human']   = ! empty( $a1['bytes'] ) ? size_format( $a1['bytes'], 1 ) : 0;

		return $a1;
	}

	/**
	 * Get Super smushed images from the given images array
	 *
	 * @param array $images Array of images containing metadata.
	 *
	 * @return array Array containing ids of supersmushed images
	 */
	private function get_super_smushed_images( $images = array() ) {
		if ( empty( $images ) ) {
			return array();
		}
		$super_smushed = array();
		// Iterate Over all the images.
		foreach ( $images as $image_k => $image ) {
			if ( empty( $image ) || ! is_array( $image ) || ! isset( $image['wp_smush'] ) ) {
				continue;
			}
			// Check for lossy compression.
			if ( ! empty( $image['wp_smush']['stats'] ) && ! empty( $image['wp_smush']['stats']['lossy'] ) ) {
				$super_smushed[] = $image_k;
			}
		}
		return $super_smushed;
	}

	/**
	 * Recalculate stats for the given smushed ids and update the cache
	 * Update Super smushed image ids
	 *
	 * @throws Exception  Exception.
	 */
	public function update_stats_cache() {
		// Get the Image ids.
		$smushed_images = $this->get_ngg_images( 'smushed' );
		$super_smushed  = array(
			'ids'       => array(),
			'timestamp' => '',
		);

		$stats = $this->get_stats_for_ids( $smushed_images );
		$lossy = $this->get_super_smushed_images( $smushed_images );

		if ( empty( $stats['bytes'] ) && ! empty( $stats['size_before'] ) ) {
			$stats['bytes'] = $stats['size_before'] - $stats['size_after'];
		}
		$stats['human'] = size_format( ! empty( $stats['bytes'] ) ? $stats['bytes'] : 0 );
		if ( ! empty( $stats['size_before'] ) ) {
			$stats['percent'] = ( $stats['bytes'] / $stats['size_before'] ) * 100;
			$stats['percent'] = round( $stats['percent'], 2 );
		}

		$super_smushed['ids']       = $lossy;
		$super_smushed['timestamp'] = current_time( 'timestamp' );

		// Update Re-smush list.
		if ( is_array( WP_Smush::get_instance()->core()->nextgen->ng_admin->resmush_ids ) && is_array( $smushed_images ) ) {
			$resmush_ids = array_intersect( WP_Smush::get_instance()->core()->nextgen->ng_admin->resmush_ids, array_keys( $smushed_images ) );
		}

		// If we have resmush ids, add it to db.
		if ( ! empty( $resmush_ids ) ) {
			// Update re-smush images to db.
			update_option( 'wp-smush-nextgen-resmush-list', $resmush_ids, false );
		}

		// Update Super smushed images in db.
		update_option( 'wp-smush-super_smushed_nextgen', $super_smushed, false );

		// Update Stats Cache.
		update_option( 'wp_smush_stats_nextgen', $stats, false );

	}

}
