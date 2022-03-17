<?php
/**
 * NextGen integration: NextGen class
 *
 * @package Smush\Core\Integrations
 * @version 1.0
 *
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2016, Incsub (http://incsub.com)
 */

namespace Smush\Core\Integrations;

use C_Component_Registry;
use C_Gallery_Storage;
use Exception;
use nggdb;
use Smush\Core\Core;
use Smush\Core\Helper;
use stdClass;
use WP_Error;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class NextGen
 */
class NextGen extends Abstract_Integration {

	/**
	 * Contains the total Stats, for displaying it on bulk page
	 *
	 * @var array $stats
	 */
	public $stats = array(
		'savings_bytes'   => 0,
		'size_before'     => 0,
		'size_after'      => 0,
		'savings_percent' => 0,
	);

	/**
	 * NextGen admin module.
	 *
	 * @var Nextgen\Admin
	 */
	public $ng_admin;

	/**
	 * NextGen stats module.
	 *
	 * @var Nextgen\Stats
	 */
	public $ng_stats;

	/**
	 * Nextgen constructor.
	 */
	public function __construct() {
		$this->module  = 'nextgen';
		$this->class   = 'pro';
		$this->enabled = class_exists( '\C_NextGEN_Bootstrap' );

		parent::__construct();

		$is_pro = WP_Smush::is_pro();

		// Hook at the end of setting row to output a error div.
		add_action( 'smush_setting_column_right_inside', array( $this, 'additional_notice' ) );

		// Do not continue if not PRO member or NextGen plugin not installed.
		if ( ! $is_pro || ! $this->enabled || ! $this->is_enabled() ) {
			// Add Pro tag.
			add_action( 'smush_setting_column_tag', array( $this, 'add_pro_tag' ) );
			return;
		}

		$this->add_mixins();

		add_action( 'admin_init', array( $this, 'init_modules' ) );

		/**
		 * FILTERS
		 */
		// Show submit button when Gutenberg is active.
		add_filter( 'wp_smush_integration_show_submit', '__return_true' );

		/**
		 * ACTIONS
		 */
		// Auto Smush image, if enabled, runs after NextGen is finished uploading the image.
		// Allows to override whether to auto smush NextGen image or not.
		$auto_smush = apply_filters( 'smush_nextgen_auto', WP_Smush::get_instance()->core()->mod->smush->is_auto_smush_enabled() );
		if ( $auto_smush ) {
			add_action( 'ngg_added_new_image', array( $this, 'auto_smush' ) );
		}

		// Update Total Image count.
		add_action( 'ngg_added_new_image', array( $this, 'update_stats_image_count' ), 10 );

		/**
		 * AJAX
		 */
		// Single Smush/Manual Smush: Handles the Single/Manual smush request for NextGen Gallery.
		add_action( 'wp_ajax_smush_manual_nextgen', array( $this, 'manual_nextgen' ) );
		// Restore Image: Handles the single/Manual restore image request for NextGen Gallery.
		add_action( 'wp_ajax_smush_restore_nextgen_image', array( $this, 'restore_image' ) );
		// Resmush Image: Handles the single/Manual resmush image request for NextGen Gallery.
		add_action( 'wp_ajax_smush_resmush_nextgen_image', array( $this, 'resmush_image' ) );
		// Bulk Smush NextGen images.
		add_action( 'wp_ajax_wp_smushit_nextgen_bulk', array( $this, 'smush_bulk' ) );
	}

	/**************************************
	 *
	 * OVERWRITE PARENT CLASS FUNCTIONALITY
	 */

	/**
	 * Filters the setting variable to add NextGen setting title and description
	 *
	 * @param array $settings Settings.
	 *
	 * @return mixed
	 */
	public function register( $settings ) {
		$settings[ $this->module ] = array(
			'label'       => esc_html__( 'Enable NextGen Gallery integration', 'wp-smushit' ),
			'short_label' => esc_html__( 'NextGen Gallery', 'wp-smushit' ),
			'desc'        => esc_html__( 'Allow smushing images directly through NextGen Gallery settings.', 'wp-smushit' ),
		);

		return $settings;
	}

	/**************************************
	 *
	 * PUBLIC CLASSES
	 */

	/**
	 * Initialize the stats and admin modules, once admin is ready.
	 *
	 * @since 3.3.0
	 */
	public function init_modules() {
		$this->ng_stats = new NextGen\Stats();
		$this->ng_admin = new NextGen\Admin( $this->ng_stats );
	}

