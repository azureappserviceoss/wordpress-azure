<?php
/**
 * Directory Smush: Dir class
 *
 * @package Smush\Core\Modules
 * @since 2.6
 *
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2016, Incsub (http://incsub.com)
 */

namespace Smush\Core\Modules;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Smush\Core\Core;
use Smush\Core\Installer;
use Smush\Core\Settings;
use WP_Error;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Dir
 */
class Dir extends Abstract_Module {
	/**
	 * Contains a list of optimised images.
	 *
	 * @var $optimised_images
	 */
	public $optimised_images;

	/**
	 * Flag to check if dir smush table exist.
	 *
	 * @var $table_exist
	 */
	public static $table_exist;

	/**
	 * Total Stats for the image optimisation.
	 *
	 * @var $stats
	 */
	public $stats;

	/**
	 * Directory scanner.
	 *
	 * @var Helpers\DScanner
	 */
	public $scanner;

	/**
	 * Dir constructor.
	 */
	public function init() {
		// We only run in admin.
		if ( ! is_admin() ) {
			return;
		}

		/**
		 * Handle Ajax request 'smush_get_directory_list'.
		 *
		 * This needs to be before self::should_continue so that the request from network admin is processed.
		 */
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			if ( ! $this->scanner ) {
				$this->scanner = new Helpers\DScanner();
			}

			add_action( 'wp_ajax_smush_get_directory_list', array( $this, 'directory_list' ) );

			// Scan the given directory path for the list of images.
			add_action( 'wp_ajax_image_list', array( $this, 'image_list' ) );

			/**
			 * Scanner ajax actions.
			 *
			 * @since 2.8.1
			 */
			add_action( 'wp_ajax_directory_smush_start', array( $this, 'directory_smush_start' ) );
			add_action( 'wp_ajax_directory_smush_check_step', array( $this, 'directory_smush_check_step' ) );
			add_action( 'wp_ajax_directory_smush_finish', array( $this, 'directory_smush_finish' ) );
			add_action( 'wp_ajax_directory_smush_cancel', array( $this, 'directory_smush_cancel' ) );
		}

