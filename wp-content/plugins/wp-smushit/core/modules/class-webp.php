<?php
/**
 * WebP class: WebP
 *
 * @package Smush\Core\Modules
 * @since 3.8.0
 */

namespace Smush\Core\Modules;

use Smush\Core\Core;
use Smush\Core\Helper;
use WP_Error;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class WebP extends Abstract_Module.
 */
class WebP extends Abstract_Module {

	/**
	 * If server is configured for webp
	 *
	 * @access private
	 * @var bool $is_configured
	 */
	private $is_configured;

	/**
	 * Initialize the module.
	 */
	public function init() {
		// Show success message after deleting all webp images.
		add_action( 'wp_smush_header_notices', array( $this, 'maybe_show_notices' ) );
	}

	/**
	 * Enables and disables the WebP module.
	 *
	 * @since 3.8.0
	 *
	 * @param boolean $enable Whether to enable or disable WebP.
	 */
	public function toggle_webp( $enable = true ) {
		$this->settings->set( 'webp_mod', $enable );

		global $wp_filesystem;
		if ( is_null( $wp_filesystem ) ) {
			// These aren't included when applying a config from the Hub.
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			WP_Filesystem();
		}

		$parsed_udir    = $this->get_upload_dir();
		$flag_file_path = $parsed_udir['webp_path'] . '/disable_smush_webp';

		// Handle the file used as a flag by the server rules.
		if ( $enable ) {
			$wp_filesystem->delete( $flag_file_path, true );
		} else {
			$wp_filesystem->put_contents( $flag_file_path, '' );
		}
	}

	/**
	 * Gets whether WebP is configured, returning a message to display when it's not.
	 * This is a wrapper for displaying a message on failure which is used in three places.
	 * Moved here to reduce the redundancy.
	 *
	 * @since 3.8.8
	 *
	 * @param bool $force  Force check.
	 *
	 * @return true|string True when it's configured. String when it's not.
	 */
	public function get_is_configured_with_error_message( $force = false ) {
		$is_configured = $this->is_configured( $force );

		if ( true === $is_configured ) {
			return true;
		}

		if ( is_wp_error( $is_configured ) ) {
			return $is_configured->get_error_message();
		}
		if ( 'apache' === $this->get_server_type() && $this->is_htaccess_written() ) {
			return __( "The server rules have been applied but the server doesn't seem to be serving your images as WebP. We recommend contacting your hosting provider to learn more about the cause of this issue.", 'wp-smushit' );
		}

		return __( "Server configurations haven't been applied yet. Make configurations to start serving images in WebP format.", 'wp-smushit' );
	}

	/**
	 * Get status of server configuration for webp.
	 *
	 * @since 3.8.0
	 *
	 * @param bool $force force to recheck.
	 *
	 * @return bool|WP_Error
	 */
	public function is_configured( $force = false ) {
		if ( ! is_null( $this->is_configured ) && ! $force ) {
			return $this->is_configured;
		}

		$this->is_configured = $this->check_server_config();

		return $this->is_configured;
	}

