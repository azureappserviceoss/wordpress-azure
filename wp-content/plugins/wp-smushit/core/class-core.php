<?php
/**
 * Core class: Core class.
 *
 * @since 2.9.0
 * @package Smush\Core
 */

namespace Smush\Core;

use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Core
 */
class Core extends Stats {

	/**
	 * S3 module
	 *
	 * @var Integrations\S3
	 */
	public $s3;

	/**
	 * NextGen module.
	 *
	 * @var Integrations\Nextgen
	 */
	public $nextgen;

	/**
	 * Modules array.
	 *
	 * @var Modules
	 */
	public $mod;

	/**
	 * Allowed mime types of image.
	 *
	 * @var array $mime_types
	 */
	public static $mime_types = array(
		'image/jpg',
		'image/jpeg',
		'image/x-citrix-jpeg',
		'image/gif',
		'image/png',
		'image/x-png',
	);

	/**
	 * List of external pages where smush needs to be loaded.
	 *
	 * @var array $pages
	 */
	public static $external_pages = array(
		'nggallery-manage-images',
		'gallery_page_nggallery-manage-gallery',
		'gallery_page_wp-smush-nextgen-bulk',
		'nextgen-gallery_page_nggallery-manage-gallery', // Different since NextGen 3.3.6.
		'nextgen-gallery_page_wp-smush-nextgen-bulk', // Different since NextGen 3.3.6.
		'post',
		'post-new',
		'page',
		'edit-page',
		'upload',
	);

	/**
	 * Attachment IDs.
	 *
	 * @var array $attachments
	 */
	public $attachments = array();

	/**
	 * Attachment IDs which are smushed.
	 *
	 * @var array $smushed_attachments
	 */
	public $smushed_attachments = array();

	/**
	 * Unsmushed image IDs.
	 *
	 * @var array $unsmushed_attachments
	 */
	public $unsmushed_attachments = array();

	/**
	 * Skipped attachment IDs.
	 *
	 * @since 3.0
	 *
	 * @var array $skipped_attachments
	 */
	public $skipped_attachments = array();

	/**
	 * Smushed attachments out of total attachments.
	 *
	 * @var int $smushed_count
	 */
	public $smushed_count = 0;

	/**
	 * Smushed attachments out of total attachments.
	 *
	 * @var int $remaining_count
	 */
	public $remaining_count = 0;

	/**
	 * Images with errors that have been skipped from bulk smushing.
	 *
	 * @since 3.0
	 * @var int $skipped_count
	 */
	public $skipped_count = 0;

	/**
	 * Super Smushed attachments count.
	 *
	 * @var int $super_smushed
	 */
	public $super_smushed = 0;

	/**
	 * Total count of attachments for smushing.
	 *
	 * @var int $total_count
	 */
	public $total_count = 0;

	/**
	 * Image ids that needs to be resmushed.
	 *
	 * @var array $resmush_ids
	 */
	public $resmush_ids = array();

	/**
	 * Limit for allowed number of images per bulk request.
	 *
	 * This is enforced at api level too.
	 *
	 * @var int $max_free_bulk
	 */
	public static $max_free_bulk = 50;

	/**
	 * Initialize modules.
	 *
	 * @since 2.9.0
	 */
	protected function init() {
		$this->mod = new Modules();

		// Enqueue scripts and initialize variables.
		add_action( 'admin_init', array( $this, 'init_settings' ) );

		// Load integrations.
		add_action( 'init', array( $this, 'load_integrations' ) );

		// Big image size threshold (WordPress 5.3+).
		add_filter( 'big_image_size_threshold', array( $this, 'big_image_size_threshold' ), 10 );

		/**
		 * Load NextGen Gallery, instantiate the Async class. if hooked too late or early, auto Smush doesn't
		 * work, also load after settings have been saved on init action.
		 */
		add_action( 'plugins_loaded', array( $this, 'load_libs' ), 90 );
	}

	/**
	 * Load integrations class.
	 *
	 * @since 2.8.0
	 */
	public function load_integrations() {
		new Integrations\Common();
	}

	/**
	 * Load plugin modules.
	 */
	public function load_libs() {
		$this->wp_smush_async();

		if ( is_admin() ) {
			$this->s3      = new Integrations\S3();
			$this->nextgen = new Integrations\Nextgen();
		}

		new Integrations\Gutenberg();
		new Integrations\Composer();
		new Integrations\Envira( $this->mod->cdn );
		new Integrations\Avada( $this->mod->cdn );
	}

