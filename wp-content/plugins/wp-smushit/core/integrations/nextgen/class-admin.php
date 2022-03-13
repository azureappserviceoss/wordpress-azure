<?php
/**
 * Adds the Bulk Page and Smush Column to NextGen Gallery
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
use Smush\App\Media_Library;
use Smush\Core\Core;
use Smush\Core\Integrations\NextGen;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Admin
 */
class Admin extends NextGen {

	/**
	 * Total image count.
	 *
	 * @var int $total_count
	 */
	public $total_count = 0;

	/**
	 * Count of images ( Attachments ), Does not includes additional sizes that might have been created.
	 *
	 * @var int $smushed_count
	 */
	public $smushed_count = 0;

	/**
	 * Includes the count of different sizes an image might have
	 *
	 * @var int $image_count
	 */
	public $image_count = 0;

	/**
	 * Remaining count.
	 *
	 * @var int $remaining_count
	 */
	public $remaining_count = 0;

	/**
	 * Super Smushed.
	 *
	 * @var int $super_smushed
	 */
	public $super_smushed = 0;

	/**
	 * Smushed images.
	 *
	 * @var array $smushed
	 */
	public $smushed = array();

	/**
	 * Stores all lossless smushed IDs.
	 *
	 * @var array $resmush_ids
	 */
	public $resmush_ids = array();

	/**
	 * Stats class object.
	 *
	 * @var Stats
	 */
	public $ng_stats;

	/**
	 * Admin constructor.
	 *
	 * @param Stats $stats  Class object.
	 */
	public function __construct( Stats $stats ) {
		$this->ng_stats = $stats;

		// Update the number of columns.
		add_filter( 'ngg_manage_images_number_of_columns', array( $this, 'wp_smush_manage_images_number_of_columns' ) );

		// Update resmush list, if a NextGen image is deleted.
		add_action( 'ngg_delete_picture', array( $this, 'update_resmush_list' ) );

		// Update Stats, if a NextGen image is deleted.
		add_action( 'ngg_delete_picture', array( $this, 'update_nextgen_stats' ) );

		// Update Stats, Lists -  if a NextGen Gallery is deleted.
		add_action( 'ngg_delete_gallery', array( $this->ng_stats, 'update_stats_cache' ) );

		// Update the Super Smush count, after the smushing.
		add_action( 'wp_smush_image_optimised_nextgen', array( $this, 'update_lists' ), '', 2 );
	}

	/**
	 * Returns a column name for WP Smush.
	 *
	 * @param array $columns  Current columns.
	 *
	 * @return array|string
	 */
	public function wp_smush_image_column_name( $columns ) {
		// Latest next gen takes string, while the earlier WP Smush plugin shows there use to be a array.
		if ( is_array( $columns ) ) {
			$columns['wp_smush_image'] = esc_html__( 'Smush', 'wp-smushit' );
		} else {
			$columns = esc_html__( 'Smush', 'wp-smushit' );
		}

		return $columns;
	}

	/**
	 * Returns Smush option / Stats, depending if image is already smushed or not.
	 *
	 * @param string     $column_name  Column name.
	 * @param object|int $id           Image object or ID.
	 *
	 * @return array|bool|string|void
	 */
	public function wp_smush_column_options( $column_name, $id ) {
		// NExtGen Doesn't returns Column name, weird? yeah, right, it is proper because hook is called for the particular column.
		if ( 'wp_smush_image' === $column_name || '' === $column_name ) {
			// We're not using our in-house function Smush\Core\Integrations\Nextgen::get_nextgen_image_from_id()
			// as we're already instializing the nextgen gallery object, we need $storage instance later.
			// Registry Object for NextGen Gallery.
			$registry = C_Component_Registry::get_instance();

			/**
			 * Gallery Storage Object.
			 *
			 * @var C_Gallery_Storage $storage
			 */
			$storage = $registry->get_utility( 'I_Gallery_Storage' );

			// We'll get the image object in $id itself, else fetch it using Gallery Storage.
			if ( is_object( $id ) ) {
				$image = $id;
			} else {
				// get an image object.
				$image = $storage->object->_image_mapper->find( $id );
			}

			// Check if it is supported image format, get image type to do that get the absolute path.
			$file_path = $storage->get_image_abspath( $image, 'full' );

			// Get image type from file path.
			$image_type = $this->get_file_type( $file_path );

			// If image type not supported.
			if ( ! $image_type || ! in_array( $image_type, Core::$mime_types, true ) ) {
				return;
			}

			$image->meta_data = $this->get_combined_stats( $image->meta_data );

			// Check Image metadata, if smushed, print the stats or super smush button.
			if ( ! empty( $image->meta_data['wp_smush'] ) ) {
				// Echo the smush stats.
				return $this->ng_stats->show_stats( $image->pid, $image->meta_data['wp_smush'], $image_type );
			}

			// Print the status of image, if Not smushed.
			return $this->set_status( $image->pid );
		}
	}