	/**
	 * Check if NextGen integration is active.
	 *
	 * @since 2.9.0
	 *
	 * @return bool|mixed
	 */
	public function is_enabled() {
		return $this->settings->get( 'nextgen' );
	}

	/**
	 * Bulk Smush for Nextgen.
	 *
	 * @throws Exception  Exception.
	 */
	public function smush_bulk() {
		$stats = array();

		if ( empty( $_GET['attachment_id'] ) ) {
			wp_send_json_error(
				array(
					'error'         => 'missing_id',
					'error_message' => esc_html__( 'No attachment ID was received', 'wp-smushit' ),
					'file_name'     => 'undefined',
				)
			);
		}

		$atchmnt_id = (int) $_GET['attachment_id'];

		$smush = $this->smush_image( $atchmnt_id, '', true );

		if ( is_wp_error( $smush ) ) {
			$error_message = $smush->get_error_message();

			// Check for timeout error and suggest to filter timeout.
			if ( strpos( $error_message, 'timed out' ) ) {
				$error         = 'timeout';
				$error_message = esc_html__( 'Smush request timed out. You can try setting a higher value ( > 60 ) for `WP_SMUSH_TIMEOUT`.', 'wp-smushit' );
			}

			$error     = isset( $error ) ? $error : 'other';
			$file_name = $this->get_nextgen_image_from_id( $atchmnt_id );

			wp_send_json_error(
				array(
					'error'         => $error,
					'stats'         => $stats,
					'error_message' => $error_message,
					'file_name'     => isset( $file_name->filename ) ? $file_name->filename : 'undefined',
				)
			);
		}

		// Check if a re-Smush request, update the re-Smush list.
		if ( ! empty( $_REQUEST['is_bulk_resmush'] ) && $_REQUEST['is_bulk_resmush'] ) {
			WP_Smush::get_instance()->core()->mod->smush->update_resmush_list( $atchmnt_id, 'wp-smush-nextgen-resmush-list' );
		}
		$stats['is_lossy'] = ! empty( $smush['stats'] ) ? $smush['stats']['lossy'] : 0;

		// Size before and after smush.
		$stats['size_before'] = ! empty( $smush['stats'] ) ? $smush['stats']['size_before'] : 0;
		$stats['size_after']  = ! empty( $smush['stats'] ) ? $smush['stats']['size_after'] : 0;

		// Get the re-Smush IDs list.
		if ( empty( $this->ng_admin->resmush_ids ) ) {
			$this->ng_admin->resmush_ids = get_option( 'wp-smush-nextgen-resmush-list' );
		}

		$this->ng_admin->resmush_ids = empty( $this->ng_admin->resmush_ids ) ? get_option( 'wp-smush-nextgen-resmush-list' ) : array();
		$resmush_count               = ! empty( $this->ng_admin->resmush_ids ) ? count( $this->ng_admin->resmush_ids ) : 0;
		$smushed_images              = $this->ng_stats->get_ngg_images( 'smushed' );

		// Remove re-Smush IDs from smushed images list.
		if ( $resmush_count > 0 && is_array( $this->ng_admin->resmush_ids ) ) {
			foreach ( $smushed_images as $image_k => $image ) {
				if ( in_array( $image_k, $this->ng_admin->resmush_ids, true ) ) {
					unset( $smushed_images[ $image_k ] );
				}
			}
		}

		// Get the image count and smushed images count.
		$image_count   = ! empty( $smush ) && ! empty( $smush['sizes'] ) ? count( $smush['sizes'] ) : 0;
		$smushed_count = is_array( $smushed_images ) ? count( $smushed_images ) : 0;

		$stats['smushed'] = ! empty( $this->ng_admin->resmush_ids ) ? $smushed_count - $resmush_count : $smushed_count;
		$stats['count']   = $image_count;

		wp_send_json_success(
			array(
				'stats' => $stats,
			)
		);
	}