	/**
	 * Initialize the Smush Async class.
	 */
	private function wp_smush_async() {
		// Don't load the Async task, if user not logged in or not in backend.
		if ( ! is_admin() || ! is_user_logged_in() ) {
			return;
		}

		// Check if Async is disabled.
		if ( defined( 'WP_SMUSH_ASYNC' ) && ! WP_SMUSH_ASYNC ) {
			return;
		}

		// Instantiate class.
		new Modules\Async\Async();
		new Modules\Async\Editor();
	}

	/**
	 * Init settings.
	 */
	public function init_settings() {
		// Initialize Image dimensions.
		$this->mod->smush->image_sizes = $this->image_dimensions();
	}

	/**
	 * Localize translations.
	 */
	public function localize() {
		global $current_screen;

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
			'nonce'                   => wp_create_nonce( 'wp-smush-ajax' ),
			'webp_nonce'              => wp_create_nonce( 'wp-smush-webp-nonce' ),
			'settingsUpdated'         => esc_html__( 'Your settings have been updated', 'wp-smushit' ),
			'resmush'                 => esc_html__( 'Super-Smush', 'wp-smushit' ),
			'smush_now'               => esc_html__( 'Smush Now', 'wp-smushit' ),
			'error_in_bulk'           => $error_in_bulk,
			'all_resmushed'           => esc_html__( 'All images are fully optimized.', 'wp-smushit' ),
			'restore'                 => esc_html__( 'Restoring image...', 'wp-smushit' ),
			'smushing'                => esc_html__( 'Smushing image...', 'wp-smushit' ),
			'membership_valid'        => esc_html__( 'We successfully verified your membership, all the Pro features should work completely. ', 'wp-smushit' ),
			'membership_invalid'      => esc_html__( "Your membership couldn't be verified.", 'wp-smushit' ),
			'missing_path'            => esc_html__( 'Missing file path.', 'wp-smushit' ),
			// Used by Directory Smush.
			'unfinished_smush_single' => esc_html__( 'image could not be smushed.', 'wp-smushit' ),
			'unfinished_smush'        => esc_html__( 'images could not be smushed.', 'wp-smushit' ),
			'already_optimised'       => esc_html__( 'Already Optimized', 'wp-smushit' ),
			'ajax_error'              => esc_html__( 'Ajax Error', 'wp-smushit' ),
			'generic_ajax_error'      => esc_html__( 'Something went wrong with the request. Please reload the page and try again.', 'wp-smushit' ),
			'all_done'                => esc_html__( 'All Done!', 'wp-smushit' ),
			'sync_stats'              => esc_html__( 'Give us a moment while we sync the stats.', 'wp-smushit' ),
			// Progress bar text.
			'progress_smushed'        => esc_html__( 'images optimized', 'wp-smushit' ),
			'bulk_resume'             => esc_html__( 'Resume scan', 'wp-smushit' ),
			'bulk_stop'               => esc_html__( 'Stop current bulk smush process.', 'wp-smushit' ),
			// Errors.
			'error_ignore'            => esc_html__( 'Ignore this image from bulk smushing', 'wp-smushit' ),
			// Ignore text.
			'ignored'                 => esc_html__( 'Ignored from auto-smush', 'wp-smushit' ),
			'not_processed'           => esc_html__( 'Not processed', 'wp-smushit' ),
			// Notices.
			'noticeDismiss'           => esc_html__( 'Dismiss', 'wp-smushit' ),
			'noticeDismissTooltip'    => esc_html__( 'Dismiss notice', 'wp-smushit' ),
			'tutorialsRemoved'        => sprintf( /* translators: %1$s - opening a tag, %2$s - closing a tag */
				esc_html__( 'The widget has been removed. Smush tutorials can still be found in the %1$sTutorials tab%2$s any time.', 'wp-smushit' ),
				'<a href=' . esc_url( menu_page_url( 'smush-tutorials', false ) ) . '>',
				'</a>'
			),
			// URLs.
			'smush_url'               => network_admin_url( 'admin.php?page=smush' ),
			'directory_url'           => network_admin_url( 'admin.php?page=smush-directory' ),
			'localWebpURL'            => network_admin_url( 'admin.php?page=smush-webp' ),
		);