	/**
	 * Localize Translations And Stats
	 */
	public function localize() {
		$handle = 'smush-admin';

		$upgrade_url = add_query_arg(
			array(
				'utm_source'   => 'smush',
				'utm_medium'   => 'plugin',
				'utm_campaign' => 'smush_bulksmush_issues_filesizelimit_notice',
			),
			'https://wpmudev.com/project/wp-smush-pro/'
		);

		if ( WP_Smush::is_pro() ) {
			$error_in_bulk = esc_html__( '{{smushed}}/{{total}} images were successfully compressed, {{errors}} encountered issues.', 'wp-smushit' );
		} else {
			$error_in_bulk = sprintf(
				/* translators: %1$s - opening link tag, %2$s - </a> */
				esc_html__( '{{smushed}}/{{total}} images were successfully compressed, {{errors}} encountered issues. Are you hitting the 5MB "size limit exceeded" warning? %1$sUpgrade to Smush Pro for FREE%2$s to optimize unlimited image files.', 'wp-smushit' ),
				'<a href="' . esc_url( $upgrade_url ) . '" target="_blank">',
				'</a>'
			);
		}

		$wp_smush_msgs = array(
			'resmush'       => esc_html__( 'Super-Smush', 'wp-smushit' ),
			'smush_now'     => esc_html__( 'Smush Now', 'wp-smushit' ),
			'error_in_bulk' => $error_in_bulk,
			'all_resmushed' => esc_html__( 'All images are fully optimized.', 'wp-smushit' ),
			'restore'       => esc_html__( 'Restoring image...', 'wp-smushit' ),
			'smushing'      => esc_html__( 'Smushing image...', 'wp-smushit' ),
		);

		wp_localize_script( $handle, 'wp_smush_msgs', $wp_smush_msgs );

		// If premium, Super smush allowed, all images are smushed, localize lossless smushed ids for bulk compression.
		$resmush_ids = get_option( 'wp-smush-nextgen-resmush-list', array() );
		if ( $resmush_ids ) {
			$this->resmush_ids = $resmush_ids;
		}

		// Setup image counts ( Total, Smushed, Super-smushed, Remaining ).
		$this->setup_image_counts();

		// Get the Latest Stats.
		$this->stats = $this->ng_stats->get_smush_stats();

		// Get the unsmushed ids, used for localized stats as well as normal localization.
		$unsmushed = $this->ng_stats->get_ngg_images( 'unsmushed' );
		$unsmushed = ( ! empty( $unsmushed ) && is_array( $unsmushed ) ) ? array_keys( $unsmushed ) : '';

		$smushed = $this->ng_stats->get_ngg_images();
		$smushed = ( ! empty( $smushed ) && is_array( $smushed ) ) ? array_keys( $smushed ) : '';

		$this->smushed = $smushed;
		if ( ! empty( $_REQUEST['ids'] ) ) {
			// Sanitize the ids and assign it to a variable.
			$this->ids = array_map( 'intval', explode( ',', $_REQUEST['ids'] ) );
		} else {
			$this->ids = $unsmushed;
		}

		$this->super_smushed = get_option( 'wp-smush-super_smushed_nextgen', array() );
		$this->super_smushed = ! empty( $this->super_smushed['ids'] ) ? $this->super_smushed['ids'] : array();

		// If we have images to be resmushed, Update supersmush list.
		if ( ! empty( $this->resmush_ids ) && ! empty( $this->super_smushed ) ) {
			$this->super_smushed = array_diff( $this->super_smushed, $this->resmush_ids );
		}

		// If supersmushed images are more than total, clean it up.
		if ( count( $this->super_smushed ) > $this->total_count ) {
			$this->super_smushed = $this->cleanup_super_smush_data();
		}

		// Array of all smushed, unsmushed and lossless ids.
		$data = array(
			'count_smushed'      => $this->smushed_count,
			'count_supersmushed' => count( $this->super_smushed ),
			'count_total'        => $this->total_count,
			'count_images'       => $this->image_count,
			'smushed'            => $smushed,
			'unsmushed'          => $unsmushed,
			'resmush'            => $this->resmush_ids,
		);

		// Add the stats to array.
		if ( ! empty( $this->stats ) && is_array( $this->stats ) ) {
			$data = array_merge( $data, $this->stats );
		}

		wp_localize_script( $handle, 'wp_smushit_data', $data );
	}