	/**
	 * Check if server is configured to serve webp image.
	 *
	 * @since 3.8.0
	 *
	 * @return bool|WP_Error
	 */
	private function check_server_config() {
		$files_created = $this->create_test_files();

		// WebP test images couldn't be created.
		if ( true !== $files_created ) {
			$message = sprintf(
				/* translators: path that couldn't be written */
				__( 'We couldn\'t create the WebP test files. This is probably due to your current folder permissions. Please adjust the permissions for "%s" to 755 and try again.', 'wp-smushit' ),
				$files_created
			);
			return new WP_Error( 'test_files_not_created', $message );
		}

		$udir       = $this->get_upload_dir();
		$test_image = $udir['upload_url'] . '/smush-webp-test.png';

		$args['headers']['Accept'] = 'image/webp';

		// Add support for basic auth in WPMU DEV staging.
		if ( isset( $_SERVER['WPMUDEV_HOSTING_ENV'] ) && 'staging' === $_SERVER['WPMUDEV_HOSTING_ENV'] && isset( $_SERVER['PHP_AUTH_USER'] ) ) {
			$args['headers']['Authorization'] = 'Basic ' . base64_encode( $_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW'] );
		}

		$response = wp_remote_get( $test_image, $args );
		$code     = wp_remote_retrieve_response_code( $response );

		// Check the image's format when the request was successful.
		if ( 200 === $code ) {
			$content_type = wp_remote_retrieve_header( $response, 'content-type' );
			return 'image/webp' === $content_type;
		}

		// Return the response code and message otherwise.
		$error_message = sprintf(
			/* translators: 1. error code, 2. error message. */
			__( "We couldn't check the WebP server rules status because there was an error with the test request. Please contact support for assistance. Code %1\$s: %2\$s.", 'wp-smushit' ),
			$code,
			wp_remote_retrieve_response_message( $response )
		);
		return new WP_Error( $code, $error_message );
	}

	/**
	 * Code to use on Nginx servers.
	 *
	 * @since 3.8.0
	 *
	 * @param bool $marker whether to wrap code with marker comment lines.
	 * @return string
	 */
	public function get_nginx_code( $marker = true ) {
		$udir = $this->get_upload_dir();

		$base       = trailingslashit( dirname( $udir['upload_rel_path'] ) );
		$directory  = trailingslashit( basename( $udir['upload_rel_path'] ) );
		$regex_base = $base . '(' . $directory . ')';

		$code = 'location ~* "' . str_replace( '/', '\/', $regex_base ) . '(.*.(?:png|jpe?g))" {
  add_header Vary Accept;
  set $image_path $2;
  if (-f "' . $udir['webp_path'] . '/disable_smush_webp") {
    break;
  }
  if ($http_accept !~* "webp") {
    break;
  }
  try_files /' . trailingslashit( $udir['webp_rel_path'] ) . '$image_path.webp $uri =404;
}';

		if ( true === $marker ) {
			$code = $this->marker_line() . "\n" . $code;
			$code = $code . "\n" . $this->marker_line( true );
		}
		return apply_filters( 'smush_nginx_webp_rules', $code );
	}

	/**
	 * Code to use on Apache servers.
	 *
	 * @since 3.8.0
	 *
	 * @todo Find out what's wrong with the rules. We shouldn't need these two different RewriteRule.
	 *
	 * @param string $location Where the .htaccess file is.
	 *
	 * @return string
	 */
	private function get_apache_code( $location ) {
		$udir = $this->get_upload_dir();

		$rewrite_path = '%{DOCUMENT_ROOT}/' . $udir['webp_rel_path'];

		$code = '<IfModule mod_rewrite.c>
 RewriteEngine On
 RewriteCond ' . $rewrite_path . '/disable_smush_webp !-f
 RewriteCond %{HTTP_ACCEPT} image/webp' . "\n";

		if ( 'root' === $location ) {
			// This works on single sites at root.
			$code .= ' RewriteCond ' . $rewrite_path . '/$1.webp -f
 RewriteRule ' . $udir['upload_rel_path'] . '/(.*.(?:png|jpe?g))$ ' . $udir['webp_rel_path'] . '/$1.webp [NC,T=image/webp]';
		} else {
			// This works at /uploads/.
			$code .= ' RewriteCond ' . $rewrite_path . '/$1.$2.webp -f
 RewriteRule ^/?(.+)\.(jpe?g|png)$ /' . $udir['webp_rel_path'] . '/$1.$2.webp [NC,T=image/webp]';
		}

		$code .= "\n" . '</IfModule>

<IfModule mod_headers.c>
 Header append Vary Accept env=WEBP_image
</IfModule>

<IfModule mod_mime.c>
 AddType image/webp .webp
</IfModule>';

		return apply_filters( 'smush_apache_webp_rules', $code );
	}