		wp_localize_script( $handle, 'wp_smush_msgs', $wp_smush_msgs );

		// Load the stats on selected screens only.
		if ( false !== strpos( $current_screen->id, 'page_smush' ) ) {
			// Get resmush list, If we have a resmush list already, localize those IDs.
			$resmush_ids = get_option( 'wp-smush-resmush-list' );
			if ( $resmush_ids ) {
				// Get the attachments, and get lossless count.
				$this->resmush_ids = $resmush_ids;
			}

			if ( ! defined( 'WP_SMUSH_DISABLE_STATS' ) || ! WP_SMUSH_DISABLE_STATS ) {
				// Setup all the stats.
				$this->setup_global_stats( true );
			}

			// Localize smushit_IDs variable, if there are fix number of IDs.
			$this->unsmushed_attachments = ! empty( $_REQUEST['ids'] ) ? array_map( 'intval', explode( ',', $_REQUEST['ids'] ) ) : array();

			if ( empty( $this->unsmushed_attachments ) ) {
				// Get attachments if all the images are not smushed.
				$this->unsmushed_attachments = $this->remaining_count > 0 ? $this->get_unsmushed_attachments() : array();
				$this->unsmushed_attachments = ! empty( $this->unsmushed_attachments ) && is_array( $this->unsmushed_attachments ) ? array_values( $this->unsmushed_attachments ) : $this->unsmushed_attachments;
			}

			// Array of all smushed, unsmushed and lossless IDs.
			$data = array(
				'count_supersmushed' => $this->super_smushed,
				'count_smushed'      => $this->smushed_count,
				'count_total'        => $this->total_count - $this->skipped_count,
				'count_images'       => $this->stats['total_images'],
				'count_resize'       => $this->stats['resize_count'],
				'unsmushed'          => $this->unsmushed_attachments,
				'resmush'            => $this->resmush_ids,
				'size_before'        => $this->stats['size_before'],
				'size_after'         => $this->stats['size_after'],
				'savings_bytes'      => $this->stats['bytes'],
				'savings_resize'     => $this->stats['resize_savings'],
				'savings_conversion' => $this->stats['conversion_savings'],
				'savings_dir_smush'  => $this->dir_stats,
			);
		} else {
			$data = array(
				'count_supersmushed' => '',
				'count_smushed'      => '',
				'count_total'        => '',
				'count_images'       => '',
				'unsmushed'          => '',
				'resmush'            => '',
				'savings_bytes'      => '',
				'savings_resize'     => '',
				'savings_conversion' => '',
				'savings_supersmush' => '',
				'pro_savings'        => '',
			);
		}

		// Check if scanner class is available.
		$scanner_ready = isset( $this->mod->dir->scanner );

		$data['dir_smush'] = array(
			'currentScanStep' => $scanner_ready ? $this->mod->dir->scanner->get_current_scan_step() : 0,
			'totalSteps'      => $scanner_ready ? $this->mod->dir->scanner->get_scan_steps() : 0,
		);

		$data['resize_sizes'] = $this->get_max_image_dimensions();

		// Convert it into ms.
		$data['timeout'] = WP_SMUSH_TIMEOUT * 1000;

