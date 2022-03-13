<?php
/**
 * Helpers class.
 *
 * @package Smush\Core
 * @version 1.0
 *
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2017, Incsub (http://incsub.com)
 */

namespace Smush\Core;

use finfo;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Helper
 */
class Helper {

	/**
	 * Check if user is a WPMU DEV admin.
	 *
	 * @since 3.9.3
	 *
	 * @return bool
	 */
	public static function is_wpmu_dev_admin() {
		if ( class_exists( '\WPMUDEV_Dashboard' ) ) {
			if ( method_exists( '\WPMUDEV_Dashboard_Site', 'allowed_user' ) ) {
				$user_id = get_current_user_id();
				return \WPMUDEV_Dashboard::$site->allowed_user( $user_id );
			}
		}

		return false;
	}

	/**
	 * Get mime type for file.
	 *
	 * @since 3.1.0  Moved here as a helper function.
	 *
	 * @param string $path  Image path.
	 *
	 * @return bool|string
	 */
	public static function get_mime_type( $path ) {
		// These mime functions only work on local files/streams.
		if ( ! stream_is_local( $path ) ) {
			return false;
		}

		// Get the File mime.
		if ( class_exists( 'finfo' ) ) {
			$finfo = new finfo( FILEINFO_MIME_TYPE );
		} else {
			$finfo = false;
		}

		if ( $finfo ) {
			$mime = file_exists( $path ) ? $finfo->file( $path ) : '';
		} elseif ( function_exists( 'mime_content_type' ) ) {
			$mime = mime_content_type( $path );
		} else {
			$mime = false;
		}

		return $mime;
	}

	/**
	 * Filter the Posts object as per mime type.
	 *
	 * @param array $posts Object of Posts.
	 *
	 * @return mixed array of post ids
	 */
	public static function filter_by_mime( $posts ) {
		if ( empty( $posts ) ) {
			return $posts;
		}

		foreach ( $posts as $post_k => $post ) {
			if ( ! isset( $post->post_mime_type ) || ! in_array( $post->post_mime_type, Core::$mime_types, true ) ) {
				unset( $posts[ $post_k ] );
			} else {
				$posts[ $post_k ] = $post->ID;
			}
		}

		return $posts;
	}

	/**
	 * Return unfiltered file path
	 *
	 * @param int $attachment_id  Attachment ID.
	 *
	 * @return bool|string
	 */
	public static function get_attached_file( $attachment_id ) {
		if ( empty( $attachment_id ) ) {
			return false;
		}

		do_action( 'smush_s3_integration_fetch_file' );

		if ( function_exists( 'wp_get_original_image_path' ) ) {
			$file_path = wp_get_original_image_path( $attachment_id );
			if ( ! empty( $file_path ) && strpos( $file_path, 's3' ) !== false ) {
				$file_path = wp_get_original_image_path( $attachment_id, true );
			}
		} else {
			$file_path = get_attached_file( $attachment_id );
			if ( ! empty( $file_path ) && strpos( $file_path, 's3' ) !== false ) {
				$file_path = get_attached_file( $attachment_id, true );
			}
		}

		// Turn the filter off again. We'll call this method when we want the file to be downloaded.
		add_filter( 'as3cf_get_attached_file_copy_back_to_local', '__return_false' );

		return $file_path;
	}

	/**
	 * Iterate over PNG->JPG Savings to return cummulative savings for an image
	 *
	 * @param string $attachment_id  Attachment ID.
	 *
	 * @return array|bool
	 */
	public static function get_pngjpg_savings( $attachment_id = '' ) {
		// Initialize empty array.
		$savings = array(
			'bytes'       => 0,
			'size_before' => 0,
			'size_after'  => 0,
		);

		// Return empty array if attaachment id not provided.
		if ( empty( $attachment_id ) ) {
			return $savings;
		}

		$pngjpg_savings = get_post_meta( $attachment_id, 'wp-smush-pngjpg_savings', true );
		if ( empty( $pngjpg_savings ) || ! is_array( $pngjpg_savings ) ) {
			return $savings;
		}

		foreach ( $pngjpg_savings as $size => $s_savings ) {
			if ( empty( $s_savings ) ) {
				continue;
			}
			$savings['size_before'] += $s_savings['size_before'];
			$savings['size_after']  += $s_savings['size_after'];
		}
		$savings['bytes'] = $savings['size_before'] - $savings['size_after'];

		return $savings;
	}