	/**
	 * Gets the apache rules for printing them in the config tab.
	 *
	 * @since 3.8.4
	 *
	 * @return string
	 */
	public function get_apache_code_to_print() {
		$location = is_multisite() ? 'uploads' : 'root';

		$code  = $this->marker_line() . "\n";
		$code .= $this->get_apache_code( $location );
		$code .= "\n" . $this->marker_line( true );

		return $code;
	}

	/**
	 * Retrieves uploads directory and WebP directory information.
	 * All paths and urls do not have trailing slash.
	 *
	 * @return array
	 */
	public function get_upload_dir() {
		static $upload_dir_info;
		if ( isset( $upload_dir_info ) ) {
			return $upload_dir_info;
		}

		if ( ! is_multisite() || is_main_site() ) {
			$upload = wp_upload_dir();
		} else {
			// Use the main site's upload directory for all subsite's webp converted images.
			// This makes it easier to have a single rule on the server configs for serving webp in mu.
			$blog_id = get_main_site_id();
			switch_to_blog( $blog_id );
			$upload = wp_upload_dir();
			restore_current_blog();
		}

		// Is it possible that none of the following conditions are met?
		$root_path_base = '';

		// Get the Document root path. There must be a better way to do this.
		// For example, /srv/www/site/public_html for /srv/www/site/public_html/wp-content/uploads.
		if ( 0 === strpos( $upload['basedir'], ABSPATH ) ) {
			// Environments like Flywheel have an ABSPATH that's not used in the paths.
			$root_path_base = ABSPATH;
		} elseif ( isset( $_SERVER['DOCUMENT_ROOT'] ) && 0 === strpos( $upload['basedir'], $_SERVER['DOCUMENT_ROOT'] ) ) {
			// This gets called when scanning for uncompressed images.
			// When ran from certain contexts, $_SERVER['DOCUMENT_ROOT'] might not be set.
			$root_path_base = $_SERVER['DOCUMENT_ROOT'];
		} elseif ( 0 === strpos( $upload['basedir'], dirname( WP_CONTENT_DIR ) ) ) {
			// We're assuming WP_CONTENT_DIR is only one level deep into the document root.
			// This might not be true in customized sites. A bit edgy.
			$root_path_base = dirname( WP_CONTENT_DIR );
		}

		/**
		 * Filters the Document root path used to get relative paths for webp rules.
		 * Hopefully of help for debugging and SLS.
		 *
		 * @since 3.9.0
		 */
		$root_path_base = apply_filters( 'smush_webp_rules_root_path_base', $root_path_base );

		// Get the upload path relative to the Document root.
		// For example, wp-content/uploads for /srv/www/site/public_html/wp-content/uploads.
		$upload_root_rel_path = ltrim( str_replace( $root_path_base, '', $upload['basedir'] ), '/' );

		// Get the relative path for the  directory containing the webp files.
		// This directory is a sibling of the 'uploads' directory.
		// For example, wp-content/smush-webp for wp-content/uploads.
		$webp_root_rel_path = dirname( $upload_root_rel_path ) . '/smush-webp';

		return array(
			'upload_path'     => $upload['basedir'],
			'upload_rel_path' => $upload_root_rel_path,
			'upload_url'      => $upload['baseurl'],
			'webp_path'       => dirname( $upload['basedir'] ) . '/smush-webp',
			'webp_rel_path'   => $webp_root_rel_path,
			'webp_url'        => dirname( $upload['baseurl'] ) . '/smush-webp',
		);
	}

	/**
	 * Create test files and required directory.
	 *
	 * @return true|string String with the path that couldn't be written on failure.
	 */
	public function create_test_files() {
		$udir           = $this->get_upload_dir();
		$test_png_file  = $udir['upload_path'] . '/smush-webp-test.png';
		$test_webp_file = $udir['webp_path'] . '/smush-webp-test.png.webp';

		// Create the png file to be requested if it doesn't exist. Bail out if it fails.
		if (
			! file_exists( $test_png_file ) &&
			! copy( WP_SMUSH_DIR . 'app/assets/images/smush-webp-test.png', $test_png_file )
		) {
			return $udir['upload_path'];
		}

		// Create the WebP file that should be sent in the response if the rules work.
		if ( ! file_exists( $test_webp_file ) ) {
			if ( ! is_dir( $udir['webp_path'] ) ) {
				$directory_created = wp_mkdir_p( $udir['webp_path'] );
			}

			// Bail out if it fails.
			if (
				! $directory_created ||
				! copy( WP_SMUSH_DIR . 'app/assets/images/smush-webp-test.png.webp', $test_webp_file )
			) {
				return $udir['webp_path'];
			}
		}

		return true;
	}