	/**
	 * Increase the count of columns for Nextgen Gallery Manage page.
	 *
	 * @param int $count  Current columns count.
	 *
	 * @return int
	 */
	public function wp_smush_manage_images_number_of_columns( $count ) {
		$count ++;

		// Add column Heading.
		add_filter( "ngg_manage_images_column_{$count}_header", array( $this, 'wp_smush_image_column_name' ) );

		// Add Column data.
		add_filter( "ngg_manage_images_column_{$count}_content", array( $this, 'wp_smush_column_options' ), 10, 2 );

		return $count;
	}

	/**
	 * Set send button status
	 *
	 * @param int $pid  ID.
	 *
	 * @return string
	 */
	private function set_status( $pid ) {
		// the status.
		$status_txt = __( 'Not processed', 'wp-smushit' );

		// we need to show the smush button.
		$show_button = true;

		// the button text.
		$button_txt = __( 'Smush', 'wp-smushit' );

		// If we are not showing smush button, append progress bar, else it is already there.
		if ( ! $show_button ) {
			$status_txt .= Media_Library::progress_bar();
		}

		return $this->column_html( $pid, $status_txt, $button_txt, $show_button );
	}

	/**
	 * Print the column html
	 *
	 * @param string  $pid          Media id.
	 * @param string  $status_txt   Status text.
	 * @param string  $button_txt   Button label.
	 * @param boolean $show_button  Whether to shoe the button.
	 * @param bool    $smushed      Image compressed or not.
	 *
	 * @return string|void
	 */
	public function column_html( $pid, $status_txt = '', $button_txt = '', $show_button = true, $smushed = false ) {
		$class = $smushed ? '' : ' sui-hidden';
		$html  = '<p class="smush-status' . $class . '">' . $status_txt . '</p>';

		// if we aren't showing the button.
		if ( ! $show_button ) {
			return $html;
		}

		$html .= '<div class="sui-smush-media smush-status-links">';
		$html .= wp_nonce_field( 'wp_smush_nextgen', '_wp_smush_nonce', '', false );
		$html .= '<button  class="button button-primary wp-smush-nextgen-send" data-id="' . $pid . '">
				<span>' . $button_txt . '</span>
			</button>';
		$html .= '</div>';
		return $html;
	}

	/**
	 * Updates the resmush list for NextGen gallery, remove the given id
	 *
	 * @param int $attachment_id  Attachment ID.
	 */
	public function update_resmush_list( $attachment_id ) {
		WP_Smush::get_instance()->core()->mod->smush->update_resmush_list( $attachment_id, 'wp-smush-nextgen-resmush-list' );
	}