	/**
	 * Checks if file for given attachment id exists on s3, otherwise looks for local path.
	 *
	 * @param int    $id         File ID.
	 * @param string $file_path  Path.
	 *
	 * @return bool
	 */
	public static function file_exists( $id, $file_path = '' ) {
		// If not attachment id is given return false.
		if ( empty( $id ) ) {
			return false;
		}

		$s3 = WP_Smush::get_instance()->core()->s3;

		// If S3 is enabled.
		if ( is_object( $s3 ) && method_exists( $s3, 'is_image_on_s3' ) && $s3->is_image_on_s3( $id ) ) {
			$file_exists = true;
		} else {
			// Get file path, if not provided.
			if ( empty( $file_path ) ) {
				$file_path = self::get_attached_file( $id );
			}
			$file_exists = file_exists( $file_path );
		}

		return $file_exists;
	}

	/**
	 * Removes the main file from an attachement when S3 is enabled and the file is on S3.
	 *
	 * The method @see self::get_attached_file() downloads the main image
	 * from S3 into the server. That method is called for certain process.
	 * This method should clean up the local file after those processes are done.
	 *
	 * @since 3.8.3
	 *
	 * @param int $attachment_id Image ID.
	 */
	public static function remove_main_file_from_server_when_in_s3( $attachment_id ) {
		// Skip if the image wasn't downloaded.
		if ( 0 === did_action( 'smush_s3_integration_fetch_file' ) ) {
			return;
		}

		$s3 = WP_Smush::get_instance()->core()->s3;

		// If S3 is enabled.
		if ( is_object( $s3 ) && method_exists( $s3, 'is_image_on_s3' ) && $s3->is_image_on_s3( $attachment_id ) ) {
			global $as3cf;

			// Remove the local file only when S3 is removing them.
			if ( '1' !== $as3cf->get_setting( 'remove-local-file' ) ) {
				return;
			}
			$file_path = get_attached_file( $attachment_id );
			if ( file_exists( $file_path ) ) {
				unlink( $file_path );
			}
		}
	}

	/**
	 * Get the link to the media library page for the image.
	 *
	 * @since 2.9.0
	 *
	 * @param int    $id    Image ID.
	 * @param string $name  Image file name.
	 * @param bool   $src   Return only src. Default - return link.
	 *
	 * @return string
	 */
	public static function get_image_media_link( $id, $name, $src = false ) {
		$mode = get_user_option( 'media_library_mode' );
		if ( 'grid' === $mode ) {
			$link = admin_url( "upload.php?item={$id}" );
		} else {
			$link = admin_url( "post.php?post={$id}&action=edit" );
		}

		if ( ! $src ) {
			return "<a href='{$link}'>{$name}</a>";
		}

		return $link;
	}

	/**
	 * Returns current user name to be displayed
	 *
	 * @return string
	 */
	public static function get_user_name() {
		$current_user = wp_get_current_user();
		return ! empty( $current_user->first_name ) ? $current_user->first_name : $current_user->display_name;
	}

	/**
	 * Allows to filter the error message sent to the user
	 *
	 * @param string $error          Error message.
	 * @param string $attachment_id  Attachment ID.
	 *
	 * @return mixed|null|string
	 */
	public static function filter_error( $error = '', $attachment_id = '' ) {
		if ( empty( $error ) ) {
			return null;
		}

		/**
		 * Replace the 500 server error with a more appropriate error message.
		 */
		if ( false !== strpos( $error, '500 Internal Server Error' ) ) {
			$error = __( "Couldn't process image due to bad headers. Try re-saving the image in an image editor, then upload it again.", 'wp-smushit' );
		}

		/**
		 * Used internally to modify the error message
		 */
		$error = apply_filters( 'wp_smush_error', $error, $attachment_id );

		return $error;
	}