	/**
	 * Retrieves related webp image file path for a given non webp image file path.
	 * Also create required directories for webp image if not exists.
	 *
	 * @param string $file_path  Non webp image file path.
	 * @param bool   $make       Weather to create required directories.
	 *
	 * @return string
	 */
	public function get_webp_file_path( $file_path, $make = false ) {
		$udir = $this->get_upload_dir();

		$file_rel_path  = substr( $file_path, strlen( $udir['upload_path'] ) );
		$webp_file_path = $udir['webp_path'] . $file_rel_path . '.webp';

		if ( $make ) {
			$webp_file_dir = dirname( $webp_file_path );
			if ( ! is_dir( $webp_file_dir ) ) {
				wp_mkdir_p( $webp_file_dir );
			}
		}

		return $webp_file_path;
	}

	/**
	 * Check whether the given attachment id or mime type can be converted to WebP.
	 *
	 * @param string $id   Atachment ID.
	 * @param string $mime Mime type.
	 *
	 * @return bool
	 */
	private function can_be_converted( $id = '', $mime = '' ) {
		if ( empty( $id ) && empty( $mime ) ) {
			return false;
		}

		$mime = empty( $mime ) ? get_post_mime_type( $id ) : $mime;

		// This image can not be converted to webp.
		if ( ! ( 'image/jpeg' === $mime || 'image/png' === $mime ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks whether an attachment should be converted to WebP.
	 * Returns false if WebP isn't configured, the attachment was already converted,
	 * or if the attachment can't be converted ( @see self::can_be_converted() ).
	 *
	 * @since 3.8.0
	 *
	 * @param string $id Attachment ID.
	 *
	 * @return bool
	 */
	public function should_be_converted( $id ) {
		// Avoid conversion when webp disabled, or when Smush is Free.
		if ( ! $this->settings->get( 'webp_mod' ) || ! WP_Smush::is_pro() ) {
			return false;
		}

		$meta      = get_post_meta( $id, Smush::$smushed_meta_key, true );
		$webp_udir = $this->get_upload_dir();

		// The image was already converted to WebP.
		if ( ! empty( $meta['webp_flag'] ) && file_exists( $webp_udir['webp_path'] . '/' . $meta['webp_flag'] ) ) {
			return false;
		}

		return $this->can_be_converted( $id );
	}

	/**
	 * Convert images to WebP.
	 *
	 * @since 3.8.0
	 *
	 * @param int   $attachment_id  Attachment ID.
	 * @param array $meta           Attachment meta.
	 *
	 * @return array
	 */
	public function convert_to_webp( $attachment_id, $meta ) {
		$webp_files = array();

		if ( ! wp_attachment_is_image( $attachment_id ) ) {
			return $webp_files;
		}

		if ( ! $this->should_be_converted( $attachment_id ) ) {
			return $webp_files;
		}

		// File path and URL for original image.
		$attachment_file_path = Helper::get_attached_file( $attachment_id );

		// If images has other registered size, smush them first.
		if ( ! empty( $meta['sizes'] ) && ! has_filter( 'wp_image_editors', 'photon_subsizes_override_image_editors' ) ) {
			foreach ( $meta['sizes'] as $size_data ) {
				// We take the original image. The 'sizes' will all match the same URL and
				// path. So just get the dirname and replace the filename.
				$attachment_file_path_size = path_join( dirname( $attachment_file_path ), $size_data['file'] );

				// Allows S3 to hook over here and check if the given file path exists else download the file.
				do_action( 'smush_file_exists', $attachment_file_path_size, $attachment_id, $size_data );

				$ext = Helper::get_mime_type( $attachment_file_path_size );
				if ( $ext && false === array_search( $ext, Core::$mime_types, true ) ) {
					continue;
				}

				$response = WP_Smush::get_instance()->core()->mod->smush->do_smushit( $attachment_file_path_size, true );

				if ( is_wp_error( $response ) || ! $response ) {
					$webp_has_error = true;
				} else {
					$webp_files[] = $this->get_webp_file_path( $attachment_file_path_size );
				}
			}
		}

		if ( isset( $webp_has_error ) ) {
			return $webp_files;
		}

		$response = WP_Smush::get_instance()->core()->mod->smush->do_smushit( $attachment_file_path, true );
		if ( ! is_wp_error( $response ) ) {
			$webp_files[] = $this->get_webp_file_path( $attachment_file_path );

			// If all images have been converted, set a flag in meta.
			$stats = get_post_meta( $attachment_id, Smush::$smushed_meta_key, true );
			if ( ! $stats ) {
				$stats = array();
			}

			$upload_dir = $this->get_upload_dir();
			// Use the relative path of the first webp image as a flag.
			$stats['webp_flag'] = substr( $webp_files[0], strlen( $upload_dir['webp_path'] . '/' ) );

			update_post_meta( $attachment_id, Smush::$smushed_meta_key, $stats );
		}

		return $webp_files;
	}

	/**
	 * Deletes all the webp files when an attachment is deleted
	 * Update Smush::$smushed_meta_key meta ( optional )
	 * Used in Smush::delete_images() and Backup::restore_image()
	 *
	 * @since 3.8.0
	 *
	 * @param int    $image_id  Attachment ID.
	 * @param bool   $update_meta Whether to update meta or not.
	 * @param string $main_file Main file to replace the one retrieved via the $id.
	 *                          Useful for deleting webp images after PNG to JPG conversion.
	 */
	public function delete_images( $image_id, $update_meta = true, $main_file = '' ) {
		$meta = wp_get_attachment_metadata( $image_id );

		// File path for original image.
		if ( empty( $main_file ) ) {
			$main_file = get_attached_file( $image_id );
		}

		// Not a supported image? Exit.
		if ( ! in_array( strtolower( pathinfo( $main_file, PATHINFO_EXTENSION ) ), array( 'gif', 'jpg', 'jpeg', 'png' ), true ) ) {
			return;
		}

		$main_file_webp = $this->get_webp_file_path( $main_file );

		$dir_path = dirname( $main_file_webp );

		if ( file_exists( $main_file_webp ) ) {
			unlink( $main_file_webp );
		}

		if ( ! empty( $meta['sizes'] ) ) {
			foreach ( $meta['sizes'] as $size_data ) {
				$size_file = path_join( $dir_path, $size_data['file'] );
				if ( file_exists( $size_file . '.webp' ) ) {
					unlink( $size_file . '.webp' );
				}
			}
		}

		if ( $update_meta ) {
			$smushed_meta_key = Smush::$smushed_meta_key;
			$stats            = get_post_meta( $image_id, $smushed_meta_key, true );
			if ( ! empty( $stats ) && is_array( $stats ) ) {
				unset( $stats['webp_flag'] );
				update_post_meta( $image_id, $smushed_meta_key, $stats );
			}
		}
	}

	/**
	 * Deletes all webp images for the whole network or the current subsite.
	 * It deletes the whole smush-webp directory when it's a single install
	 * or a MU called from the network admin (and the current_user_can( manage_network )).
	 *
	 * @since 3.8.0
	 */
	public function delete_all() {
		global $wp_filesystem;
		if ( is_null( $wp_filesystem ) ) {
			WP_Filesystem();
		}

		$parsed_udir = $this->get_upload_dir();

		// Delete the whole webp directory only when on single install or network admin.
		$wp_filesystem->delete( $parsed_udir['webp_path'], true );
	}

	/**
	 * Renders the notice after deleting all webp images.
	 *
	 * @since 3.8.0
	 *
	 * @param string $tab  Smush tab name.
	 */
	public function maybe_show_notices( $tab ) {
		// Show only on WebP page.
		if ( ! isset( $tab ) || 'webp' !== $tab ) {
			return;
		}

		// Show only when there are images in the library, except on mu, where the count is always 0.
		if ( ! is_multisite() && 0 === WP_Smush::get_instance()->core()->total_count ) {
			return;
		}

		$show_message = filter_input( INPUT_GET, 'notice', FILTER_SANITIZE_STRING );
		// Success notice after deleting all WebP images.
		if ( 'webp-deleted' === $show_message ) {
			$message = __( 'WebP files were deleted successfully.', 'wp-smushit' );
			echo '<div role="alert" id="wp-smush-webp-delete-all-notice" data-message="' . esc_attr( $message ) . '" class="sui-notice" aria-live="assertive"></div>';
		}
	}

	/*
	 * Server related methods.
	 */

	/**
	 * Return the server type (Apache, NGINX...)
	 *
	 * @return string Server type
	 */
	public function get_server_type() {
		global $is_apache, $is_IIS, $is_iis7, $is_nginx;

		if ( $is_apache ) {
			// It's a common configuration to use nginx in front of Apache.
			// Let's make sure that this server is Apache.
			$response = wp_remote_get( home_url() );

			if ( is_wp_error( $response ) ) {
				// Bad luck.
				return 'apache';
			}

			$server = strtolower( wp_remote_retrieve_header( $response, 'server' ) );
			// Could be LiteSpeed too.
			return ( strpos( $server, 'nginx' ) !== false ? 'nginx' : 'apache' );

		}
		if ( $is_nginx ) {
			return 'nginx';
		}
		if ( $is_IIS ) {
			return 'IIS';
		}
		if ( $is_iis7 ) {
			return 'IIS 7';
		}

		return 'unknown';
	}

	/*
	 * Apache's .htaccess rules handling.
	*/

	/**
	 * Gets the path of .htaccess file for the given location.
	 *
	 * @param string $location Location of the .htaccess file to retrieve. root|uploads.
	 *
	 * @return string
	 */
	private function get_htaccess_file( $location ) {
		if ( 'root' === $location ) {
			// Get the .htaccess located at the root.
			$base_dir = get_home_path();
		} else {
			// Get the .htaccess located at the uploads directory.
			if ( ! function_exists( 'wp_upload_dir' ) ) {
				require_once ABSPATH . 'wp-includes/functions.php';
			}

			$uploads  = wp_upload_dir();
			$base_dir = $uploads['basedir'];
		}

		return rtrim( $base_dir, '/' ) . '/.htaccess';
	}

	/**
	 * Get unique string to use at marker comment line in .htaccess or nginx config file.
	 *
	 * @since 3.8.0
	 *
	 * @return string
	 */
	private function marker_suffix() {
		return 'SMUSH-WEBP';
	}

	/**
	 * Get unique string to use as marker comment line in .htaccess or nginx config file.
	 *
	 * @param bool $end whether to use marker after end of the config code.
	 * @return string
	 */
	private function marker_line( $end = false ) {
		if ( true === $end ) {
			return '# END ' . $this->marker_suffix();
		} else {
			return '# BEGIN ' . $this->marker_suffix();
		}
	}

	/**
	 * Check if .htaccess has rules for this module in place.
	 *
	 * @since 3.8.0
	 *
	 * @param bool|string $location Location of the .htaccess to check.
	 *
	 * @return bool
	 */
	public function is_htaccess_written( $location = false ) {
		if ( ! function_exists( 'extract_from_markers' ) ) {
			require_once ABSPATH . 'wp-admin/includes/misc.php';
		}

		$has_rules = false;

		// Remove the rules from all the possible places if not specified.
		$locations = ! $location ? $this->get_htaccess_locations() : array( $location );

		foreach ( $locations as $name ) {
			$htaccess  = $this->get_htaccess_file( $name );
			$has_rules = ! empty( $has_rules ) || array_filter( extract_from_markers( $htaccess, $this->marker_suffix() ) );
		}

		return $has_rules;
	}

	/**
	 * Tries different rules in different locations of the .htaccess file.
	 *
	 * @since 3.8.0
	 *
	 * @return bool|string True on success. String with the error message on failure.
	 */
	public function save_htaccess() {
		$cannot_write_message = sprintf(
			/* translators: 1. opening 'a' tag to premium support, 2. closing 'a' tag. */
			__( 'We tried to apply the .htaccess rules automatically but we were unable to complete this action. Make sure the file permissions on your .htaccess file are set to 644, or switch to manual mode and apply the rules yourself. If you need further assistance, you can %1$scontact support%2$s for help.', 'wp-smushit' ),
			'<a href="https://wpmudev.com/hub2/support/#get-support" target="_blank">',
			'</a>'
		);

		$last_error = sprintf(
			/* translators: 1. opening 'a' tag to docs, 2. opening 'a' tag to premium support, 3. closing 'a' tag. */
			__( 'We tried different rules but your server still isn\'t serving WebP images. Please contact your hosting provider for further assistance. You can also see our %1$stroubleshooting guide%3$s or %2$scontact support%3$s for help.', 'wp-smushit' ),
			'<a href="https://wpmudev.com/docs/wpmu-dev-plugins/smush/#wordpress-in-its-own-directory" target="_blank">',
			'<a href="https://wpmudev.com/hub2/support/#get-support" target="_blank">',
			'</a>'
		);

		$locations = $this->get_htaccess_locations();

		$is_configured = false;

		foreach ( $locations as $location ) {
			$htaccess = $this->get_htaccess_file( $location );

			$code             = $this->get_apache_code( $location );
			$code             = explode( "\n", $code );
			$markers_inserted = insert_with_markers( $htaccess, $this->marker_suffix(), $code );
			if ( ! $markers_inserted ) {
				$last_error = $cannot_write_message;
				continue;
			}

			$is_configured = $this->check_server_config();

			if ( true === $is_configured ) {
				break;
			}

			// TODO: if $is_configured is a wp error, display the message.

			$this->unsave_htaccess( $location );
		}

		if ( $is_configured ) {
			return true;
		}

		return $last_error;
	}

	/**
	 * Returns the handled locations for the .htaccess.
	 *
	 * @since 3.8.3
	 *
	 * @return array
	 */
	private function get_htaccess_locations() {
		if ( ! is_multisite() ) {
			$locations[] = 'root';
		}
		$locations[] = 'uploads';

		return $locations;
	}

	/**
	 * Remove rules from .htaccess file.
	 *
	 * @since 3.8.0
	 *
	 * @param bool|string $location Location of the htaccess to unsave. uploads|root.
	 *
	 * @return bool|string True on success. String with the error message on failure.
	 */
	public function unsave_htaccess( $location = false ) {
		if ( ! $this->is_htaccess_written( $location ) ) {
			return esc_html__( "The .htaccess file doesn't contain the WebP rules from Smush.", 'wp-smushit' );
		}

		$markers_inserted = false;

		// Remove the rules from all the possible places if not specified.
		$locations = ! $location ? $this->get_htaccess_locations() : array( $location );

		foreach ( $locations as $name ) {
			$htaccess         = $this->get_htaccess_file( $name );
			$markers_inserted = insert_with_markers( $htaccess, $this->marker_suffix(), '' ) || ! empty( $markers_inserted );
		}

		if ( ! $markers_inserted ) {
			return esc_html__( 'We were unable to automatically remove the rules. We recommend trying to remove the rules manually. If you don’t have access to the .htaccess file to remove it manually, please consult with your hosting provider to change the configuration on the server.', 'wp-smushit' );
		}
		return true;
	}
}