		add_action( 'current_screen', array( $this, 'initialize' ), 10 );
	}

	/**
	 * To get access to get_current_screen(), we need to move this under the current_screen action.
	 *
	 * @since 2.8.0
	 */
	public function initialize() {
		$current_page = '';
		if ( function_exists( 'get_current_screen' ) ) {
			$current_screen = get_current_screen();
			$current_page   = ! empty( $current_screen ) ? $current_screen->base : '';
		}

		if ( false === strpos( $current_page, 'page_smush-directory' ) ) {
			return;
		}

		if ( ! self::should_continue() ) {
			// Remove directory smush from tabs if not required.
			add_filter( 'smush_setting_tabs', array( $this, 'remove_directory_tab' ) );

			return;
		}

		if ( ! $this->scanner ) {
			$this->scanner = new Helpers\DScanner();
		}

		if ( ! $this->scanner->is_scanning() ) {
			$this->scanner->reset_scan();
		}

		// Check and show missing directory smush table error.
		add_action( 'wp_smush_header_notices', array( $this, 'show_table_error' ) );

		// Check directory smush table after screen is set.
		add_action( 'current_screen', array( $this, 'check_table' ) );

		// Check to see if the scanner should be running.
		add_action( 'admin_footer', array( $this, 'check_scan' ) );
	}

	/**
	 * Do not display Directory smush for subsites.
	 *
	 * @return bool True/False, whether to display the Directory smush or not
	 */
	public static function should_continue() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_SERVER['HTTP_REFERER'] ) && preg_match( '#^' . network_admin_url() . '#i', wp_unslash( $_SERVER['HTTP_REFERER'] ) ) ) { // Input var ok.
			return true;
		}

		// Do not show directory smush, if not main site in a network.
		if ( is_multisite() && ( ! is_main_site() || ! is_network_admin() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Run the scanner on page refresh (if it's running).
	 *
	 * @since 2.8.1
	 */
	public function check_scan() {
		if ( $this->scanner->is_scanning() ) {
			?>
			<script>
				jQuery( document ).ready( function() {
					jQuery('#wp-smush-progress-dialog').show();
					window.WP_Smush.directory.scanner.scan();
				});
			</script>
			<?php
		}
	}

	/**
	 * Directory Smush: Start smush.
	 *
	 * @since 2.8.1
	 */
	public function directory_smush_start() {
		$this->scanner->init_scan();
		wp_send_json_success();
	}

	/**
	 * Directory Smush: Smush step.
	 *
	 * @since 2.8.1
	 */
	public function directory_smush_check_step() {
		$urls         = $this->get_scanned_images();
		$current_step = absint( $_POST['step'] ); // Input var ok.

		$this->scanner->update_current_step( $current_step );

		if ( isset( $urls[ $current_step ] ) ) {
			$this->optimise_image( (int) $urls[ $current_step ]['id'] );
		}

		wp_send_json_success();
	}

	/**
	 * Directory Smush: Finish smush.
	 *
	 * @since 2.8.1
	 */
	public function directory_smush_finish() {
		$items   = isset( $_POST['items'] ) ? absint( $_POST['items'] ) : 0; // Input var ok.
		$failed  = isset( $_POST['failed'] ) ? absint( $_POST['failed'] ) : 0; // Input var ok.
		$skipped = isset( $_POST['skipped'] ) ? absint( $_POST['skipped'] ) : 0; // Input var ok.

		// If any images failed to smush, store count.
		if ( $failed > 0 ) {
			set_transient( 'wp-smush-dir-scan-failed-items', $failed, 60 * 5 ); // 5 minutes max.
		}

		if ( $skipped > 0 ) {
			set_transient( 'wp-smush-dir-scan-skipped-items', $skipped, 60 * 5 ); // 5 minutes max.
		}

		// Store optimized items count.
		set_transient( 'wp-smush-show-dir-scan-notice', $items, 60 * 5 ); // 5 minutes max.
		$this->scanner->reset_scan();
		wp_send_json_success();
	}

	/**
	 * Directory Smush: Cancel smush.
	 *
	 * @since 2.8.1
	 */
	public function directory_smush_cancel() {
		$this->scanner->reset_scan();
		wp_send_json_success();
	}

	/**
	 * Handles the ajax request for image optimisation in a folder
	 *
	 * @param int $id  Image ID.
	 */
	private function optimise_image( $id ) {
		global $wpdb;

		$error_msg = '';

		// No image ID.
		if ( $id < 1 ) {
			$error_msg = esc_html__( 'Incorrect image id', 'wp-smushit' );
			wp_send_json_error( $error_msg );
		}

		// Check smush limit for free users.
		if ( ! WP_Smush::is_pro() ) {
			// Free version bulk smush, check the transient counter value.
			$should_continue = Core::check_bulk_limit( false, 'dir_sent_count' );

			// Send a error for the limit.
			if ( ! $should_continue ) {
				wp_send_json_error(
					array(
						'error'    => 'dir_smush_limit_exceeded',
						'continue' => false,
					)
				);
			}
		}

		$scanned_images = $this->get_unsmushed_images();
		$image          = $this->get_image( $id, '', $scanned_images );

		if ( empty( $image ) ) {
			wp_send_json_success( array( 'skipped' => true ) );
		}

		$path = $image['path'];

		if ( false !== stripos( $path, 'phar://' ) ) {
			wp_send_json_error(
				array(
					'error' => esc_html_e( 'Potential Phar PHP Object Injection detected', 'wp-smushit' ),
					'image' => array(
						'id' => $id,
					),
				)
			);
		}

		// We have the image path, optimise.
		$results = WP_Smush::get_instance()->core()->mod->smush->do_smushit( $path );

		if ( is_wp_error( $results ) ) {
			/**
			 * Smush results.
			 *
			 * @var WP_Error $results
			 */
			$error_msg = $results->get_error_message();
		} elseif ( empty( $results['data'] ) ) {
			// If there are no stats.
			$error_msg = esc_html__( "Image couldn't be optimized", 'wp-smushit' );
		}

		if ( ! empty( $error_msg ) ) {
			// Store the error in DB. All good, Update the stats.
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$wpdb->prefix}smush_dir_images SET error=%s WHERE id=%d LIMIT 1",
					$error_msg,
					$id
				)
			); // Db call ok; no-cache ok.

			wp_send_json_error(
				array(
					'error' => $error_msg,
					'image' => array(
						'id' => $id,
					),
				)
			);
		}

		if ( ! $this->settings ) {
			$this->settings = Settings::get_instance();
		}

		// All good, Update the stats.
		$wpdb->query(
			$wpdb->prepare(
				"UPDATE {$wpdb->prefix}smush_dir_images SET error=NULL, image_size=%d, file_time=%d, lossy=%d, meta=%d WHERE id=%d LIMIT 1",
				$results['data']->after_size,
				@filectime( $path ), // Get file time.
				WP_Smush::is_pro() && $this->settings->get( 'lossy' ),
				$this->settings->get( 'strip_exif' ),
				$id
			)
		); // Db call ok; no-cache ok.

		// Update bulk limit transient.
		Core::update_smush_count( 'dir_sent_count' );
	}

	/**
	 * Create the Smush image table to store the paths of scanned images, and stats
	 */
	public function create_table() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		/**
		 * Table: wp_smush_dir_images
		 * Columns:
		 * id         -> Auto Increment ID
		 * path       -> Absolute path to the image file
		 * resize     -> Whether the image was resized or not
		 * lossy      -> Whether the image was super-smushed/lossy or not
		 * image_size -> Current image size post optimisation
		 * orig_size  -> Original image size before optimisation
		 * file_time  -> Unix time for the file creation, to match it against the current creation time,
		 *                  in order to confirm if it is optimised or not
		 * last_scan  -> Timestamp, Get images form last scan by latest timestamp
		 *                  are from latest scan only and not the whole list from db
		 * meta       -> For any future use
		 */
		$sql = "CREATE TABLE {$wpdb->base_prefix}smush_dir_images (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			path text NOT NULL,
			path_hash CHAR(32),
			resize varchar(55),
			lossy varchar(55),
			error varchar(55) DEFAULT NULL,
			image_size int(10) unsigned,
			orig_size int(10) unsigned,
			file_time int(10) unsigned,
			last_scan timestamp DEFAULT '0000-00-00 00:00:00',
			meta text,
			UNIQUE KEY id (id),
			UNIQUE KEY path_hash (path_hash),
			KEY image_size (image_size)
		) $charset_collate;";

		// Include the upgrade library to initialize a table.
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		// Set flag.
		self::$table_exist = true;
	}

	/**
	 * Get the image ids and path for last scanned images
	 *
	 * @return array Array of last scanned images containing image id and path
	 */
	public function get_scanned_images() {
		global $wpdb;

		$results = $wpdb->get_results( "SELECT id, path, orig_size FROM {$wpdb->prefix}smush_dir_images WHERE last_scan = (SELECT MAX(last_scan) FROM {$wpdb->prefix}smush_dir_images )  GROUP BY id ORDER BY id", ARRAY_A ); // Db call ok; no-cache ok.

		// Return image ids.
		if ( is_wp_error( $results ) ) {
			error_log( sprintf( 'WP Smush Query Error in %s at %s: %s', __FILE__, __LINE__, $results->get_error_message() ) );
			$results = array();
		}

		return $results;
	}

	/**
	 * Get only images that need compressing.
	 *
	 * @since 3.6.1
	 *
	 * @return array Array of images that require compression.
	 */
	public function get_unsmushed_images() {
		global $wpdb;

		$condition = 'image_size IS NULL';
		if ( WP_Smush::is_pro() && $this->settings->get( 'lossy' ) ) {
			$condition .= ' OR lossy <> 1';
		}

		if ( $this->settings->get( 'strip_exif' ) ) {
			$condition .= ' OR meta <> 1';
		}

		$results = $wpdb->get_results( "SELECT id, path, orig_size FROM {$wpdb->prefix}smush_dir_images WHERE {$condition} && last_scan = (SELECT MAX(last_scan) FROM {$wpdb->prefix}smush_dir_images )  GROUP BY id ORDER BY id", ARRAY_A ); // Db call ok; no-cache ok.

		// Return image ids.
		if ( is_wp_error( $results ) ) {
			error_log( sprintf( 'WP Smush Query Error in %s at %s: %s', __FILE__, __LINE__, $results->get_error_message() ) );
			$results = array();
		}

		return $results;
	}

	/**
	 * Get the paths and errors from last scan.
	 *
	 * @since 3.0
	 *
	 * @return array  Array of last scanned images
	 */
	public function get_image_errors() {
		global $wpdb;

		return $wpdb->get_results(
			"SELECT id, path, error
					FROM {$wpdb->prefix}smush_dir_images
					WHERE error IS NOT NULL
						AND last_scan = ( SELECT MAX(last_scan) FROM {$wpdb->prefix}smush_dir_images )
					LIMIT 20",
			ARRAY_A
		); // Db call ok; no-cache ok.
	}

	/**
	 * Return the number of errors.
	 *
	 * @since 3.0
	 *
	 * @return int
	 */
	public function get_image_errors_count() {
		global $wpdb;

		return (int) $wpdb->get_var(
			"SELECT COUNT(id)
					FROM {$wpdb->prefix}smush_dir_images
					WHERE error IS NOT NULL AND last_scan = ( SELECT MAX(last_scan) FROM {$wpdb->prefix}smush_dir_images )"
		); // Db call ok.
	}

	/**
	 * Check if the image file is media library file
	 *
	 * @param string $file_path  File path.
	 *
	 * @return bool
	 */
	private function is_media_library_file( $file_path ) {
		$upload_dir  = wp_upload_dir();
		$upload_path = $upload_dir['path'];

		// Get the base path of file.
		$base_dir = dirname( $file_path );
		if ( $base_dir === $upload_path ) {
			return true;
		}

		return false;
	}

	/**
	 * Return a directory/File list
	 *
	 * PHP Connector
	 */
	public function directory_list() {
		// Check For permission.
		if ( ! current_user_can( 'manage_options' ) || ! is_user_logged_in() ) {
			wp_send_json_error( __( 'Unauthorized', 'wp-smushit' ) );
		}

		// Verify nonce.
		check_ajax_referer( 'smush_get_dir_list', 'list_nonce' );

		$dir  = filter_input( INPUT_GET, 'dir', FILTER_SANITIZE_STRING );
		$tree = $this->get_directory_tree( $dir );

		if ( ! is_array( $tree ) ) {
			wp_send_json_error( __( 'Unauthorized', 'wp-smushit' ) );
		}

		wp_send_json( $tree );
	}

	/**
	 * Gets the directory tree data for the given path.
	 *
	 * @since 3.8.3
	 *
	 * @param string $dir Directory path.
	 * @return array|bool False on failure. Array with the data on success.
	 */
	private function get_directory_tree( $dir = null ) {
		// Get the root path for a main site or subsite.
		$root     = realpath( $this->get_root_path() );
		$post_dir = strlen( $dir ) >= 1 ? path_join( $root, $dir ) : $root . $dir;

		// If the final path doesn't contains the root path, bail out.
		if ( ! $root || false === $post_dir || 0 !== strpos( $post_dir, $root ) ) {
			return false;
		}

		$supported_image = array(
			'gif',
			'jpg',
			'jpeg',
			'png',
		);

		if ( file_exists( $post_dir ) && is_dir( $post_dir ) ) {
			$files = scandir( $post_dir );
			// Exclude hidden files.
			if ( ! empty( $files ) ) {
				$files = preg_grep( '/^([^.])/', $files );
			}
			$return_dir = substr( $post_dir, strlen( $root ) );

			natcasesort( $files );

			if ( count( $files ) !== 0 && ! $this->skip_dir( $post_dir ) ) {
				$tree = array();

				foreach ( $files as $file ) {
					$html_rel  = htmlentities( ltrim( path_join( $return_dir, $file ), '/' ) );
					$html_name = htmlentities( $file );
					$ext       = preg_replace( '/^.*\./', '', $file );

					$file_path = path_join( $post_dir, $file );
					if ( ! file_exists( $file_path ) || '.' === $file || '..' === $file ) {
						continue;
					}

					// Skip unsupported files and files that are already in the media library.
					if ( ! is_dir( $file_path ) && ( ! in_array( $ext, $supported_image, true ) || $this->is_media_library_file( $file_path ) ) ) {
						continue;
					}

					$skip_path = $this->skip_dir( $file_path );

					$tree[] = array(
						'title'        => $html_name,
						'key'          => $html_rel,
						'folder'       => is_dir( $file_path ),
						'lazy'         => ! $skip_path,
						'checkbox'     => true,
						'unselectable' => $skip_path, // Skip Uploads folder - Media Files.
					);
				}

				return $tree;
			}
		}

		return array();
	}

	/**
	 * Get root path of the installation.
	 *
	 * @return string Root path.
	 */
	public function get_root_path() {
		// If main site.
		if ( is_main_site() ) {
			/**
			 * Sometimes content directories may reside outside
			 * the installation sub directory. We need to make sure
			 * we are selecting the root directory, not installation
			 * directory.
			 *
			 * @see https://xnau.com/finding-the-wordpress-root-path-for-an-alternate-directory-structure/
			 * @see https://app.asana.com/0/14491813218786/487682361460247/f
			 */
			$content_path = explode( '/', wp_normalize_path( WP_CONTENT_DIR ) );
			// Get root path and explod.
			$root_path = explode( '/', get_home_path() );

			// Find the length of the shortest one.
			$end         = min( count( $content_path ), count( $root_path ) );
			$i           = 0;
			$common_path = array();
			// Add the component if they are the same in both paths.
			while ( $content_path[ $i ] === $root_path[ $i ] && $i < $end ) {
				$common_path[] = $content_path[ $i ];
				$i++;
			}

			return implode( '/', $common_path );
		}

		$up = wp_upload_dir();
		return $up['basedir'];
	}

	/**
	 * Checks whether the user-submitted paths are among our allowed ones.
	 *
	 * @since 3.8.3
	 *
	 * @param string $path_to_check User-submitted path.
	 * @return bool
	 */
	private function validate_path( $path_to_check ) {
		$is_valid = true;

		// Verify that every directory in the path is allowed.
		while ( $is_valid && dirname( $path_to_check ) !== $path_to_check ) {
			$path_contents = $this->get_directory_tree( $path_to_check );

			if ( empty( $path_contents ) ) {
				return false;
			}

			$is_valid = false;
			foreach ( $path_contents as $tree_data ) {
				if ( false !== strpos( $tree_data['key'], $path_to_check ) && ! $tree_data['unselectable'] ) {
					$is_valid = true;
					break;
				}
			}

			if ( ! $is_valid ) {
				$path_to_check = dirname( $path_to_check );
			} else {
				// Valid path, break out of the loop.
				break;
			}
		}

		return $is_valid;
	}

	/**
	 * Get the image list in a specified directory path.
	 *
	 * @since 2.8.1  Added support for selecting files.
	 *
	 * @param string|array $paths  Path where to look for images, or selected images.
	 *
	 * @throws \Exception Never, actually. Supposedly, when an invalid directory was selected.
	 * @return array
	 */
	private function get_image_list( $paths = '' ) {
		// Error with directory tree.
		if ( ! is_array( $paths ) ) {
			$this->send_error( __( 'There was a problem getting the selected directories', 'wp-smushit' ) );
		}

		$count     = 0;
		$images    = array();
		$values    = array();
		$timestamp = gmdate( 'Y-m-d H:i:s' );

		// Temporary increase the limit.
		wp_raise_memory_limit( 'image' );

		// Avoid checking already validated paths.
		$validated_dirs = array();

		// Iterate over all the selected items (can be either an image or directory).
		foreach ( $paths as $relative_path ) {

			// Make the path absolute.
			$path = trim( $this->get_root_path() . '/' . $relative_path );

			// Prevent phar deserialization vulnerability.
			if ( stripos( $path, 'phar://' ) !== false ) {
				continue;
			}

			/**
			 * Path is an image.
			 *
			 * @TODO: The is_dir() check fails directories with spaces.
			 */
			if ( ! is_dir( $path ) && ! $this->is_media_library_file( $path ) && ! strpos( $path, '.bak' ) ) {

				if ( ! $this->is_image( $path ) ) {
					continue;
				}

				// Image already added. Skip.
				if ( in_array( $path, $images, true ) ) {
					continue;
				}

				// Skip if the parent directory isn't allowed.
				if ( ! in_array( dirname( $relative_path ), $validated_dirs, true ) ) {
					if ( ! $this->validate_path( dirname( $relative_path ) ) ) {
						continue;
					}
					$validated_dirs[] = dirname( $relative_path );
				}

				$images[] = $path;
				$images[] = md5( $path );
				$images[] = @filesize( $path );  // Get the file size.
				$images[] = @filectime( $path ); // Get the file modification time.
				$images[] = $timestamp;
				$values[] = '(%s, %s, %d, %d, %s)';
				$count++;

				// Store the images in db at an interval of 5k.
				if ( $count >= 5000 ) {
					$count = 0;
					$this->store_images( $values, $images );
					$images = $values = array();
				}

				continue;
			}

			/**
			 * Path is a directory.
			 */
			$base_dir = realpath( rawurldecode( $path ) );

			if ( ! $base_dir ) {
				$this->send_error( __( 'Unauthorized', 'wp-smushit' ) );
			}

			// Skip if this directory isn't allowed.
			if ( ! in_array( $relative_path, $validated_dirs, true ) ) {
				if ( ! $this->validate_path( $relative_path ) ) {
					continue;
				}
				$validated_dirs[] = $relative_path;
			}

			// Directory Iterator, Exclude . and ..
			$filtered_dir = new Helpers\Iterator( new RecursiveDirectoryIterator( $base_dir ) );

			// File Iterator.
			$iterator = new RecursiveIteratorIterator( $filtered_dir, RecursiveIteratorIterator::CHILD_FIRST );

			foreach ( $iterator as $file ) {
				// Used in place of Skip Dots, For php 5.2 compatibility.
				if ( basename( $file ) === '..' || basename( $file ) === '.' ) {
					continue;
				}

				// Not a file. Skip.
				if ( ! $file->isFile() ) {
					continue;
				}

				$file_path = $file->getPathname();

				if ( $this->is_image( $file_path ) && ! $this->is_media_library_file( $file_path ) && strpos( $file, '.bak' ) === false ) {
					/** To be stored in DB, Part of code inspired from Ewwww Optimiser  */
					$images[] = $file_path;
					$images[] = md5( $file_path );
					$images[] = $file->getSize();
					$images[] = @filectime( $file_path ); // Get the file modification time.
					$images[] = $timestamp;
					$values[] = '(%s, %s, %d, %d, %s)';
					$count++;
				}

				// Store the images in db at an interval of 5k.
				if ( $count >= 5000 ) {
					$count = 0;
					$this->store_images( $values, $images );
					$images = $values = array();
				}
			}
		}

		if ( empty( $images ) || 0 === $count ) {
			return array();
		}

		// Update rest of the images.
		$this->store_images( $values, $images );

		// Get the image ids.
		return $this->get_scanned_images();
	}

	/**
	 * Write to the database.
	 *
	 * @since 2.8.1
	 *
	 * @param array $values  Values for query build.
	 * @param array $images  Array of images.
	 */
	private function store_images( $values, $images ) {
		global $wpdb;

		$query = $this->build_query( $values, $images );
		$wpdb->query( $query ); // Db call ok; no-cache ok.
	}

	/**
	 * Build and prepare query from the given values and image array.
	 *
	 * @param array $values  Values.
	 * @param array $images  Images.
	 *
	 * @return bool|string
	 */
	private function build_query( $values, $images ) {
		if ( empty( $images ) || empty( $values ) ) {
			return false;
		}

		global $wpdb;
		$values = implode( ',', $values );

		// Replace with image path and respective parameters.
		$query = "INSERT INTO {$wpdb->prefix}smush_dir_images (path, path_hash, orig_size, file_time, last_scan) VALUES $values ON DUPLICATE KEY UPDATE image_size = IF( file_time < VALUES(file_time), NULL, image_size ), file_time = IF( file_time < VALUES(file_time), VALUES(file_time), file_time ), last_scan = VALUES( last_scan )";
		$query = $wpdb->prepare( $query, $images ); // Db call ok; no-cache ok.

		return $query;
	}

	/**
	 * Sends a Ajax response with a message to be shown in a floating warning notice.
	 *
	 * @param string $message The message for the notice.
	 */
	private function send_error( $message ) {
		wp_send_json_error(
			array(
				'message' => sprintf( '<p>%s</p>', esc_html( $message ) ),
			)
		);
	}

	/**
	 * Handles Ajax request to obtain the Image list within a selected directory path
	 */
	public function image_list() {
		// Check For permission.
		if ( ! current_user_can( 'manage_options' ) ) {
			$this->send_error( __( 'Unauthorized', 'wp-smushit' ) );
		}

		// Verify nonce.
		check_ajax_referer( 'smush_get_image_list', 'image_list_nonce' );

		// Check if directory path is set or not.
		if ( empty( $_POST['smush_path'] ) ) { // Input var ok.
			$this->send_error( __( 'Empty Directory Path', 'wp-smushit' ) );
		}

		// FILTER_SANITIZE_URL is trimming the space if a folder contains space.
		$smush_path = filter_input( INPUT_POST, 'smush_path', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );

		try {
			// This will add the images to the database and get the file list.
			$files = $this->get_image_list( $smush_path );
		} catch ( \Exception $e ) {
			$this->send_error( $e->getMessage() );
		}

		// If files array is empty, send a message.
		if ( empty( $files ) ) {
			$this->send_error( __( 'We could not find any images in the selected directory.', 'wp-smushit' ) );
		}

		// Send response.
		wp_send_json_success( count( $files ) );
	}

	/**
	 * Check whether the given path is a image or not.
	 *
	 * Do not include backup files.
	 *
	 * @param string $path  Image path.
	 *
	 * @return bool
	 */
	private function is_image( $path ) {
		// Check if the path is valid.
		if ( ! file_exists( $path ) || ! $this->is_image_from_extension( $path ) ) {
			return false;
		}

		if ( false !== stripos( $path, 'phar://' ) ) {
			return false;
		}

		$a = @getimagesize( $path );

		// If a is not set.
		if ( ! $a || empty( $a ) ) {
			return false;
		}

		$image_type = $a[2];

		if ( in_array( $image_type, array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Obtain the path to the admin directory.
	 *
	 * @return string
	 *
	 * Thanks @andrezrv (Github)
	 * TODO: this does not properly get the admin path in Bedrock
	 */
	private function get_admin_path() {
		// Replace the site base URL with the absolute path to its installation directory.
		$admin_path = rtrim( str_replace( get_bloginfo( 'url' ) . '/', ABSPATH, get_admin_url() ), '/' );

		// Make it filterable, so other plugins can hook into it.
		$admin_path = apply_filters( 'wp_smush_get_admin_path', $admin_path );

		return $admin_path;
	}

	/**
	 * Check if the given file path is a supported image format
	 *
	 * @param string $path  File path.
	 *
	 * @return bool Whether a image or not
	 */
	private function is_image_from_extension( $path ) {
		$supported_image = array( 'gif', 'jpg', 'jpeg', 'png' );
		$ext             = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) ); // Using strtolower to overcome case sensitive.

		if ( in_array( $ext, $supported_image, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Excludes the Media Upload Directory ( Checks for Year and Month ).
	 *
	 * Borrowed from Shortpixel - (y)*
	 * TODO: Add a option to filter images if User have turned off the Year and Month Organize option
	 *
	 * @param string $path  Path.
	 *
	 * @return bool
	 */
	public function skip_dir( $path ) {
		// Admin directory path.
		$admin_dir = $this->get_admin_path();

		// Includes directory path.
		$includes_dir = ABSPATH . WPINC;

		// Upload directory.
		$upload_dir = wp_upload_dir();
		$base_dir   = $upload_dir['basedir'];

		$skip = false;

		// Don't skip the whole sites folder but only skip media upload year folder for multi-sites.
		if ( false !== strpos( $path, $base_dir . '/sites' ) ) {
			// If matches the current upload path contains one of the year sub folders of the media library.
			$path_arr = explode( '/', str_replace( $base_dir.'/sites' . '/', '', $path ) );
			if ( is_array( $path_arr ) && count( $path_arr ) > 1
			     && is_numeric( $path_arr[1] ) && $path_arr[1] > 1900 && $path_arr[1] < 2100 // Contains the year sub folder.
			) {
				$skip = true;
			}
		} elseif ( false !== strpos( $path, $base_dir ) ) {
			// If matches the current upload path contains one of the year sub folders of the media library.
			$path_arr = explode( '/', str_replace( $base_dir . '/', '', $path ) );
			if ( count( $path_arr ) >= 1
				&& is_numeric( $path_arr[0] ) && $path_arr[0] > 1900 && $path_arr[0] < 2100 // Contains the year sub folder.
				&& ( 1 === count( $path_arr ) // If there is another sub folder then it's the month sub folder.
				|| ( is_numeric( $path_arr[1] ) && $path_arr[1] > 0 && $path_arr[1] < 13 ) )
			) {
				$skip = true;
			}
		} elseif ( ( false !== strpos( $path, $admin_dir ) ) || false !== strpos( $path, $includes_dir ) ) {
			$skip = true;
		}

		// Can be used to skip/include folders matching a specific directory path.
		$skip = apply_filters( 'wp_smush_skip_folder', $skip, $path );

		return $skip;
	}

	/**
	 * Search for image from given image id or path.
	 *
	 * @param string $id      Image id to search for.
	 * @param string $path    Image path to search for.
	 * @param array  $images  Image array to search within.
	 *
	 * @return array  Image array or empty array.
	 */
	private function get_image( $id, $path, $images ) {
		foreach ( $images as $key => $val ) {
			if ( ! empty( $id ) && (int) $val['id'] === $id ) {
				return $images[ $key ];
			} elseif ( ! empty( $path ) && $val['path'] === $path ) {
				return $images[ $key ];
			}
		}

		return array();
	}

	/**
	 * Fetch all the optimised image, calculate stats.
	 *
	 * @param bool $force_update Should force update or not.
	 *
	 * @return array Total stats.
	 */
	public function total_stats( $force_update = false ) {
		// If not forced to update.
		if ( ! $force_update ) {
			// Get stats from cache.
			$total_stats = wp_cache_get( 'wp-smush-dir_total_stats', 'wp-smush' );
			// If we have already calculated the stats and found in cache, return it.
			if ( false !== $total_stats ) {
				return $total_stats;
			}
		}

		global $wpdb;

		$offset    = 0;
		$optimised = 0;
		$limit     = 1000;
		$images    = array();
		$continue  = true;

		while ( $continue ) {
			$results = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT path, image_size, orig_size FROM {$wpdb->prefix}smush_dir_images WHERE image_size IS NOT NULL ORDER BY `id` LIMIT %d, %d",
					$offset,
					$limit
				),
				ARRAY_A
			); // Db call ok; no-cache ok.

			if ( ! $results ) {
				break;
			}

			$images  = array_merge( $images, $results );
			$offset += $limit;
		}

		// Iterate over stats, return count and savings.
		if ( ! empty( $images ) ) {
			// Init the stats array.
			$this->stats = array(
				'path'       => '',
				'image_size' => 0,
				'orig_size'  => 0,
			);

			foreach ( $images as $im ) {
				foreach ( $im as $key => $val ) {
					if ( 'path' === $key ) {
						$this->optimised_images[ $val ] = $im;
						continue;
					}
					$this->stats[ $key ] += (int) $val;
				}
				$optimised++;
			}
		}

		// Get the savings in bytes and percent.
		if ( ! empty( $this->stats ) && ! empty( $this->stats['orig_size'] ) ) {
			$this->stats['bytes']   = ( $this->stats['orig_size'] > $this->stats['image_size'] ) ? $this->stats['orig_size'] - $this->stats['image_size'] : 0;
			$this->stats['percent'] = number_format_i18n( ( ( $this->stats['bytes'] / $this->stats['orig_size'] ) * 100 ), 1 );
			// Convert to human readable form.
			$this->stats['human'] = size_format( $this->stats['bytes'], 1 );
		}

		$this->stats['total']     = count( $images );
		$this->stats['optimised'] = $optimised;

		// Set stats in cache.
		wp_cache_set( 'wp-smush-dir_total_stats', $this->stats, 'wp-smush' );

		return $this->stats;
	}

	/**
	 * Returns the number of images scanned and optimised
	 *
	 * @return array
	 */
	private function last_scan_stats() {
		global $wpdb;
		$results = $wpdb->get_results( "SELECT id, image_size, orig_size FROM {$wpdb->prefix}smush_dir_images WHERE last_scan = (SELECT MAX(last_scan) FROM {$wpdb->prefix}smush_dir_images ) GROUP BY id", ARRAY_A ); // Db call ok; no-cache ok.
		$total   = count( $results );
		$smushed = 0;
		$stats   = array(
			'image_size' => 0,
			'orig_size'  => 0,
		);

		// Get the Smushed count, and stats sum.
		foreach ( $results as $image ) {
			if ( ! is_null( $image['image_size'] ) ) {
				$smushed ++;
			}
			// Summation of stats.
			foreach ( $image as $k => $v ) {
				if ( 'id' === $k ) {
					continue;
				}
				$stats[ $k ] += $v;
			}
		}

		// Stats.
		$stats['total']   = $total;
		$stats['smushed'] = $smushed;

		return $stats;
	}

	/**
	 * Combine the stats from Directory Smush and Media Library Smush.
	 *
	 * @param array $stats  Directory Smush stats.
	 *
	 * @return array Combined array of stats.
	 */
	public function combine_stats( $stats ) {
		if ( empty( $stats ) || empty( $stats['percent'] ) || empty( $stats['bytes'] ) ) {
			return array();
		}

		$dasharray = 125.663706144;

		$core = WP_Smush::get_instance()->core();

		// Initialize global stats.
		$core->setup_global_stats();

		// Get the total/Smushed attachment count.
		$total_attachments = $core->total_count + $stats['total'];
		$total_images      = $core->stats['total_images'] + $stats['total'];

		$smushed     = $core->smushed_count + $stats['optimised'];
		$savings     = ! empty( $core->stats ) ? $core->stats['bytes'] + $stats['bytes'] : $stats['bytes'];
		$size_before = ! empty( $core->stats ) ? $core->stats['size_before'] + $stats['orig_size'] : $stats['orig_size'];
		$percent     = $size_before > 0 ? ( $savings / $size_before ) * 100 : 0;

		// Store the stats in array.
		return array(
			'total_count'   => $total_attachments,
			'smushed_count' => $smushed,
			'savings'       => size_format( $savings ),
			'percent'       => round( $percent, 1 ),
			'image_count'   => $total_images,
			'dash_offset'   => $total_attachments > 0 ? $dasharray - ( $dasharray * ( $smushed / $total_attachments ) ) : $dasharray,
			/* translators: %s: total number of images */
			'tooltip_text'  => ! empty( $total_images ) ? sprintf( __( "You've smushed %d images in total.", 'wp-smushit' ), $total_images ) : '',
		);
	}

	/**
	 * Check and create dir smush table if required.
	 *
	 * @since 2.9.0
	 */
	public function check_table() {
		// Get current screen.
		$current_screen = get_current_screen();

		// Only run on required pages.
		if ( ! empty( $current_screen ) && false === strpos( $current_screen->id, 'page_smush' ) ) {
			return;
		}

		// Create custom table for directory smush.
		if ( ! self::table_exist() ) {
			Installer::directory_smush_table();
		}
	}

	/**
	 * Check if required directory smush table exist.
	 *
	 * @param bool $force Should force check?.
	 *
	 * @since 2.9.0
	 *
	 * @return bool
	 */
	public static function table_exist( $force = false ) {
		global $wpdb;

		// If not forced, try to get from cache.
		if ( ! $force && isset( self::$table_exist ) ) {
			return self::$table_exist;
		}

		// If not already checked, check.
		$table_exist = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $wpdb->base_prefix . 'smush_dir_images' ) ) ); // Db call ok; no-cache ok.

		self::$table_exist = $table_exist ? true : false;

		return self::$table_exist;
	}

	/**
	 * Remove directory smush from tabs.
	 *
	 * If not in main site, do not show directory smush.
	 *
	 * @param array $tabs Tabs.
	 *
	 * @return array
	 */
	public function remove_directory_tab( $tabs ) {
		if ( isset( $tabs['directory'] ) ) {
			unset( $tabs['directory'] );
		}

		return $tabs;
	}

	/**
	 * Display a admin notice on smush screen if the custom table wasn't created
	 */
	public function show_table_error() {
		if ( ! self::table_exist() ) { // Display a notice.
			?>
		<div class="sui-notice sui-notice-warning">
			<div class="sui-notice-content">
				<div class="sui-notice-message">
					<i class="sui-notice-icon sui-icon-info" aria-hidden="true"></i>
					<p>
						<?php esc_html_e( 'Directory smushing requires custom tables and it seems there was an error creating tables. For help, please contact our team on the support forums.', 'wp-smushit' ); ?>
					</p>
				</div>
			</div>
		</div>
			<?php
		}
	}

}