	/**
	 * Format metadata from $_POST request.
	 *
	 * Post request in WordPress will convert all values
	 * to string. Make sure image height and width are int.
	 * This is required only when Async requests are used.
	 * See - https://wordpress.org/support/topic/smushit-overwrites-image-meta-crop-sizes-as-string-instead-of-int/
	 *
	 * @since 2.8.0
	 *
	 * @param array $meta Metadata of attachment.
	 *
	 * @return array
	 */
	public static function format_meta_from_post( $meta = array() ) {
		// Do not continue in case meta is empty.
		if ( empty( $meta ) ) {
			return $meta;
		}

		// If metadata is array proceed.
		if ( is_array( $meta ) ) {

			// Walk through each items and format.
			array_walk_recursive( $meta, array( 'self', 'format_attachment_meta_item' ) );
		}

		return $meta;
	}

	/**
	 * If current item is width or height, make sure it is int.
	 *
	 * @since 2.8.0
	 *
	 * @param mixed  $value Meta item value.
	 * @param string $key Meta item key.
	 */
	public static function format_attachment_meta_item( &$value, $key ) {
		if ( 'height' === $key || 'width' === $key ) {
			$value = (int) $value;
		}

		/**
		 * Allows to format single item in meta.
		 *
		 * This filter will be used only for Async, post requests.
		 *
		 * @param mixed $value Meta item value.
		 * @param string $key Meta item key.
		 */
		$value = apply_filters( 'wp_smush_format_attachment_meta_item', $value, $key );
	}

	/**
	 * Check to see if file is animated.
	 *
	 * @since 3.0  Moved from class-resize.php
	 *
	 * @param string $file_path  Image file path.
	 * @param int    $id         Attachment ID.
	 */
	public static function check_animated_status( $file_path, $id ) {
		// Only do this for GIFs.
		if ( 'image/gif' !== get_post_mime_type( $id ) || ! isset( $file_path ) ) {
			return;
		}

		$filecontents = file_get_contents( $file_path );

		$str_loc = 0;
		$count   = 0;

		// There is no point in continuing after we find a 2nd frame.
		while ( $count < 2 ) {
			$where1 = strpos( $filecontents, "\x00\x21\xF9\x04", $str_loc );
			if ( false === $where1 ) {
				break;
			} else {
				$str_loc = $where1 + 1;
				$where2  = strpos( $filecontents, "\x00\x2C", $str_loc );
				if ( false === $where2 ) {
					break;
				} else {
					if ( $where2 === $where1 + 8 ) {
						$count++;
					}
					$str_loc = $where2 + 1;
				}
			}
		}

		if ( $count > 1 ) {
			update_post_meta( $id, 'wp-smush-animated', true );
		}
	}

	/**
	 * Original File path
	 *
	 * @param string $original_file  Original file.
	 *
	 * @return string File Path
	 */
	public static function original_file( $original_file = '' ) {
		$uploads     = wp_get_upload_dir();
		$upload_path = $uploads['basedir'];

		return path_join( $upload_path, $original_file );
	}

	/**
	 * Gets the WPMU DEV API key.
	 *
	 * @since 3.8.6
	 *
	 * @return string|false
	 */
	public static function get_wpmudev_apikey() {
		// If API key defined manually, get that.
		if ( defined( 'WPMUDEV_APIKEY' ) && WPMUDEV_APIKEY ) {
			return WPMUDEV_APIKEY;
		}

		// If dashboard plugin is active, get API key from db.
		if ( class_exists( 'WPMUDEV_Dashboard' ) ) {
			return get_site_option( 'wpmudev_apikey' );
		}

		return false;
	}

	/**
	 * Get upsell URL.
	 *
	 * @since 3.9.1
	 *
	 * @param string $utm_campaign  Campaing string.
	 *
	 * @return string
	 */
	public static function get_url( $utm_campaign = '' ) {
		$upgrade_url = 'https://wpmudev.com/project/wp-smush-pro/';

		return add_query_arg(
			array(
				'utm_source'   => 'smush',
				'utm_medium'   => 'plugin',
				'utm_campaign' => $utm_campaign,
			),
			$upgrade_url
		);
	}
}
