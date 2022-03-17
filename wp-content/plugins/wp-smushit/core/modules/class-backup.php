<?php
/**
 * Smush backup class
 *
 * @package Smush\Core\Modules
 */

namespace Smush\Core\Modules;

use Smush\Core\Core;
use Smush\Core\Helper;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Backup
 */
class Backup extends Abstract_Module {

	/**
	 * Key for storing file path for image backup
	 *
	 * @var string
	 */
	private $backup_key = 'smush-full';

	/**
	 * Backup constructor.
	 */
	public function init() {
		// Handle Restore operation.
		add_action( 'wp_ajax_smush_restore_image', array( $this, 'restore_image' ) );

		// Handle bulk restore from modal.
		add_action( 'wp_ajax_get_image_count', array( $this, 'get_image_count' ) );
		add_action( 'wp_ajax_restore_step', array( $this, 'restore_step' ) );
	}

	/**
	 * Creates a backup of file for the given attachment path.
	 *
	 * Checks if there is an existing backup, else create one.
	 *
	 * @param string $file_path      File path.
	 * @param int    $attachment_id  Attachment ID.
	 */
	public function create_backup( $file_path, $attachment_id ) {
		if ( empty( $file_path ) || empty( $attachment_id ) ) {
			return;
		}

		if ( ! $this->settings->get( 'backup' ) ) {
			return;
		}

		// Add WordPress 5.3 support for -scaled images size, and those can always be used to restore.
		if ( false !== strpos( $file_path, '-scaled.' ) && function_exists( 'wp_get_original_image_path' ) ) {
			// Scaled images already have a backup. Use that and don't create a new one.
			$file_path = wp_get_original_image_path( $attachment_id );

			// We do not need an additional backup file if we're not compressing originals.
			if ( ! $this->settings->get( 'original' ) ) {
				$this->add_to_image_backup_sizes( $attachment_id, $file_path );
				return;
			}
		}

		$backup_path = $this->get_image_backup_path( $file_path );

		if ( empty( $backup_path ) ) {
			return;
		}

		if ( WP_Smush::get_instance()->core()->mod->png2jpg->is_converted( $attachment_id ) ) {
			return; // No need to create a backup, we already have one if enabled.
		}

		// Check for backup from other plugins, like nextgen, if it doesn't exist - create our own.
		if ( file_exists( $backup_path ) ) {
			return;
		}

		// Store the backup path in image backup sizes.
		$copied = copy( $file_path, $backup_path );
		if ( $copied ) {
			$this->add_to_image_backup_sizes( $attachment_id, $backup_path );
		}
	}

	/**
	 * Store new backup path for the image.
	 *
	 * @param int    $attachment_id  Attachment ID.
	 * @param string $backup_path    Backup path.
	 * @param string $backup_key     Backup key.
	 */
	public function add_to_image_backup_sizes( $attachment_id, $backup_path, $backup_key = '' ) {
		if ( empty( $attachment_id ) || empty( $backup_path ) ) {
			return;
		}

		// Get the Existing backup sizes.
		$backup_sizes = get_post_meta( $attachment_id, '_wp_attachment_backup_sizes', true );
		if ( empty( $backup_sizes ) ) {
			$backup_sizes = array();
		}

		// Prevent phar deserialization vulnerability.
		if ( false !== stripos( $backup_path, 'phar://' ) ) {
			return;
		}

		// Return if backup file doesn't exist.
		if ( ! file_exists( $backup_path ) ) {
			return;
		}

		list( $width, $height ) = getimagesize( $backup_path );

		// Store our backup path.
		$backup_key                  = empty( $backup_key ) ? $this->backup_key : $backup_key;
		$backup_sizes[ $backup_key ] = array(
			'file'   => wp_basename( $backup_path ),
			'width'  => $width,
			'height' => $height,
		);

		wp_cache_delete( 'images_with_backups', 'wp-smush' );
		update_post_meta( $attachment_id, '_wp_attachment_backup_sizes', $backup_sizes );
	}