		wp_localize_script( $handle, 'wp_smushit_data', $data );
	}

	/**
	 * Check bulk sent count, whether to allow further smushing or not
	 *
	 * @param bool   $reset  To hard reset the transient.
	 * @param string $key    Transient Key - bulk_sent_count/dir_sent_count.
	 *
	 * @return bool
	 */
	public static function check_bulk_limit( $reset = false, $key = 'bulk_sent_count' ) {
		$transient_name = 'wp-smush-' . $key;

		// If we JUST need to reset the transient.
		if ( $reset ) {
			set_transient( $transient_name, 0, 60 );
			return;
		}

		$bulk_sent_count = (int) get_transient( $transient_name );

		// Check if bulk smush limit is less than limit.
		if ( ! $bulk_sent_count || $bulk_sent_count < self::$max_free_bulk ) {
			$continue = true;
		} elseif ( $bulk_sent_count === self::$max_free_bulk ) {
			// If user has reached the limit, reset the transient.
			$continue = false;
			$reset    = true;
		} else {
			$continue = false;
		}

		// If we need to reset the transient.
		if ( $reset ) {
			set_transient( $transient_name, 0, 60 );
		}

		return $continue;
	}

	/**
	 * Get registered image sizes with dimension
	 *
	 * @return array
	 */
	public function image_dimensions() {
		// Get from cache if available to avoid duplicate looping.
		$sizes = wp_cache_get( 'get_image_sizes', 'smush_image_sizes' );
		if ( $sizes ) {
			return $sizes;
		}

		global $_wp_additional_image_sizes;
		$additional_sizes = get_intermediate_image_sizes();
		$sizes            = array();

		if ( empty( $additional_sizes ) ) {
			return $sizes;
		}

		// Create the full array with sizes and crop info.
		foreach ( $additional_sizes as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ), true ) ) {
				$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
				$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
				$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}

		// Medium Large.
		if ( ! isset( $sizes['medium_large'] ) || empty( $sizes['medium_large'] ) ) {
			$width  = (int) get_option( 'medium_large_size_w' );
			$height = (int) get_option( 'medium_large_size_h' );

			$sizes['medium_large'] = array(
				'width'  => $width,
				'height' => $height,
			);
		}

		// Set cache to avoid this loop next time.
		wp_cache_set( 'get_image_sizes', $sizes, 'smush_image_sizes' );

		return $sizes;
	}

	/**
	 * Get the Maximum Width and Height settings for WrodPress
	 *
	 * @return array, Array of Max. Width and Height for image.
	 */
	public function get_max_image_dimensions() {
		global $_wp_additional_image_sizes;

		$width  = 0;
		$height = 0;
		$limit  = 9999; // Post-thumbnail.

		$image_sizes = get_intermediate_image_sizes();

		// If image sizes are filtered and no image size list is returned.
		if ( empty( $image_sizes ) ) {
			return array(
				'width'  => $width,
				'height' => $height,
			);
		}

		// Create the full array with sizes and crop info.
		foreach ( $image_sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
				$size_width  = get_option( "{$size}_size_w" );
				$size_height = get_option( "{$size}_size_h" );
			} elseif ( isset( $_wp_additional_image_sizes[ $size ] ) ) {
				$size_width  = $_wp_additional_image_sizes[ $size ]['width'];
				$size_height = $_wp_additional_image_sizes[ $size ]['height'];
			}

			// Skip if no width and height.
			if ( ! isset( $size_width, $size_height ) ) {
				continue;
			}

			// If within te limit, check for a max value.
			if ( $size_width <= $limit ) {
				$width = max( $width, $size_width );
			}

			if ( $size_height <= $limit ) {
				$height = max( $height, $size_height );
			}
		}

		return array(
			'width'  => $width,
			'height' => $height,
		);
	}

	/**
	 * Update the image smushed count in transient
	 *
	 * @param string $key  Database key.
	 */
	public static function update_smush_count( $key = 'bulk_sent_count' ) {
		$transient_name = 'wp-smush-' . $key;

		$bulk_sent_count = get_transient( $transient_name );

		// If bulk sent count is not set.
		if ( false === $bulk_sent_count ) {
			// Start transient at 0.
			set_transient( $transient_name, 1, 200 );
		} elseif ( $bulk_sent_count < self::$max_free_bulk ) {
			// If lte $this->max_free_bulk images are sent, increment.
			set_transient( $transient_name, $bulk_sent_count + 1, 200 );
		}
	}

	/**
	 * Set the big image threshold.
	 *
	 * @since 3.3.2
	 *
	 * @param int $threshold  The threshold value in pixels. Default 2560.
	 *
	 * @return int|bool  New threshold. False if scaling is disabled.
	 */
	public function big_image_size_threshold( $threshold ) {
		if ( Settings::get_instance()->get( 'no_scale' ) ) {
			return false;
		}

		if ( ! Settings::get_instance()->get( 'resize' ) ) {
			return $threshold;
		}

		$resize_sizes = Settings::get_instance()->get_setting( 'wp-smush-resize_sizes' );
		if ( ! $resize_sizes || ! is_array( $resize_sizes ) ) {
			return $threshold;
		}

		return $resize_sizes['width'] > $resize_sizes['height'] ? $resize_sizes['width'] : $resize_sizes['height'];
	}
}
