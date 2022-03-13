<?php
/**
 * PNG to JPG conversion: Png2jpg class
 *
 * @package Smush\Core\Modules
 *
 * @version 2.4
 *
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2016, Incsub (http://incsub.com)
 */

namespace Smush\Core\Modules;

use Exception;
use Imagick;
use ImagickPixel;
use Smush\Core\Helper;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Png2jpg
 */
class Png2jpg extends Abstract_Module {

	/**
	 * Does PNG contain transparency.
	 *
	 * @var bool
	 */
	private $is_transparent = false;

	/**
	 * Init method.
	 *
	 * @since 3.0
	 */
	public function init() {}

	/**
	 * Check if Imagick is available or not
	 *
	 * @return bool True/False Whether Imagick is available or not
	 */
	private function supports_imagick() {
		if ( ! class_exists( '\Imagick' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if GD is loaded
	 *
	 * @return bool True/False Whether GD is available or not
	 */
	private function supports_gd() {
		if ( ! function_exists( 'gd_info' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if the Given PNG file is transparent or not
	 *
	 * @param string $id    Attachment ID.
	 * @param string $file  File path for the attachment.
	 *
	 * @return bool|int
	 */
	private function is_transparent( $id = '', $file = '' ) {
		// No attachment id/ file path, return.
		if ( empty( $id ) && empty( $file ) ) {
			return false;
		}

		if ( empty( $file ) ) {
			// This downloads the file from S3 when S3 is enabled.
			$file = Helper::get_attached_file( $id );
		}

		// Check if File exists.
		if ( empty( $file ) || ! file_exists( $file ) ) {
			return false;
		}

		$transparent = '';

		// Try to get transparency using Imagick.
		if ( $this->supports_imagick() ) {
			try {
				$im = new Imagick( $file );

				return $im->getImageAlphaChannel();
			} catch ( Exception $e ) {
				error_log( 'Imagick: Error in checking PNG transparency ' . $e->getMessage() );
			}
		} else {
			// Simple check.
			// Src: http://camendesign.com/code/uth1_is-png-32bit.
			if ( ord( file_get_contents( $file, false, null, 25, 1 ) ) & 4 ) {
				return true;
			}
			// Src: http://www.jonefox.com/blog/2011/04/15/how-to-detect-transparency-in-png-images/.
			$contents = file_get_contents( $file );
			if ( stripos( $contents, 'PLTE' ) !== false && stripos( $contents, 'tRNS' ) !== false ) {
				return true;
			}

			// If both the conditions failed, that means not transparent.
			return false;

		}

		// If Imagick is installed, and the code exited due to some error.
		// Src: StackOverflow.
		if ( empty( $transparent ) && $this->supports_gd() ) {
			// Check for transparency using GD.
			$i       = imagecreatefrompng( $file );
			$palette = ( imagecolortransparent( $i ) < 0 );
			if ( $palette ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check whether to convert the PNG to JPG or not
	 *
	 * @param string $id    Attachment ID.
	 * @param string $file  File path for the attachment.
	 *
	 * @return bool Whether to convert the PNG or not
	 */
	private function should_convert( $id, $file = '' ) {
		// Get the Transparency conversion settings.
		$convert_png = $this->settings->get( 'png_to_jpg' );

		if ( ! $convert_png ) {
			return false;
		}

		$this->is_transparent = $this->is_transparent( $id, $file );

		// The $file is provided when it'll be used later on (when smushing),
		// and it's empty when only checking whether an image should be converted.
		if ( empty( $file ) ) {
			// The local file is downloaded by $this->is_transparent() and
			// isn't used anymore when we're only checking whether to convert.
			Helper::remove_main_file_from_server_when_in_s3( $id );
		}

		// Only convert non-transparent images.
		return ! $this->is_transparent;
	}

	/**
	 * Check if given attachment id can be converted to JPEG or not
	 *
	 * @param string $id    Atachment ID.
	 * @param string $size  Image size.
	 * @param string $mime  Mime type.
	 * @param string $file  File.
	 *
	 * @return bool True/False Can be converted or not
	 */
	public function can_be_converted( $id = '', $size = 'full', $mime = '', $file = '' ) {
		if ( empty( $id ) ) {
			return false;
		}

		// False if not a PNG.
		$mime = empty( $mime ) ? get_post_mime_type( $id ) : $mime;
		if ( 'image/png' !== $mime && 'image/x-png' !== $mime ) {
			return false;
		}

		// Return if Imagick and GD is not available.
		if ( ! $this->supports_imagick() && ! $this->supports_gd() ) {
			return false;
		}

		// If already tried the conversion.
		if ( get_post_meta( $id, 'wp-smush-pngjpg_savings', true ) ) {
			return false;
		}

		// Check if registered size is supposed to be converted or not.
		if ( 'full' !== $size && WP_Smush::get_instance()->core()->mod->smush->skip_image_size( $size ) ) {
			return false;
		}

		/** Whether to convert to jpg or not */
		$should_convert = $this->should_convert( $id, $file );

		/**
		 * Filter whether to convert the PNG to JPG or not
		 *
		 * @since 2.4
		 *
		 * @param bool $should_convert Current choice for image conversion
		 *
		 * @param int $id Attachment id
		 *
		 * @param string $file File path for the image
		 *
		 * @param string $size Image size being converted
		 */
		$should_convert = apply_filters( 'wp_smush_convert_to_jpg', $should_convert, $id, $file, $size );

		return $should_convert;
	}

	/**
	 * Update the image URL, MIME Type, Attached File, file path in Meta, URL in post content
	 *
	 * @param string $id      Attachment ID.
	 * @param string $o_file  Original File Path that has to be replaced.
	 * @param string $n_file  New File Path which replaces the old file.
	 * @param string $meta    Attachment Meta.
	 * @param string $size_k  Image Size.
	 * @param string $o_type  Operation Type "conversion", "restore".
	 *
	 * @return mixed  Attachment Meta with updated file path.
	 */
	public function update_image_path( $id, $o_file, $n_file, $meta, $size_k, $o_type = 'conversion' ) {
		// Upload Directory.
		$upload_dir = wp_upload_dir();

		// Upload Path.
		$upload_path = trailingslashit( $upload_dir['basedir'] );

		$dir_name = pathinfo( $o_file, PATHINFO_DIRNAME );

		// Full Path to new file.
		$n_file_path = path_join( $dir_name, $n_file );

		// Current URL for image.
		$o_url = wp_get_attachment_url( $id );

		// Update URL for image size.
		if ( 'full' !== $size_k ) {
			$base_url = dirname( $o_url );
			$o_url    = $base_url . '/' . basename( $o_file );
		}

		// Update File path, Attached File, GUID.
		$meta = empty( $meta ) ? wp_get_attachment_metadata( $id ) : $meta;
		$mime = Helper::get_mime_type( $n_file_path );

		/**
		 * If there's no fileinfo extension installed, the mime type will be returned as false.
		 * As a fallback, we set it manually.
		 *
		 * @since 3.8.3
		 */
		if ( false === $mime ) {
			$mime = 'conversion' === $o_type ? 'image/jpeg' : 'image/png';
		}

		// Update File Path, Attached file, Mime Type for Image.
		if ( 'full' === $size_k ) {
			if ( ! empty( $meta ) ) {
				$new_file     = str_replace( $upload_path, '', $n_file_path );
				$meta['file'] = $new_file;
			}
			// Update Attached File.
			update_attached_file( $id, $meta['file'] );

			// Update Mime type.
			wp_update_post(
				array(
					'ID'             => $id,
					'post_mime_type' => $mime,
				)
			);
		} else {
			$meta['sizes'][ $size_k ]['file']      = basename( $n_file );
			$meta['sizes'][ $size_k ]['mime-type'] = $mime;
		}

		// To be called after the attached file key is updated for the image.
		$this->update_image_url( $id, $size_k, $n_file, $o_url );

		// Delete the Original files if backup not enabled.
		if ( 'conversion' === $o_type && ! $this->settings->get( 'backup' ) ) {
			@unlink( $o_file );
		}

		return $meta;
	}

	/**
	 * Replace the file if there are savings, and return savings
	 *
	 * @param string $file    Original File Path.
	 * @param array  $result  Array structure.
	 * @param string $n_file  Updated File path.
	 *
	 * @return array
	 */
	private function replace_file( $file = '', $result = array(), $n_file = '' ) {
		if ( empty( $file ) || empty( $n_file ) ) {
			return $result;
		}

		// Get the file size of original image.
		$o_file_size = filesize( $file );

		$n_file = path_join( dirname( $file ), $n_file );

		$n_file_size = filesize( $n_file );

		// If there aren't any savings return.
		if ( $n_file_size >= $o_file_size ) {
			// Delete the JPG image and return.
			@unlink( $n_file );

			return $result;
		}

		// Get the savings.
		$savings = $o_file_size - $n_file_size;

		// Store Stats.
		$savings = array(
			'bytes'       => $savings,
			'size_before' => $o_file_size,
			'size_after'  => $n_file_size,
		);

		$result['savings'] = $savings;

		return $result;
	}

	/**
	 * Perform the conversion process, using WordPress Image Editor API
	 *
	 * @param string $id    Attachment ID.
	 * @param string $file  Attachment File path.
	 * @param string $meta  Attachment meta.
	 * @param string $size  Image size, default empty for full image.
	 *
	 * @return array $result array(
	 *  'meta'  => array Update Attachment metadata
	 *  'savings'   => Reduction of Image size in bytes
	 * )
	 */
	private function convert_to_jpg( $id = '', $file = '', $meta = '', $size = 'full' ) {
		$result = array(
			'meta'    => $meta,
			'savings' => '',
		);

		// Flag: Whether the image was converted or not.
		if ( 'full' === $size ) {
			$result['converted'] = false;
		}

		// If any of the values is not set.
		if ( empty( $id ) || empty( $file ) || empty( $meta ) ) {
			return $result;
		}

		$editor = wp_get_image_editor( $file );

		if ( is_wp_error( $editor ) ) {
			// Use custom method maybe.
			return $result;
		}

		$n_file = pathinfo( $file );

		if ( ! empty( $n_file['filename'] ) && $n_file['dirname'] ) {
			// Get a unique File name.
			$n_file['filename'] = wp_unique_filename( $n_file['dirname'], $n_file['filename'] . '.jpg' );
			$n_file             = path_join( $n_file['dirname'], $n_file['filename'] );
		} else {
			return $result;
		}

		// Save PNG as JPG.
		$new_image_info = $editor->save( $n_file, 'image/jpeg' );

		// If image editor was unable to save the image, return.
		if ( is_wp_error( $new_image_info ) ) {
			return $result;
		}

		$n_file = ! empty( $new_image_info ) ? $new_image_info['file'] : '';

		// Replace file, and get savings.
		$result = $this->replace_file( $file, $result, $n_file );

		if ( ! empty( $result['savings'] ) ) {
			if ( 'full' === $size ) {
				$result['converted'] = true;
			}
			// Update the File Details. and get updated meta.
			$result['meta'] = $this->update_image_path( $id, $file, $n_file, $meta, $size );

			/**
			 *  Perform a action after the image URL is updated in post content
			 */
			do_action( 'wp_smush_image_url_changed', $id, $file, $n_file, $size );
		}

		return $result;
	}

	/**
	 * Convert a PNG to JPG, Lossless Conversion, if we have any savings
	 *
	 * @param string       $id    Image ID.
	 * @param string|array $meta  Image meta.
	 *
	 * @uses Backup::add_to_image_backup_sizes()
	 *
	 * @return mixed|string
	 *
	 * TODO: Save cumulative savings
	 */
	public function png_to_jpg( $id = '', $meta = '' ) {
		// If we don't have meta or ID, or if not a premium user.
		if ( empty( $id ) || empty( $meta ) || ! WP_Smush::is_pro() ) {
			return $meta;
		}

		$file = Helper::get_attached_file( $id );

		// Whether to convert to jpg or not.
		$should_convert = $this->can_be_converted( $id, 'full', '', $file );

		if ( ! $should_convert ) {
			return $meta;
		}

		$result['meta'] = $meta;

		if ( ! $this->is_transparent ) {
			// Perform the conversion, and update path.
			$result = $this->convert_to_jpg( $id, $file, $result['meta'] );
		}

		$savings['full'] = ! empty( $result['savings'] ) ? $result['savings'] : '';

		// If original image was converted and other sizes are there for the image, Convert all other image sizes.
		if ( $result['converted'] ) {
			if ( ! empty( $meta['sizes'] ) ) {
				foreach ( $meta['sizes'] as $size_k => $data ) {
					$s_file = path_join( dirname( $file ), $data['file'] );

					/**
					 * Since these sizes are derived from the main png file,
					 * We can safely perform the conversion.
					 */
					$result = $this->convert_to_jpg( $id, $s_file, $result['meta'], $size_k );

					if ( ! empty( $result['savings'] ) ) {
						$savings[ $size_k ] = $result['savings'];
					}
				}
			}

			// Save the original File URL.
			$o_file = ! empty( $file ) ? $file : get_post_meta( $id, '_wp_attached_file', true );

			WP_Smush::get_instance()->core()->mod->backup->add_to_image_backup_sizes( $id, $o_file, 'smush_png_path' );

			// Remove webp images created from the png version, if any.
			WP_Smush::get_instance()->core()->mod->webp->delete_images( $id, true, $o_file );

			/**
			 * Do action, if the PNG to JPG conversion was successful
			 */
			do_action( 'wp_smush_png_jpg_converted', $id, $meta, $savings );
		}

		// Update the Final Stats.
		update_post_meta( $id, 'wp-smush-pngjpg_savings', $savings );

		return $result['meta'];
	}

	/**
	 * Get JPG quality from WordPress Image Editor
	 *
	 * @param string $file  File.
	 *
	 * @return int Quality for JPEG images
	 */
	private function get_quality( $file ) {
		if ( empty( $file ) ) {
			return 82;
		}
		$editor = wp_get_image_editor( $file );

		if ( ! is_wp_error( $editor ) ) {
			$quality = $editor->get_quality();
		}

		// Choose the default quality if we didn't get it.
		if ( ! isset( $quality ) || $quality < 1 || $quality > 100 ) {
			// The default quality.
			$quality = 82;
		}

		return $quality;
	}

	/**
	 * Check whether the given attachment was converted from PNG to JPG.
	 *
	 * @param int $id  Attachment ID.
	 *
	 * @return bool  If the image was converted from PNG or not.
	 */
	public function is_converted( $id ) {
		if ( empty( $id ) ) {
			return false;
		}

		// Get the original file path and check if it exists.
		$original_file = get_post_meta( $id, 'wp-smush-original_file', true );

		// If original file path is not stored, then it wasn't converted or was restored to original.
		if ( empty( $original_file ) ) {
			return false;
		}

		$uploads = wp_get_upload_dir();
		return file_exists( path_join( $uploads['basedir'], $original_file ) );
	}

	/**
	 * Update Image URL in post content
	 *
	 * @param string $id      Attachment ID.
	 * @param string $size_k  Image Size.
	 * @param string $n_file  New File Path which replaces the old file.
	 * @param string $o_url   URL to search for.
	 */
	private function update_image_url( $id, $size_k, $n_file, $o_url ) {
		if ( 'full' === $size_k ) {
			// Get the updated image URL.
			$n_url = wp_get_attachment_url( $id );
		} else {
			$n_url = trailingslashit( dirname( $o_url ) ) . basename( $n_file );
		}

		// Update In Post Content, Loop Over a set of posts to avoid the query failure for large sites.
		global $wpdb;
		// Get existing Images with current URL.
		$query = $wpdb->prepare(
			"SELECT ID, post_content FROM $wpdb->posts WHERE post_content LIKE '%%%s%%'",
			$o_url
		);

		$rows = $wpdb->get_results( $query, ARRAY_A );

		if ( empty( $rows ) || ! is_array( $rows ) ) {
			return;
		}

		// Iterate over rows to update post content.
		foreach ( $rows as $row ) {
			// replace old URLs with new URLs.
			$post_content = $row['post_content'];
			$post_content = str_replace( $o_url, $n_url, $post_content );
			// Update Post content.
			$wpdb->update(
				$wpdb->posts,
				array(
					'post_content' => $post_content,
				),
				array(
					'ID' => $row['ID'],
				)
			);
			clean_post_cache( $row['ID'] );
		}
	}

}