	/**
	 * Restore the image and its sizes from backup
	 *
	 * @param string $attachment_id  Attachment ID.
	 * @param bool   $resp           Send JSON response or not.
	 *
	 * @return bool
	 */
	public function restore_image( $attachment_id = '', $resp = true ) {
		// If no attachment id is provided, check $_POST variable for attachment_id.
		if ( empty( $attachment_id ) ) {
			// Check Empty fields.
			if ( empty( $_POST['attachment_id'] ) || empty( $_POST['_nonce'] ) ) {
				wp_send_json_error(
					array(
						'error_msg' => esc_html__( 'Error in processing restore action, fields empty.', 'wp-smushit' ),
					)
				);
			}

			$nonce_value   = filter_input( INPUT_POST, '_nonce', FILTER_SANITIZE_STRING );
			$attachment_id = filter_input( INPUT_POST, 'attachment_id', FILTER_SANITIZE_NUMBER_INT );

			if ( ! wp_verify_nonce( $nonce_value, "wp-smush-restore-{$attachment_id}" ) ) {
				wp_send_json_error(
					array(
						'error_msg' => esc_html__( 'Image not restored, nonce verification failed.', 'wp-smushit' ),
					)
				);
			}
		}

		$attachment_id = (int) $attachment_id;

		// Store the restore success/failure for full size image.
		$restored    = false;
		$restore_png = false;

		// Set an option to avoid the smush-restore-smush loop.
		update_option( "wp-smush-restore-{$attachment_id}", true );

		/**
		 * Delete WebP.
		 *
		 * Run WebP::delete_images always even when the module is deactivated.
		 *
		 * @since 3.8.0
		 */
		WP_Smush::get_instance()->core()->mod->webp->delete_images( $attachment_id );

		// The scaled images' paths are re-saved when getting the original image.
		// This avoids storing the S3's url in there.
		add_filter( 'as3cf_get_attached_file', array( $this, 'skip_as3cf_url_get_attached_file' ), 10, 2 );

		// Restore Full size -> get other image sizes -> restore other images.
		// Get the Original Path.
		$file_path = get_attached_file( $attachment_id );

		// Add WordPress 5.3 support for -scaled images size.
		if ( false !== strpos( $file_path, '-scaled.' ) && function_exists( 'wp_get_original_image_path' ) ) {
			$file_path = wp_get_original_image_path( $attachment_id, true );
		}

		// And go back to normal after retrieving the original path.
		remove_filter( 'as3cf_get_attached_file', array( $this, 'skip_as3cf_url_get_attached_file' ) );

		$backup_sizes = get_post_meta( $attachment_id, '_wp_attachment_backup_sizes', true );

		if ( ! empty( $backup_sizes ) ) {
			// 1. Check if the image was converted from PNG->JPG, Get the corresponding backup path
			if ( isset( $backup_sizes['smush_png_path'] ) ) {
				$backup_path = $backup_sizes['smush_png_path'];

				// If we don't have the backup path in backup sizes, check for legacy original file path.
				if ( empty( $backup_path ) ) {
					// Check if it's a JPG converted from PNG, and restore the JPG to PNG.
					$original_file = get_post_meta( $attachment_id, 'wp-smush-original_file', true );
					$backup_path   = Helper::original_file( $original_file );
				}

				// If we have a backup path for PNG file, use restore_png().
				if ( ! empty( $backup_path ) ) {
					$restore_png = true;
				}
			}

			// 2. If we don't have a backup path from PNG->JPG, check for normal smush backup path.
			if ( empty( $backup_path ) ) {
				if ( ! empty( $backup_sizes[ $this->backup_key ] ) ) {
					$backup_path = $backup_sizes[ $this->backup_key ];
				} else {
					// If we don't have a backup path, check for legacy backup naming convention.
					$backup_path = $this->get_image_backup_path( $file_path );
				}
			}
			$backup_path = is_array( $backup_path ) && ! empty( $backup_path['file'] ) ? $backup_path['file'] : $backup_path;

			$is_bak_file = false === strpos( $backup_path, '.bak' );

			if ( $is_bak_file ) {
				$backup_full_path = $backup_path;
			} else {
				$backup_full_path = str_replace( wp_basename( $file_path ), wp_basename( $backup_path ), $file_path );
			}
		}

		// Finally, if we have the backup path, perform the restore operation.
		if ( ! empty( $backup_full_path ) ) {
			/**
			 * Allows S3 to hook, check and download the file
			 */
			do_action( 'smush_file_exists', $backup_full_path, $attachment_id, array() );

			if ( $restore_png ) {
				// Restore PNG full size and all other image sizes.
				$restored = $this->restore_png( $attachment_id, $backup_full_path, $file_path );

				// JPG file is already deleted, Update backup sizes.
				if ( $restored ) {
					$this->remove_from_backup_sizes( $attachment_id, 'smush_png_path', $backup_sizes );
				}
			} else {
				// If file exists, corresponding to our backup path - restore.
				if ( ! isset( $is_bak_file ) || ! $is_bak_file ) {
					$restored = copy( $backup_full_path, $file_path );
				} else {
					$restored = true;
				}

				// Remove the backup, if we were able to restore the image.
				if ( $restored ) {
					// Update backup sizes.
					$this->remove_from_backup_sizes( $attachment_id, '', $backup_sizes );

					// Delete the backup.
					if ( file_exists( $backup_full_path ) ) {
						unlink( $backup_full_path );
						do_action( 'smush_s3_backup_remove', $attachment_id );
					}
				}
			}
		} elseif ( file_exists( $file_path . '_backup' ) ) {
			// Try to restore from other backups, if any.
			$restored = copy( $file_path . '_backup', $file_path );
		}

		// All this is handled in self::restore_png().
		if ( ! $restore_png ) {
			// Prevent the image from being offloaded during 'wp_generate_attachment_metadata'.
			add_filter( 'as3cf_wait_for_generate_attachment_metadata', '__return_true' );
			// Generate all other image size, and update attachment metadata.
			$metadata = wp_generate_attachment_metadata( $attachment_id, $file_path );
			// And go back to normal.
			remove_filter( 'as3cf_wait_for_generate_attachment_metadata', '__return_true' );

			// Update metadata to db if it was successfully generated.
			if ( ! empty( $metadata ) && ! is_wp_error( $metadata ) ) {
				wp_update_attachment_metadata( $attachment_id, $metadata );
			}
		}

		// If any of the image is restored, we count it as success.
		if ( $restored ) {
			// Remove the Meta, And send json success.
			delete_post_meta( $attachment_id, Smush::$smushed_meta_key );

			// Remove PNG to JPG conversion savings.
			delete_post_meta( $attachment_id, 'wp-smush-pngjpg_savings' );

			// Remove Original File.
			delete_post_meta( $attachment_id, 'wp-smush-original_file' );

			// Delete resize savings.
			delete_post_meta( $attachment_id, 'wp-smush-resize_savings' );

			// Clear backups cache.
			wp_cache_delete( 'images_with_backups', 'wp-smush' );

			// Get the Button html without wrapper.
			$button_html = WP_Smush::get_instance()->library()->generate_markup( $attachment_id );

			// Remove the transient.
			delete_option( "wp-smush-restore-$attachment_id" );

			Core::remove_from_smushed_list( $attachment_id );

			if ( ! $resp ) {
				return true;
			}

			$size = file_exists( $file_path ) ? filesize( $file_path ) : 0;
			if ( $size > 0 ) {
				$update_size = size_format( $size ); // Used in js to update image stat.
			}

			wp_send_json_success(
				array(
					'stats'    => $button_html,
					'new_size' => isset( $update_size ) ? $update_size : 0,
				)
			);
		}

		delete_option( "wp-smush-restore-$attachment_id" );

		if ( $resp ) {
			wp_send_json_error( array( 'error_msg' => esc_html__( 'Unable to restore image', 'wp-smushit' ) ) );
		}

		return false;
	}

