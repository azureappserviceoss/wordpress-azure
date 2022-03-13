<?php
/**
 * Class CLI
 *
 * @since 3.1
 * @package Smush\Core
 */

namespace Smush\Core;

use Smush\Core\Modules\Smush;
use WP_CLI;
use WP_CLI_Command;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Reduce image file sizes, improve performance and boost your SEO using the free WPMU DEV Smush API.
 */
class CLI extends WP_CLI_Command {

	/**
	 * Optimize image.
	 *
	 * ## OPTIONS
	 *
	 * [--type=<type>]
	 * : Optimize single image, batch or all images.
	 * ---
	 * default: all
	 * options:
	 *   - all
	 *   - single
	 *   - batch
	 * ---
	 *
	 * [--image=<ID>]
	 * : Attachment ID to compress.
	 * ---
	 * default: 0
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 * # Smush all images.
	 * $ wp smush compress
	 *
	 * # Smush single image with ID = 10.
	 * $ wp smush compress --type=single --image=10
	 *
	 * # Smush first 5 images.
	 * $ wp smush compress --type=batch --image=5
	 */
	public function compress( $args, $assoc_args ) {
		$type  = $assoc_args['type'];
		$image = $assoc_args['image'];

		switch ( $type ) {
			case 'single':
				/* translators: %d - image ID */
				$msg = sprintf( __( 'Smushing image ID: %d', 'wp-smushit' ), absint( $image ) );
				$this->smush( $msg, array( $image ) );
				$this->_list( array() );
				break;
			case 'batch':
				/* translators: %d - number of images */
				$msg = sprintf( __( 'Smushing first %d images', 'wp-smushit' ), absint( $image ) );
				$this->smush_all( $msg, $image );
				break;
			case 'all':
			default:
				$this->smush_all( __( 'Smushing all images', 'wp-smushit' ) );
				break;
		}
	}

	/**
	 * List unoptimized images.
	 *
	 * ## OPTIONS
	 *
	 * [<count>]
	 * : Limit number of images to get.
	 *
	 * ## EXAMPLES
	 *
	 * # Get all unoptimized images.
	 * $ wp smush list
	 *
	 * # Get the first 100 images that are not optimized.
	 * $ wp smush list 100
	 *
	 * @subcommand list
	 * @when after_wp_load
	 */
	public function _list( $args ) {
		if ( ! empty( $args ) ) {
			list( $count ) = $args;
		} else {
			$count = -1;
		}

		$response = WP_CLI::launch_self(
			'post list',
			array( '--meta_compare=NOT EXISTS' ),
			array(
				'post_type'      => 'attachment',
				'fields'         => 'ID, guid, post_mime_type',
				'meta_key'       => 'wp-smpro-smush-data',
				'format'         => 'json',
				'posts_per_page' => (int) $count,
			),
			false,
			true
		);

		$images = json_decode( $response->stdout );

		if ( empty( $images ) ) {
			WP_CLI::success( __( 'No uncompressed images found', 'wp-smushit' ) );
			return;
		}

		WP_CLI::success( __( 'Unsmushed images:', 'wp-smushit' ) );
		WP_CLI\Utils\format_items( 'table', $images, array( 'ID', 'guid', 'post_mime_type' ) );
	}

	/**
	 * Restore image.
	 *
	 * ## OPTIONS
	 *
	 * [--id=<ID>]
	 * : Attachment ID to restore.
	 * ---
	 * default: all
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 * # Restore all images that have backups.
	 * $ wp smush restore
	 *
	 * # Restore single image with ID = 10.
	 * $ wp smush restore --id=10
	 */
	public function restore( $args, $assoc_args ) {
		$id = $assoc_args['id'];

		if ( 'all' === $id ) {
			$this->restore_image();
		} else {
			$this->restore_image( absint( $id ) );
		}
	}

	/**
	 * Smush single image.
	 *
	 * @since 3.1
	 *
	 * @param string $msg     Message for progress bar status.
	 * @param array  $images  Attachment IDs.
	 */
	private function smush( $msg = '', $images = array() ) {
		$success  = false;
		$errors   = array();
		$progress = WP_CLI\Utils\make_progress_bar( $msg, count( $images ) + 1 );

		$core = WP_Smush::get_instance()->core();

		// We need to initialize the database module (maybe all other modules as well?).
		Settings::get_instance()->init();

		$unsmushed_attachments = $core->get_unsmushed_attachments();

		while ( $images ) {
			$progress->tick();

			$attachment_id = array_pop( $images );

			// Skip if already Smushed.
			$should_convert = $core->mod->webp->should_be_converted( $attachment_id );
			if ( ! in_array( (int) $attachment_id, $unsmushed_attachments, true ) && ! $should_convert ) {
				/* translators: %d - attachment ID */
				$errors[] = sprintf( __( 'Image (ID: %d) already compressed', 'wp-smushit' ), $attachment_id );
				continue;
			}

			$status = $core->mod->smush->smush_single( $attachment_id, true );

			if ( is_array( $status ) && isset( $status['error'] ) ) {
				/* translators: %1$d - attachment ID, %2$s - error. */
				$errors[] = sprintf( __( 'Error compressing image (ID: %1$d). %2$s', 'wp-smushit' ), $attachment_id, $status['error'] );
				continue;
			}

			$success = true;
		}

		$progress->tick();
		$progress->finish();

		if ( ! empty( $errors ) ) {
			foreach ( $errors as $error ) {
				WP_CLI::warning( $error );
			}
		}

		if ( $success ) {
			WP_CLI::success( __( 'Image compressed', 'wp-smushit' ) );
		}
	}