	/**
	 * Fetch the stats for the given attachment id, and subtract them from Global stats
	 *
	 * @param int $attachment_id  Attachment ID.
	 *
	 * @return bool
	 */
	public function update_nextgen_stats( $attachment_id ) {
		if ( empty( $attachment_id ) ) {
			return false;
		}

		$image_id = absint( (int) $attachment_id );

		// Get the absolute path for original image.
		$image = $this->get_nextgen_image_from_id( $image_id );

		// Image Metadata.
		$metadata = ! empty( $image ) ? $image->meta_data : '';

		$smush_stats = ! empty( $metadata['wp_smush'] ) ? $metadata['wp_smush'] : '';

		if ( empty( $smush_stats ) ) {
			return false;
		}

		$nextgen_stats = get_option( 'wp_smush_stats_nextgen', false );
		if ( ! $nextgen_stats ) {
			return false;
		}

		if ( ! empty( $nextgen_stats['size_before'] ) && ! empty( $nextgen_stats['size_after'] ) && $nextgen_stats['size_before'] > 0 && $nextgen_stats['size_after'] > 0 && $nextgen_stats['size_before'] >= $smush_stats['stats']['size_before'] ) {
			$nextgen_stats['size_before'] = $nextgen_stats['size_before'] - $smush_stats['stats']['size_before'];
			$nextgen_stats['size_after']  = $nextgen_stats['size_after'] - $smush_stats['stats']['size_after'];
			$nextgen_stats['bytes']       = $nextgen_stats['size_before'] - $nextgen_stats['size_after'];
			if ( 0 === $nextgen_stats['bytes'] && 0 === $nextgen_stats['size_before'] ) {
				$nextgen_stats['percent'] = 0;
			} else {
				$nextgen_stats['percent'] = ( $nextgen_stats['bytes'] / $nextgen_stats['size_before'] ) * 100;
			}
			$nextgen_stats['human'] = size_format( $nextgen_stats['bytes'], 1 );
		}

		// Update Stats.
		update_option( 'wp_smush_stats_nextgen', $nextgen_stats, false );

		// Remove from Super Smush list.
		WP_Smush::get_instance()->core()->mod->smush->update_super_smush_count( $attachment_id, 'remove', 'wp-smush-super_smushed_nextgen' );
	}

	/**
	 * Update the Super Smush count for NextGen Gallery
	 *
	 * @param int   $image_id  Image ID.
	 * @param array $stats     Stats.
	 */
	public function update_lists( $image_id, $stats ) {
		WP_Smush::get_instance()->core()->mod->smush->update_lists( $image_id, $stats, 'wp-smush-super_smushed_nextgen' );
		if ( ! empty( $this->resmush_ids ) && in_array( $image_id, $this->resmush_ids ) ) {
			$this->update_resmush_list( $image_id );
		}
	}

	/**
	 * Initialize NextGen Gallery Stats
	 */
	public function setup_image_counts() {
		$smushed_images = $this->ng_stats->get_ngg_images( 'smushed' );

		// Check if resmush ids are not set, get it.
		if ( empty( $this->resmush_ids ) ) {
			$this->resmush_ids = get_option( 'wp-smush-nextgen-resmush-list', array() );
		}

		// Includes the count of different sizes an image might have.
		$this->image_count = $this->get_image_count( $smushed_images, false );

		// If we have images to be resmushed, exclude it.
		if ( ! empty( $this->resmush_ids ) && is_array( $smushed_images ) ) {
			// Get the Smushed images, exlude resmush ids.
			$smushed_images = array_diff_key( $smushed_images, array_flip( $this->resmush_ids ) );
		}

		// Set the counts.
		$this->total_count = $this->ng_stats->total_count();

		// Count of images ( Attachments ), Does not includes additioanl sizes that might have been created.
		$this->smushed_count = isset( $smushed_images ) && is_array( $smushed_images ) ? count( $smushed_images ) : $smushed_images;

		$this->super_smushed = get_option( 'wp-smush-super_smushed_nextgen', array() );
		$this->super_smushed = ! empty( $this->super_smushed['ids'] ) ? count( $this->super_smushed['ids'] ) : 0;

		$this->remaining_count = $this->ng_stats->get_ngg_images( 'unsmushed', true );
	}

	/**
	 * Get the image count for nextgen images
	 *
	 * @param array $images               Array of attachments to get the image count for.
	 * @param bool  $exclude_resmush_ids  Whether to exclude resmush ids or not.
	 *
	 * @return int
	 */
	public function get_image_count( $images = array(), $exclude_resmush_ids = true ) {
		if ( empty( $images ) || ! is_array( $images ) ) {
			return 0;
		}

		$image_count = 0;
		// $image in here is expected to be metadata array
		foreach ( $images as $image_k => $image ) {
			// Get image object if not there already.
			if ( ! is_array( $image ) ) {
				$image = $this->get_nextgen_image_from_id( $image );
				// Get the meta.
				$image = ! empty( $image->meta_data ) ? $image->meta_data : '';
			}
			// If there are no smush stats, skip.
			if ( empty( $image['wp_smush'] ) ) {
				continue;
			}

			// If resmush ids needs to be excluded.
			if ( $exclude_resmush_ids && ( ! empty( $this->resmush_ids ) && in_array( $image_k, $this->resmush_ids ) ) ) {
				continue;
			}

			// Get the image count.
			if ( ! empty( $image['wp_smush']['sizes'] ) ) {
				$image_count += count( $image['wp_smush']['sizes'] );
			}
		}

		return $image_count;
	}