	/**
	 * Returns the original file instead of S3 URL.
	 *
	 * @noinspection PhpUnusedParameterInspection
	 *
	 * @since 3.8.3
	 *
	 * @param string $url   S3 URL.
	 * @param string $file  Local file.
	 *
	 * @return string
	 */
	public function skip_as3cf_url_get_attached_file( $url, $file ) {
		return $file;
	}

	/**
	 * Restore PNG.
	 *
	 * @param int    $attachment_id  Attachment ID.
	 * @param string $original_file  Original file.
	 * @param string $file_path      File path.
	 *
	 * @return bool
	 */
	private function restore_png( $attachment_id, $original_file, $file_path ) {
		if ( empty( $attachment_id ) ) {
			return false;
		}

		$meta = '';

		$mod = WP_Smush::get_instance()->core()->mod;

		// Else get the Attachment details.
		/**
		 * For Full Size
		 * 1. Get the original file path
		 * 2. Update the attachment metadata and all other meta details
		 * 3. Delete the JPEG
		 * 4. And we're done
		 * 5. Add an action after updating the URLs, that'd allow the users to perform an additional search, replace action
		 */
		if ( empty( $original_file ) ) {
			$original_file = get_post_meta( $attachment_id, 'wp-smush-original_file', true );
		}

		// Get the actual full path of the original file.
		$original_file_path = str_replace( wp_basename( $file_path ), wp_basename( $original_file ), $file_path );

		if ( file_exists( $original_file_path ) ) {
			// Update the path details in meta and attached file, replace the image.
			$meta = $mod->png2jpg->update_image_path( $attachment_id, $file_path, $original_file_path, $meta, 'full', 'restore' );

			// Unlink JPG.
			if ( ! empty( $meta['file'] ) && wp_basename( $original_file ) === wp_basename( $meta['file'] ) ) {
				// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
				unlink( $file_path );
			}

			$jpg_meta = wp_get_attachment_metadata( $attachment_id );
			foreach ( $jpg_meta['sizes'] as $size_data ) {
				$size_path = str_replace( wp_basename( $original_file_path ), wp_basename( $size_data['file'] ), $original_file_path );
				unlink( $size_path );
			}

			$meta = wp_generate_attachment_metadata( $attachment_id, $original_file_path );

			// Perform an action after the image URL is updated in post content.
			do_action( 'wp_smush_image_url_updated', $attachment_id, $file_path, $original_file );
		}

		if ( ! empty( $meta ) ) {
			// Remove Smushing, while attachment data is updated for the image.
			remove_filter( 'wp_generate_attachment_metadata', array( $mod->smush, 'smush_image' ), 15 );
			wp_update_attachment_metadata( $attachment_id, $meta );
			return true;
		}

		return false;
	}