	/**
	 * Smush all uncompressed images.
	 *
	 * @since 3.1
	 *
	 * @param string $msg    Message for progress bar status.
	 * @param int    $batch  Compress only this number of images.
	 */
	private function smush_all( $msg, $batch = 0 ) {
		$attachments = WP_Smush::get_instance()->core()->get_unsmushed_attachments();

		if ( $batch > 0 ) {
			$attachments = array_slice( $attachments, 0, $batch );
		}

		$progress = WP_CLI\Utils\make_progress_bar( $msg, count( $attachments ) );

		foreach ( $attachments as $attachment_id ) {
			WP_Smush::get_instance()->core()->mod->smush->smush_single( $attachment_id, true );
			$progress->tick();
		}

		$progress->finish();
		WP_CLI::success( __( 'All images compressed', 'wp-smushit' ) );
	}

	/**
	 * Restore all images.
	 *
	 * @since 3.1
	 *
	 * @param int $id  Image ID to restore. Default: 0 - restores all images.
	 */
	private function restore_image( $id = 0 ) {
		$core = WP_Smush::get_instance()->core();

		$attachments = ! empty( $core->smushed_attachments ) ? $core->smushed_attachments : $core->get_smushed_attachments();

		if ( empty( $attachments ) ) {
			WP_CLI::success( __( 'No images available to restore', 'wp-smushit' ) );
			return;
		}

		if ( 0 !== $id ) {
			if ( ! in_array( (string) $id, $attachments, true ) ) {
				WP_CLI::warning( __( 'Image with defined ID not found', 'wp-smushit' ) );
				return;
			}

			$attachments = array( $id );
		}

		$progress = WP_CLI\Utils\make_progress_bar( __( 'Restoring images', 'wp-smushit' ), count( $attachments ) );

		$warning = false;
		foreach ( $attachments as $attachment_id ) {
			if ( ! $this->can_restore( $attachment_id ) ) {
				$warning = true;
				WP_CLI::warning( __( "Image {$attachment_id} cannot be restored", 'wp-smushit' ) );
				$progress->tick();
				continue;
			}

			$core->mod->backup->restore_image( $attachment_id, false );
			$progress->tick();
		}

		$progress->finish();

		if ( $warning ) {
			WP_CLI::error( __( 'There were issues restoring some images', 'wp-smushit' ) );
		} else {
			WP_CLI::success( __( 'All images restored', 'wp-smushit' ) );
		}

	}

	/**
	 * Verify, that image can be restored.
	 *
	 * TODO: this copies the Smush code, so it needs to be refactored.
	 *
	 * @since 3.1.0
	 *
	 * @param int $image_id  Attachment ID.
	 *
	 * @return bool
	 */
	private function can_restore( $image_id ) {
		// Get the image path for all sizes.
		$file = get_attached_file( $image_id );

		// Get stored backup path, if any.
		$backup_sizes = get_post_meta( $image_id, '_wp_attachment_backup_sizes', true );

		// Check if we've a backup path.
		if ( ! empty( $backup_sizes ) && ( ! empty( $backup_sizes['smush-full'] ) || ! empty( $backup_sizes['smush_png_path'] ) ) ) {
			// Check for PNG backup.
			$backup = ! empty( $backup_sizes['smush_png_path'] ) ? $backup_sizes['smush_png_path'] : '';

			// Check for original full size image backup.
			$backup = empty( $backup ) && ! empty( $backup_sizes['smush-full'] ) ? $backup_sizes['smush-full'] : $backup;
			$backup = ! empty( $backup['file'] ) ? $backup['file'] : '';
		}

		// If we still don't have a backup path, use traditional method to get it.
		if ( empty( $backup ) ) {
			// Check backup for Full size.
			$backup = WP_Smush::get_instance()->core()->mod->backup->get_image_backup_path( $file );
		} else {
			// Get the full path for file backup.
			$backup = str_replace( wp_basename( $file ), wp_basename( $backup ), $file );
		}

		$file_exists = apply_filters( 'smush_backup_exists', file_exists( $backup ), $image_id, $backup );

		if ( $file_exists ) {
			return true;
		}

		// Additional Backup Check for JPEGs converted from PNG.
		$pngjpg_savings = get_post_meta( $image_id, 'wp-smush-pngjpg_savings', true );
		if ( ! empty( $pngjpg_savings ) ) {

			// Get the original File path and check if it exists.
			$backup = get_post_meta( $image_id, 'wp-smush-original_file', true );
			$backup = Helper::original_file( $backup );

			if ( ! empty( $backup ) && is_file( $backup ) ) {
				return true;
			}
		}

		return false;
	}

}