	/**
	 * Combine the resizing stats and smush stats , One time operation - performed during the image optimization
	 *
	 * @param array $metadata  Image metadata.
	 *
	 * @return mixed
	 */
	private function get_combined_stats( $metadata ) {
		if ( empty( $metadata ) ) {
			return $metadata;
		}

		$smush_stats    = ! empty( $metadata['wp_smush'] ) ? $metadata['wp_smush'] : '';
		$resize_savings = ! empty( $metadata['wp_smush_resize_savings'] ) ? $metadata['wp_smush_resize_savings'] : '';

		if ( empty( $resize_savings ) || empty( $smush_stats ) ) {
			return $metadata;
		}

		$smush_stats['stats']['bytes']       = ! empty( $resize_savings['bytes'] ) ? $smush_stats['stats']['bytes'] + $resize_savings['bytes'] : $smush_stats['stats']['bytes'];
		$smush_stats['stats']['size_before'] = ! empty( $resize_savings['size_before'] ) ? $smush_stats['stats']['size_before'] + $resize_savings['size_before'] : $smush_stats['stats']['size_before'];
		$smush_stats['stats']['size_after']  = ! empty( $resize_savings['size_after'] ) ? $smush_stats['stats']['size_after'] + $resize_savings['size_after'] : $smush_stats['stats']['size_after'];
		$smush_stats['stats']['percent']     = ! empty( $resize_savings['size_before'] ) ? ( $smush_stats['stats']['bytes'] / $smush_stats['stats']['size_before'] ) * 100 : $smush_stats['stats']['percent'];

		// Round off.
		$smush_stats['stats']['percent'] = round( $smush_stats['stats']['percent'], 2 );

		if ( ! empty( $smush_stats['sizes']['full'] ) ) {
			// Full Image.
			$smush_stats['sizes']['full']['bytes']       = ! empty( $resize_savings['bytes'] ) ? $smush_stats['sizes']['full']['bytes'] + $resize_savings['bytes'] : $smush_stats['sizes']['full']['bytes'];
			$smush_stats['sizes']['full']['size_before'] = ! empty( $resize_savings['size_before'] ) ? $smush_stats['sizes']['full']['size_before'] + $resize_savings['size_before'] : $smush_stats['sizes']['full']['size_before'];
			$smush_stats['sizes']['full']['size_after']  = ! empty( $resize_savings['size_after'] ) ? $smush_stats['sizes']['full']['size_after'] + $resize_savings['size_after'] : $smush_stats['sizes']['full']['size_after'];
			$smush_stats['sizes']['full']['percent']     = ! empty( $smush_stats['sizes']['full']['bytes'] ) && $smush_stats['sizes']['full']['size_before'] > 0 ? ( $smush_stats['sizes']['full']['bytes'] / $smush_stats['sizes']['full']['size_before'] ) * 100 : $smush_stats['sizes']['full']['percent'];

			$smush_stats['sizes']['full']['percent'] = round( $smush_stats['sizes']['full']['percent'], 2 );
		} else {
			$smush_stats['sizes']['full'] = $resize_savings;
		}

		$metadata['wp_smush'] = $smush_stats;

		return $metadata;
	}

	/**
	 * Cleanup Super-smush images array against the all ids in gallery
	 *
	 * @return array|mixed|void
	 */
	private function cleanup_super_smush_data() {
		$super_smushed = get_option( 'wp-smush-super_smushed_nextgen', array() );
		$ids           = $this->ng_stats->total_count( false, true );

		if ( is_array( $super_smushed ) && ! empty( $super_smushed['ids'] ) && is_array( $ids ) ) {
			$super_smushed['ids'] = array_intersect( $super_smushed['ids'], $ids );
		}

		update_option( 'wp-smush-super_smushed_nextgen', $super_smushed );

		return $super_smushed['ids'];

	}

}