	/**
	 * Remove a specific backup key from the backup size array.
	 *
	 * @param int    $attachment_id  Attachment ID.
	 * @param string $backup_key     Backup key.
	 * @param array  $backup_sizes   Backup sizes.
	 */
	private function remove_from_backup_sizes( $attachment_id, $backup_key, $backup_sizes ) {
		// Get backup sizes.
		$backup_sizes = empty( $backup_sizes ) ? get_post_meta( $attachment_id, '_wp_attachment_backup_sizes', true ) : $backup_sizes;
		$backup_key   = empty( $backup_key ) ? $this->backup_key : $backup_key;

		// If we don't have any backup sizes list or if the particular key is not set, return.
		if ( empty( $backup_sizes ) || ! isset( $backup_sizes[ $backup_key ] ) ) {
			return;
		}

		unset( $backup_sizes[ $backup_key ] );

		if ( empty( $backup_sizes ) ) {
			delete_post_meta( $attachment_id, '_wp_attachment_backup_sizes' );
		} else {
			update_post_meta( $attachment_id, '_wp_attachment_backup_sizes', $backup_sizes );
		}
	}

	/**
	 * Get the attachments that can be restored.
	 *
	 * @since 3.6.0  Changed from private to public.
	 *
	 * @return array  Array of attachments IDs.
	 */
	public function get_attachments_with_backups() {
		$images = wp_cache_get( 'images_with_backups', 'wp-smush' );

		if ( ! $images ) {
			global $wpdb;
			$images = $wpdb->get_col(
				"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_wp_attachment_backup_sizes' AND (`meta_value` LIKE '%smush-full%' OR `meta_value` LIKE '%smush_png_path%')"
			); // Db call ok.

			if ( $images ) {
				wp_cache_set( 'images_with_backups', $images, 'wp-smush' );
			}
		}

		return $images;
	}