	/**
	 * Show additional notice if the required plugins are not installed.
	 *
	 * @since 2.8.0
	 *
	 * @param string $name  Setting name.
	 */
	public function additional_notice( $name ) {
		if ( $this->module === $name && ! $this->enabled ) {
			?>
			<div class="sui-toggle-content">
				<div class="sui-notice">
					<div class="sui-notice-content">
						<div class="sui-notice-message">
							<i class="sui-notice-icon sui-icon-info" aria-hidden="true"></i>
							<p><?php esc_html_e( 'To use this feature you need to be using NextGen Gallery.', 'wp-smushit' ); ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Get the NextGen Image object from attachment id
	 *
	 * @param string $pid  NextGen Gallery Image ID.
	 *
	 * @return object
	 */
	public function get_nextgen_image_from_id( $pid ) {
		// Registry Object for NextGen Gallery.
		$registry = C_Component_Registry::get_instance();

		// Gallery Storage Object.
		$storage = $registry->get_utility( 'I_Gallery_Storage' );

		$image = $storage->object->_image_mapper->find( $pid );

		return $image;
	}

	/**
	 * Get image mime type
	 *
	 * @param string $file_path  File path.
	 *
	 * @return bool|string
	 */
	public function get_file_type( $file_path ) {
		if ( empty( $file_path ) || ! file_exists( $file_path ) ) {
			return false;
		}

		$image_mime = false;

		if ( function_exists( 'exif_imagetype' ) ) {
			$image_type = exif_imagetype( $file_path );
			if ( ! empty( $image_type ) ) {
				$image_mime = image_type_to_mime_type( $image_type );
			}
		} else {
			$image_details = getimagesize( $file_path );
			$image_mime    = ! empty( $image_details ) && is_array( $image_details ) ? $image_details['mime'] : '';
		}

		return $image_mime;
	}

	/**
	 * Performs the actual smush process
	 *
	 * @usedby: `manual_nextgen`, `auto_smush`, `smush_bulk`
	 *
	 * @param string $pid      NextGen Gallery Image id.
	 * @param string $image    Nextgen gallery image object.
	 * @param bool   $is_bulk  Whether it's called by bulk smush or not.
	 *
	 * @return mixed Stats / Status / Error
	 */
	public function smush_image( $pid = '', $image = '', $is_bulk = false ) {
		// Get image, if we have image id.
		if ( ! empty( $pid ) ) {
			$image = $this->get_nextgen_image_from_id( $pid );
		} elseif ( ! empty( $image ) ) {
			$pid = $this->get_nextgen_id_from_image( $image );
		}

		$metadata = ! empty( $image ) ? $image->meta_data : '';

		if ( empty( $metadata ) ) {
			/**
			 * We use error_msg for single images to append to the div and error_message to
			 * append to bulk smush errors list.
			 */
			wp_send_json_error(
				array(
					'error'         => 'no_metadata',
					'error_msg'     => '<p class="wp-smush-error-message">' . esc_html__( "We couldn't find the metadata for the image, possibly the image has been deleted.", 'wp-smushit' ) . '</p>',
					'error_message' => esc_html__( "We couldn't find the metadata for the image, possibly the image has been deleted.", 'wp-smushit' ),
					'file_name'     => isset( $image->filename ) ? $image->filename : 'undefined',
				)
			);
		}

		$registry = C_Component_Registry::get_instance();
		$storage  = $registry->get_utility( 'I_Gallery_Storage' );

		// Perform Resizing.
		$metadata = $this->resize_image( $pid, $image, $metadata, $storage );

		// Store Meta.
		$image->meta_data = $metadata;
		nggdb::update_image_meta( $image->pid, $image->meta_data );

		// Smush the main image and its sizes.
		$smush = $this->resize_from_meta_data( $image );

		$status = '';
		if ( ! is_wp_error( $smush ) ) {
			$status = $this->ng_stats->show_stats( $pid, $smush );
		}

		if ( ! $is_bulk ) {
			if ( is_wp_error( $smush ) ) {
				return $smush;
			}

			return $status;
		}

		return $smush;
	}

	/**
	 * Refreshes the total image count from the stats when a new image is added to nextgen gallery
	 * Should be called only if image count need to be updated, use total_count(), otherwise
	 */
	public function update_stats_image_count() {
		NextGen\Stats::total_count( true );
	}

	/**
	 * Handles the smushing of each image and its registered sizes
	 * Calls the function to update the compression stats
	 */
	public function manual_nextgen() {
		$pid   = ! empty( $_GET['attachment_id'] ) ? absint( (int) $_GET['attachment_id'] ) : '';
		$nonce = ! empty( $_GET['_nonce'] ) ? $_GET['_nonce'] : '';

		// Verify Nonce.
		if ( ! wp_verify_nonce( $nonce, 'wp_smush_nextgen' ) ) {
			wp_send_json_error(
				array(
					'error' => 'nonce_verification_failed',
				)
			);
		}

		// Check for media upload permission.
		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error(
				array(
					'error_msg' => __( "You don't have permission to work with uploaded files.", 'wp-smushit' ),
				)
			);
		}

		if ( empty( $pid ) ) {
			wp_send_json_error(
				array(
					'error_msg' => __( 'No attachment ID was provided.', 'wp-smushit' ),
				)
			);
		}

		$status = $this->smush_image( $pid );

		// Send stats.
		if ( is_wp_error( $status ) ) {
			/**
			 * Not used for bulk smush.
			 *
			 * @param WP_Error $smush
			 */
			wp_send_json_error( $status->get_error_message() );
		}

		wp_send_json_success( $status );
	}

	/**
	 * Process auto smush request for NextGen gallery images.
	 *
	 * @param stdClass $image  Image.
	 */
	public function auto_smush( $image ) {
		if ( ! $this->ng_stats || ! $this->ng_admin ) {
			$this->init_modules();
		}

		$this->smush_image( '', $image );
	}


	/**
	 * Checks for file backup, if available for any of the size,
	 * Function returns true
	 *
	 * @param string $pid              NextGen gallery image ID.
	 * @param array  $attachment_data  Attachment data.
	 *
	 * @return bool
	 */
	public function show_restore_option( $pid, $attachment_data ) {
		$backup = WP_Smush::get_instance()->core()->mod->backup;

		// Registry Object for NextGen Gallery.
		$registry = C_Component_Registry::get_instance();

		/**
		 * Gallery Storage Object.
		 *
		 * @var C_Gallery_Storage $storage
		 */
		$storage = $registry->get_utility( 'I_Gallery_Storage' );

		$image = $storage->object->_image_mapper->find( $pid );

		// Get image full path.
		$attachment_file_path = $storage->get_image_abspath( $image, 'full' );

		// Get the backup path.
		$backup_path = $backup->get_image_backup_path( $attachment_file_path );

		// If one of the backup(Ours/NextGen) exists, show restore option.
		if ( file_exists( $backup_path ) || file_exists( $attachment_file_path . '_backup' ) ) {
			return true;
		}

		// Get Sizes, and check for backup.
		if ( empty( $attachment_data['sizes'] ) ) {
			return false;
		}

		foreach ( $attachment_data['sizes'] as $size => $size_data ) {
			if ( 'full' === $size ) {
				continue;
			}

			// Get file path.
			$attachment_size_file_path = $storage->get_image_abspath( $image, $size );

			// Get the backup path.
			$backup_path = $backup->get_image_backup_path( $attachment_size_file_path );

			// If one of the backup(Ours/NextGen) exists, show restore option.
			if ( file_exists( $backup_path ) || file_exists( $attachment_size_file_path . '_backup' ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Handles the ajax request to restore a image from backup and return button HTML
	 *
	 * @uses Nextgen\Admin::wp_smush_column_options()
	 */
	public function restore_image() {
		// Check Empty fields.
		if ( empty( $_POST['attachment_id'] ) || empty( $_POST['_nonce'] ) ) {
			wp_send_json_error(
				array(
					'error'   => 'empty_fields',
					'message' => esc_html__( 'Error in processing restore action, Fields empty.', 'wp-smushit' ),
				)
			);
		}

		// Check Nonce.
		if ( ! wp_verify_nonce( $_POST['_nonce'], 'wp-smush-restore-' . $_POST['attachment_id'] ) ) {
			wp_send_json_error(
				array(
					'error'   => 'empty_fields',
					'message' => esc_html__( 'Image not restored, Nonce verification failed.', 'wp-smushit' ),
				)
			);
		}

		$backup = WP_Smush::get_instance()->core()->mod->backup;

		// Store the restore success/failure for all the sizes.
		$restored = array();

		// Registry Object for NextGen Gallery.
		$registry = C_Component_Registry::get_instance();

		/**
		 * Gallery Storage Object.
		 *
		 * @var C_Gallery_Storage $storage
		 */
		$storage = $registry->get_utility( 'I_Gallery_Storage' );

		// Process Now.
		$image_id = absint( (int) $_POST['attachment_id'] );

		// Get the absolute path for original image.
		$image = $this->get_nextgen_image_from_id( $image_id );

		// Get image full path.
		$attachment_file_path = $storage->get_image_abspath( $image, 'full' );

		// Get the backup path.
		$backup_path = $backup->get_image_backup_path( $attachment_file_path );

		// Restoring the full image.
		// If file exists, corresponding to our backup path.
		if ( file_exists( $backup_path ) ) {
			// Restore.
			$restored[] = @copy( $backup_path, $attachment_file_path );

			// Delete the backup.
			@unlink( $backup_path );
		} elseif ( file_exists( $attachment_file_path . '_backup' ) ) {
			// Restore from other backups.
			$restored[] = @copy( $attachment_file_path . '_backup', $attachment_file_path );
		}
		// Restoring the other sizes.
		$attachment_data = ! empty( $image->meta_data['wp_smush'] ) ? $image->meta_data['wp_smush'] : array();
		if ( isset( $attachment_data['sizes'] ) && ! empty( $attachment_data['sizes'] ) ) {
			foreach ( $attachment_data['sizes'] as $size => $size_data ) {
				if ( 'full' === $size ) {
					continue;
				}
				// Get file path.
				$attachment_size_file_path = $storage->get_image_abspath( $image, $size );

				// Get the backup path.
				$backup_path = $backup->get_image_backup_path( $attachment_size_file_path );

				// If file exists, corresponding to our backup path.
				if ( file_exists( $backup_path ) ) {
					// Restore.
					$restored[] = @copy( $backup_path, $attachment_size_file_path );

					// Delete the backup.
					@unlink( $backup_path );
				} elseif ( file_exists( $attachment_size_file_path . '_backup' ) ) {
					// Restore from other backups.
					$restored[] = @copy( $attachment_size_file_path . '_backup', $attachment_size_file_path );
				}
			}
		}

		// If any of the image is restored, we count it as success.
		if ( in_array( true, $restored ) ) {
			// Update the global Stats.
			$this->ng_admin->update_nextgen_stats( $image_id );

			// Remove the Meta, And send json success.
			$image->meta_data['wp_smush'] = '';
			nggdb::update_image_meta( $image->pid, $image->meta_data );

			// Get the Button html without wrapper.
			$button_html = $this->ng_admin->wp_smush_column_options( '', $image_id );

			/**
			 * Called after the image has been successfully restored
			 *
			 * @since 3.7.0
			 *
			 * @param int $image_id ID of the restored image.
			 */
			do_action( 'wp_smush_image_nextgen_restored', $image_id );

			wp_send_json_success(
				array(
					'stats' => $button_html,
				)
			);
		}

		wp_send_json_error(
			array(
				'message' => '<div class="wp-smush-error">' . __( 'Unable to restore image', 'wp-smushit' ) . '</div>',
			)
		);
	}

	/**
	 * Handles the Ajax request to resmush a image, if the full image wasn't smushed earlier
	 */
	public function resmush_image() {
		// Check Empty fields.
		if ( empty( $_POST['attachment_id'] ) || empty( $_POST['_nonce'] ) ) {
			wp_send_json_error(
				array(
					'error_msg' => '<div class="wp-smush-error">' . esc_html__( "We couldn't process the image, fields empty.", 'wp-smushit' ) . '</div>',
				)
			);
		}

		// Check Nonce.
		if ( ! wp_verify_nonce( $_POST['_nonce'], 'wp-smush-resmush-' . $_POST['attachment_id'] ) ) {
			wp_send_json_error(
				array(
					'error_msg' => '<div class="wp-smush-error">' . esc_html__( "Image couldn't be smushed as the nonce verification failed, try reloading the page.", 'wp-smushit' ) . '</div>',
				)
			);
		}

		$status = $this->smush_image( (int) $_POST['attachment_id'] );

		// If any of the image is restored, we count it as success.
		if ( ! empty( $status ) && ! is_wp_error( $status ) ) {
			// Send button content.
			wp_send_json_success(
				array(
					'stats' => $status,
				)
			);
		} elseif ( is_wp_error( $status ) ) {
			// Send Error Message.
			wp_send_json_error(
				array(
					'error_msg' => sprintf(
						/* translators: %s: error message */
						'<div class="wp-smush-error">' . __( 'Unable to smush image, %s', 'wp-smushit' ) . '</div>',
						$status->get_error_message()
					),
				)
			);
		}
	}

	/**
	 * Add a pro tag next to the setting title.
	 *
	 * @param string $setting_key  Setting key name.
	 *
	 * @since 3.4.0
	 */
	public function add_pro_tag( $setting_key ) {
		// Return if not NextGen integration.
		if ( $this->module !== $setting_key || WP_Smush::is_pro() ) {
			return;
		}
		?>
		<span class="sui-tag sui-tag-pro"><?php esc_html_e( 'Pro', 'wp-smushit' ); ?></span>
		<?php
	}

	/**************************************
	 *
	 * PRIVATE CLASSES
	 */

	/**
	 * Queries Nextgen table for a list of image ids
	 *
	 * @return mixed  Array of IDs.
	 */
	protected static function get_nextgen_attachments() {
		global $wpdb;

		// Query images from the nextgen table.
		$images = $wpdb->get_col( "SELECT pid FROM $wpdb->nggpictures ORDER BY pid ASC" ); // db-query ok; no-cache ok.

		// Return empty array, if there was error querying the images.
		if ( empty( $images ) || is_wp_error( $images ) ) {
			$images = array();
		}

		return $images;
	}

	/**
	 * Get the NextGen attachment id from image object
	 *
	 * @param $image
	 *
	 * @return mixed
	 */
	private function get_nextgen_id_from_image( $image ) {
		// Registry Object for NextGen Gallery.
		$registry = C_Component_Registry::get_instance();

		/**
		 * Gallery Storage Object.
		 *
		 * @var C_Gallery_Storage $storage
		 */
		$storage = $registry->get_utility( 'I_Gallery_Storage' );

		$pid = $storage->object->_get_image_id( $image );

		return $pid;
	}

	/**
	 * Read the image paths from an attachment's metadata and process each image
	 * with wp_smushit().
	 *
	 * @param $image
	 *
	 * @return mixed
	 */
	private function resize_from_meta_data( $image ) {
		$smush = WP_Smush::get_instance()->core()->mod->smush;

		$errors = new WP_Error();
		$stats  = array(
			'stats' => array_merge(
				$smush->get_size_signature(),
				array(
					'api_version' => - 1,
					'lossy'       => - 1,
				)
			),
			'sizes' => array(),
		);

		// Registry Object for NextGen Gallery.
		$registry = C_Component_Registry::get_instance();

		/**
		 * Gallery Storage Object.
		 *
		 * @var C_Gallery_Storage $storage
		 */
		$storage = $registry->get_utility( 'I_Gallery_Storage' );

		// File path and URL for original image.
		// Get an array of sizes available for the $image.
		$sizes = $storage->get_image_sizes();

		// If images has other registered size, smush them first.
		if ( ! empty( $sizes ) ) {
			foreach ( $sizes as $size ) {
				// Skip Full size, if smush original is not checked.
				if ( 'full' === $size && ! $this->settings->get( 'original' ) ) {
					continue;
				}

				// Check if registered size is supposed to be converted or not.
				if ( 'full' !== $size && $smush->skip_image_size( $size ) ) {
					return false;
				}

				// We take the original image. Get the absolute path using the storage object.
				$attachment_file_path_size = $storage->get_image_abspath( $image, $size );

				$ext = Helper::get_mime_type( $attachment_file_path_size );

				if ( $ext ) {
					$valid_mime = array_search(
						$ext,
						array(
							'jpg' => 'image/jpeg',
							'png' => 'image/png',
							'gif' => 'image/gif',
						),
						true
					);

					if ( false === $valid_mime ) {
						continue;
					}
				}

				/**
				 * Allows to skip a image from smushing
				 *
				 * @param bool , Smush image or not
				 * @$size string, Size of image being smushed
				 */
				$smush_image = apply_filters( 'wp_smush_nextgen_image', true, $size );

				if ( ! $smush_image ) {
					continue;
				}

				// Store details for each size key.
				$response = $smush->do_smushit( $attachment_file_path_size );

				if ( is_wp_error( $response ) ) {
					return $response;
				}

				// If there are no stats.
				if ( empty( $response['data'] ) ) {
					continue;
				}

				// If the image size grew after smushing, skip it.
				if ( $response['data']->after_size > $response['data']->before_size ) {
					continue;
				}

				$stats['sizes'][ $size ] = (object) $smush->array_fill_placeholders( $smush->get_size_signature(), (array) $response['data'] );

				if ( empty( $stats['stats']['api_version'] ) || - 1 == $stats['stats']['api_version'] ) {
					$stats['stats']['api_version'] = $response['data']->api_version;
					$stats['stats']['lossy']       = $response['data']->lossy;
					$stats['stats']['keep_exif']   = ! empty( $response['data']->keep_exif ) ? $response['data']->keep_exif : 0;
				}
			}
		}

		$has_errors = (bool) count( $errors->get_error_messages() );

		// Set smush status for all the images, store it in wp-smpro-smush-data.
		if ( ! $has_errors ) {
			$existing_stats = ( ! empty( $image->meta_data ) && ! empty( $image->meta_data['wp_smush'] ) ) ? $image->meta_data['wp_smush'] : array();

			if ( ! empty( $existing_stats ) ) {
				// Update stats for each size.
				if ( ! empty( $existing_stats['sizes'] ) && ! empty( $stats['sizes'] ) ) {
					foreach ( $existing_stats['sizes'] as $size_name => $size_stats ) {
						// If stats for a particular size doesn't exists.
						if ( empty( $stats['sizes'] ) || empty( $stats['sizes'][ $size_name ] ) ) {
							$stats = empty( $stats ) ? array() : $stats;
							if ( empty( $stats['sizes'] ) ) {
								$stats['sizes'] = array();
							}
							$stats['sizes'][ $size_name ] = $existing_stats['sizes'][ $size_name ];
						} else {
							$existing_stats_size = (object) $existing_stats['sizes'][ $size_name ];

							// Store the original image size.
							$stats['sizes'][ $size_name ]->size_before = ( ! empty( $existing_stats_size->size_before ) && $existing_stats_size->size_before > $stats['sizes'][ $size_name ]->size_before ) ? $existing_stats_size->size_before : $stats['sizes'][ $size_name ]->size_before;

							// Update compression percent and bytes saved for each size.
							$stats['sizes'][ $size_name ]->bytes = $stats['sizes'][ $size_name ]->bytes + $existing_stats_size->bytes;
							// Calculate percentage.
							$stats['sizes'][ $size_name ]->percent = $smush->calculate_percentage( $stats['sizes'][ $size_name ], $existing_stats_size );
						}
					}
				}
			}

			// Total Stats.
			$stats                 = WP_Smush::get_instance()->core()->total_compression( $stats );
			$stats['total_images'] = ! empty( $stats['sizes'] ) ? count( $stats['sizes'] ) : 0;

			// If there was any compression and there was no error in smushing.
			if ( isset( $stats['stats']['bytes'] ) && $stats['stats']['bytes'] >= 0 && ! $has_errors ) {
				/**
				 * Runs if the image smushing was successful
				 *
				 * @param int $ID Image Id
				 *
				 * @param array $stats Smush Stats for the image
				 */
				do_action( 'wp_smush_image_optimised_nextgen', $image->pid, $stats );
			}
			$image->meta_data['wp_smush'] = $stats;
			nggdb::update_image_meta( $image->pid, $image->meta_data );

			// Allows To get the stats for each image, after the image is smushed.
			do_action( 'wp_smush_nextgen_image_stats', $image->pid, $stats );
		}

		return $image->meta_data['wp_smush'];
	}

	/**
	 * Get file extension from file path
	 *
	 * @param string $file_path Absolute image path to get the mime for.
	 *
	 * @return string Null/ Mime Type
	 */
	private function get_file_ext( $file_path = '' ) {
		if ( empty( $file_path ) ) {
			return '';
		}

		return Helper::get_mime_type( $file_path );
	}

	/**
	 * Optionally resize a NextGen image
	 *
	 * @param int               $attachment_id  Gallery Image id.
	 * @param stdClass          $image          Image object for NextGen gallery.
	 * @param string|array      $meta           Image meta from nextgen gallery.
	 * @param C_Gallery_Storage $storage        Storage object for nextgen gallery.
	 *
	 * @return mixed
	 */
	private function resize_image( $attachment_id, $image, $meta, $storage ) {
		if ( empty( $attachment_id ) || empty( $meta ) || ! is_object( $storage ) ) {
			return $meta;
		}

		$resize = WP_Smush::get_instance()->core()->mod->resize;

		// Initialize resize class.
		$resize->initialize();

		// If resizing not enabled, or if both max width and height is set to 0, return.
		if ( ! $resize->resize_enabled || ( 0 == $resize->max_w && 0 == $resize->max_h ) ) {
			return $meta;
		}

		$file_path = $storage->get_image_abspath( $image );
		if ( ! file_exists( $file_path ) ) {
			return $meta;
		}

		$ext = $this->get_file_ext( $file_path );

		$mime_supported = in_array( $ext, Core::$mime_types );

		// If type of upload doesn't matches the criteria return.
		$mime_supported = apply_filters( 'wp_smush_resmush_mime_supported', $mime_supported, $ext );
		if ( ! empty( $mime ) && ! $mime_supported ) {
			return $meta;
		}

		// If already resized.
		if ( ! empty( $meta['wp_smush_resize_savings'] ) ) {
			return $meta;
		}

		$sizes = $storage->get_image_sizes();

		$should_resize = true;

		/**
		 * Filter whether the NextGen image should be resized or not
		 *
		 * @since 2.3
		 *
		 * @param bool $should_resize
		 *
		 * @param object NextGen Gallery image object
		 *
		 * @param array NextGen Gallery image object
		 *
		 * @param string $context The type of upload action. Values include 'upload' or 'sideload'.
		 */
		$should_resize = apply_filters( 'wp_smush_resize_nextgen_image', $should_resize, $image, $meta );
		if ( ! $should_resize ) {
			return $meta;
		}

		$original_file_size = filesize( $file_path );

		$resized = $resize->perform_resize( $file_path, $original_file_size, $attachment_id, array(), false );

		// If resize wasn't successful.
		if ( ! $resized || $resized['filesize'] == $original_file_size ) {
			// Unlink Image, if other size path is not similar.
			$this->maybe_unlink( $file_path, $sizes, $image, $storage );
			return $meta;
		} else {
			// Else Replace the Original file with resized file.
			$replaced = @copy( $resized['file_path'], $file_path );
			$this->maybe_unlink( $resized['file_path'], $sizes, $image, $storage );
		}

		if ( $replaced ) {
			// Updated File size.
			$u_file_size = filesize( $file_path );

			$savings['bytes']       = $original_file_size > $u_file_size ? $original_file_size - $u_file_size : 0;
			$savings['size_before'] = $original_file_size;
			$savings['size_after']  = $u_file_size;

			// Store savings in metadata.
			if ( ! empty( $savings ) ) {
				$meta['wp_smush_resize_savings'] = $savings;
			}

			// Update dimensions of the image in meta.
			$meta['width']         = ! empty( $resized['width'] ) ? $resized['width'] : $meta['width'];
			$meta['full']['width'] = ! empty( $resized['width'] ) ? $resized['width'] : $meta['width'];

			$meta['height']         = ! empty( $resized['height'] ) ? $resized['height'] : $meta['height'];
			$meta['full']['height'] = ! empty( $resized['height'] ) ? $resized['height'] : $meta['height'];

			/**
			 * Called after the image has been successfully resized
			 * Can be used to update the stored stats
			 */
			do_action( 'wp_smush_image_nextgen_resized', $attachment_id, array( 'stats' => $savings ) );

			/**
			 * Called after the image has been successfully resized
			 * Can be used to update the stored stats
			 */
			do_action( 'wp_smush_image_resized', $attachment_id, $savings );
		}

		return $meta;
	}

	/**
	 * Unlinks a file if none of the thumbnails have same file path
	 *
	 * @param string $path     Full path of the file to be unlinked
	 * @param array  $sizes    All the available image sizes for the image
	 * @param object $image    Image object to fetch the full path of all the sizes
	 * @param object $storage  Gallery storage object
	 *
	 * @return bool Whether the file was unlinked or not
	 */
	private function maybe_unlink( $path, $sizes, $image, $storage ) {
		if ( empty( $path ) || ! is_object( $storage ) || ! is_object( $image ) ) {
			return false;
		}

		// Unlink directly if meta value is not specified.
		if ( empty( $sizes ) ) {
			@unlink( $path );
		}

		$unlink = true;

		// Check if the file name is similar to one of the image sizes.
		$path_parts = pathinfo( $path );

		$filename = ! empty( $path_parts['basename'] ) ? $path_parts['basename'] : $path_parts['filename'];
		foreach ( $sizes as $image_size ) {
			$file_path_size = $storage->get_image_abspath( $image, $image_size );
			if ( false === strpos( $file_path_size, $filename ) ) {
				continue;
			}
			$unlink = false;
		}

		// Unlink the file.
		if ( $unlink ) {
			@unlink( $path );
		}

		return $unlink;
	}

	/**
	 * Extend NextGen Mixin class and override the generate_image_size, to optimize dynamic thumbnails, generated by
	 * NextGen, check for auto smush.
	 *
	 * @since 3.3.0
	 */
	private function add_mixins() {
		if ( ! class_exists( '\Mixin' ) || ! WP_Smush::get_instance()->core()->mod->smush->is_auto_smush_enabled() ) {
			return;
		}

		// Extend NextGen Mixin class to smush dynamic images.
		new NextGen\Thumbs();

		if ( ! get_option( 'ngg_options' ) ) {
			return;
		}

		$storage = C_Gallery_Storage::get_instance();
		$storage->get_wrapped_instance()->add_mixin( '\\Smush\\Core\Integrations\\NextGen\\Thumbs' );
	}

}