	/**
	 * Get the number of attachments that can be restored.
	 *
	 * @since 3.2.2
	 */
	public function get_image_count() {
		check_ajax_referer( 'smush_bulk_restore' );
		wp_send_json_success(
			array(
				'items' => $this->get_attachments_with_backups(),
			)
		);
	}

	/**
	 * Bulk restore images from the modal.
	 *
	 * @since 3.2.2
	 */
	public function restore_step() {
		check_ajax_referer( 'smush_bulk_restore' );
		$id = filter_input( INPUT_POST, 'item', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE );

		$status = $id && $this->restore_image( $id, false );

		$original_meta = wp_get_attachment_metadata( $id, true );

		// Try to get the file name from path.
		$file_name = explode( '/', $original_meta['file'] );
		$file_name = is_array( $file_name ) ? array_pop( $file_name ) : $original_meta['file'];

		wp_send_json_success(
			array(
				'success' => $status,
				'src'     => $file_name ? $file_name : __( 'Error getting file name', 'wp-smushit' ),
				'thumb'   => wp_get_attachment_image( $id ),
				'link'    => Helper::get_image_media_link( $id, $file_name, true ),
			)
		);
	}

	/**
	 * Returns the backup path for attachment
	 *
	 * @param string $attachment_path  Attachment path.
	 *
	 * @return string
	 */
	public function get_image_backup_path( $attachment_path ) {
		if ( empty( $attachment_path ) ) {
			return '';
		}

		$path = pathinfo( $attachment_path );

		if ( empty( $path['extension'] ) ) {
			return '';
		}

		return trailingslashit( $path['dirname'] ) . $path['filename'] . '.bak.' . $path['extension'];
	}

	/**
	 * Clear up all the backup files for the image.
	 *
	 * @param int $attachment_id  Attachment ID.
	 */
	public function delete_backup_files( $attachment_id ) {
		$smush_meta = get_post_meta( $attachment_id, Smush::$smushed_meta_key, true );
		if ( empty( $smush_meta ) ) {
			return;
		}

		$file_path   = get_attached_file( $attachment_id );
		$backup_path = $this->get_image_backup_path( $file_path );
		if ( file_exists( $backup_path ) ) {
			unlink( $backup_path );
		}

		wp_cache_delete( 'images_with_backups', 'wp-smush' );

		// Check meta for rest of the sizes.
		$meta = wp_get_attachment_metadata( $attachment_id );
		if ( empty( $meta ) || empty( $meta['sizes'] ) ) {
			return;
		}

		foreach ( $meta['sizes'] as $size ) {
			if ( empty( $size['file'] ) ) {
				continue;
			}

			// Image path and backup path.
			$image_size_path   = path_join( dirname( $file_path ), $size['file'] );
			$image_backup_path = $this->get_image_backup_path( $image_size_path );
			if ( file_exists( $image_backup_path ) ) {
				unlink( $image_backup_path );
			}
		}
	}

}
