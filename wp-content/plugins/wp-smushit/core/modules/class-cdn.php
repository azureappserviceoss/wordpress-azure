<?php
/**
 * CDN class: CDN
 *
 * @package Smush\Core\Modules
 * @version 3.0
 */

namespace Smush\Core\Modules;

use stdClass;
use WPMUDEV_Dashboard;
use WP_Error;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class CDN
 */
class CDN extends Abstract_Module {

	/**
	 * Smush CDN base url.
	 *
	 * @var null|string
	 */
	private $cdn_base = null;

	/**
	 * Flag to check if CDN is active.
	 *
	 * @var bool
	 */
	private $cdn_active = false;

	/**
	 * CDN status.
	 *
	 * @var stdClass $status
	 */
	private $status;

	/**
	 * Site URL.
	 *
	 * @since 3.8.0
	 * @var string
	 */
	private $site_url;

	/**
	 * Home URL.
	 *
	 * @since 3.8.0
	 * @var string
	 */
	private $home_url;

	/**
	 * Supported file extensions.
	 *
	 * @var array $supported_extensions
	 */
	private $supported_extensions = array(
		'gif',
		'jpg',
		'jpeg',
		'png',
		'webp',
	);

	/**
	 * Page parser.
	 *
	 * @since 3.2.2
	 * @var Helpers\Parser $parser
	 */
	protected $parser;

	/**
	 * CDN constructor.
	 *
	 * @since 3.2.2
	 * @param Helpers\Parser $parser  Page parser instance.
	 */
	public function __construct( Helpers\Parser $parser ) {
		$this->parser = $parser;
		parent::__construct();
	}

	/**
	 * CDN constructor.
	 */
	public function init() {
		/**
		 * Settings.
		 */
		// Filters the setting variable to add module setting title and description.
		add_filter( 'wp_smush_settings', array( $this, 'register' ) );

		// Add settings descriptions to the meta box.
		add_action( 'smush_setting_column_right_inside', array( $this, 'settings_desc' ), 10, 2 );

		// Cron task to update CDN stats.
		add_action( 'smush_update_cdn_stats', array( $this, 'update_stats' ) );

		// Set auto resize flag.
		$this->init_flags();

		/**
		 * Main functionality.
		 */
		if ( ! $this->settings->get( 'cdn' ) || ! $this->cdn_active ) {
			return;
		}

		// Set Smush API config.
		add_action( 'init', array( $this, 'set_cdn_url' ) );

		// Only do stuff on the frontend.
		if ( is_admin() ) {
			// Verify the cron task to update stats is configured.
			$this->schedule_cron();
			return;
		}

		// We do this to save extra checks when we load images later on in code.
		$this->site_url = get_site_url();

		if ( is_multisite() && ! is_subdomain_install() ) {
			$this->home_url = get_home_url( get_current_site()->id );
		} else {
			$this->home_url = get_home_url();
		}

		$this->init_parser();

		// Add cdn url to dns prefetch.
		add_filter( 'wp_resource_hints', array( $this, 'dns_prefetch' ), 99, 2 );

		$priority = defined( 'WP_SMUSH_CDN_DELAY_SRCSET' ) && WP_SMUSH_CDN_DELAY_SRCSET ? 1000 : 99;
		// Update responsive image srcset and sizes if required.
		add_filter( 'wp_calculate_image_srcset', array( $this, 'update_image_srcset' ), $priority, 5 );
		if ( $this->settings->get( 'auto_resize' ) ) {
			add_filter( 'wp_calculate_image_sizes', array( $this, 'update_image_sizes' ), 1, 2 );
		}
		// Add resizing arguments to image src.
		add_filter( 'smush_image_cdn_args', array( $this, 'update_cdn_image_src_args' ), 99, 3 );

		// Add REST API integration.
		add_filter( 'rest_pre_echo_response', array( $this, 'filter_rest_api_response' ) );
	}

	/**************************************
	 *
	 * PUBLIC METHODS SETTINGS & UI
	 */

	/**
	 * Get CDN status.
	 *
	 * @since 3.0
	 */
	public function get_status() {
		return $this->cdn_active && $this->settings->get( 'cdn' );
	}

	/**
	 * Get the CDN status.
	 *
	 * @return string  Possible return values: disabled/enabled, activating, overcap/upgrade.
	 *
	 * @since 3.2.1
	 */
	public function status() {
		if ( ! $this->cdn_active || ! $this->settings->get( 'cdn' ) ) {
			return 'disabled';
		}

		$cdn = $this->settings->get_setting( 'wp-smush-cdn_status' );

		if ( ! $cdn ) {
			return 'disabled';
		}

		if ( isset( $cdn->cdn_enabling ) && $cdn->cdn_enabling ) {
			return 'activating';
		}

		$plan      = isset( $cdn->bandwidth_plan ) ? $cdn->bandwidth_plan : 10;
		$bandwidth = isset( $cdn->bandwidth ) ? $cdn->bandwidth : 0;

		$percentage = round( 100 * $bandwidth / 1024 / 1024 / 1024 / $plan );

		if ( $percentage > 100 || 100 === (int) $percentage ) {
			return 'overcap';
		} elseif ( 90 <= (int) $percentage ) {
			return 'upgrade';
		}

		return 'enabled';
	}

	/**
	 * Add settings to settings array.
	 *
	 * @since 3.0
	 *
	 * @param array $settings  Current settings array.
	 *
	 * @return array
	 */
	public function register( $settings ) {
		return array_merge(
			$settings,
			array(
				'background_images' => array(
					'label'       => __( 'Serve background images from the CDN', 'wp-smushit' ),
					'short_label' => __( 'Background Images', 'wp-smushit' ),
					'desc'        => __( 'Where possible we will serve background images declared with CSS directly from the CDN.', 'wp-smushit' ),
				),
				'auto_resize'       => array(
					'label'       => __( 'Enable automatic resizing of my images', 'wp-smushit' ),
					'short_label' => __( 'Automatic Resizing', 'wp-smushit' ),
					'desc'        => __( 'If your images don’t match their containers, we’ll automatically serve a correctly sized image.', 'wp-smushit' ),
				),
				'webp'              => array(
					'label'       => __( 'Enable WebP conversion', 'wp-smushit' ),
					'short_label' => __( 'WebP Conversion', 'wp-smushit' ),
					'desc'        => __( 'Smush can automatically convert and serve your images as WebP from the WPMU DEV CDN to compatible browsers.', 'wp-smushit' ),
				),
				'rest_api_support'  => array(
					'label'       => __( 'Enable REST API support', 'wp-smushit' ),
					'short_label' => __( 'REST API', 'wp-smushit' ),
					'desc'        => __( 'Smush can automatically replace image URLs when fetched via REST API endpoints.', 'wp-smushit' ),
				),
			)
		);
	}

	/**
	 * Show additional descriptions for settings.
	 *
	 * @since 3.0
	 *
	 * @param string $setting_key Setting key.
	 */
	public function settings_desc( $setting_key = '' ) {
		if ( empty( $setting_key ) || ! in_array( $setting_key, $this->settings->get_cdn_fields(), true ) ) {
			return;
		}
		?>
		<span class="sui-description sui-toggle-description" id="<?php echo esc_attr( 'wp-smush-' . $setting_key . '-desc' ); ?>">
			<?php
			switch ( $setting_key ) {
				case 'webp':
					esc_html_e(
						'Note: We’ll detect and serve WebP images to browsers that will accept them by checking
						Accept Headers, and gracefully fall back to normal PNGs or JPEGs for non-compatible browsers.',
						'wp-smushit'
					);
					break;
				case 'auto_resize':
					esc_html_e( 'Having trouble with Google PageSpeeds ‘properly size images’ suggestion? This feature will fix this without any coding needed!', 'wp-smushit' );
					echo '<br>';
					printf(
						/* translators: %1$s - opening tag, %2$s - closing tag */
						esc_html__( 'Note: Smush will pre-fill the srcset attribute with missing image sizes so for this feature to work, those must be declared properly by your theme and page builder using the %1$scontent width%2$s variable.', 'wp-smushit' ),
						'<a href="https://developer.wordpress.com/themes/content-width/" target="_blank">',
						'</a>'
					);
					break;
				case 'background_images':
					printf(
						/* translators: %1$s - link, %2$s - closing link tag */
						esc_html__( 'Note: For this feature to work your theme’s background images must be declared correctly using the default %1$swp_attachment%2$s functions.', 'wp-smushit' ),
						'<a href="https://developer.wordpress.org/reference/functions/wp_get_attachment_image/" target="_blank">',
						'</a>'
					);
					echo '<br>';
					printf(
						/* translators: %1$s - link, %2$s - closing link tag */
						esc_html__( 'For any non-media library uploads, you can still use the %1$sDirectory Smush%2$s feature to compress them, they just won’t be served from the CDN.', 'wp-smushit' ),
						'<a href="' . esc_url( network_admin_url( 'admin.php?page=smush-directory' ) ) . '">',
						'</a>'
					);
					break;
				case 'rest_api_support':
					printf(
						/* translators: %1$s - link, %2$s - closing link tag */
						esc_html__( 'Note: Smush will use the %1$srest_pre_echo_response%2$s hook to filter images in REST API responses.', 'wp-smushit' ),
						'<a href="https://developer.wordpress.org/reference/hooks/rest_pre_echo_response/" target="_blank">',
						'</a>'
					);
					break;
				default:
					break;
			}
			?>
		</span>
		<?php
	}

	/**
	 * Initialize required flags.
	 */
	public function init_flags() {
		// All these are members only feature.
		if ( ! WP_Smush::is_pro() ) {
			return;
		}

		// CDN will not work if there is no dashboard plugin installed.
		if ( ! file_exists( WP_PLUGIN_DIR . '/wpmudev-updates/update-notifications.php' ) && ! class_exists('WPMUDEV_Dashboard') ) {
			return;
		}

		// CDN will not work if site is not registered with the dashboard.
		if ( class_exists( 'WPMUDEV_Dashboard' ) && ! WPMUDEV_Dashboard::$api->has_key() ) {
			return;
		}

		// Disable CDN on staging.
		if ( isset( $_SERVER['WPMUDEV_HOSTING_ENV'] ) && 'staging' === $_SERVER['WPMUDEV_HOSTING_ENV'] ) {
			return;
		}

		$this->status = $this->settings->get_setting( 'wp-smush-cdn_status' );

		// CDN is not enabled and not active.
		if ( ! $this->status ) {
			return;
		}

		$this->cdn_active = isset( $this->status->cdn_enabled ) && $this->status->cdn_enabled;
	}

	/**
	 * Set the API base for the member.
	 */
	public function set_cdn_url() {
		$site_id = absint( $this->status->site_id );

		$this->cdn_base = trailingslashit( "https://{$this->status->endpoint_url}/{$site_id}" );
	}

	/**
	 * Add CDN url to header for better speed.
	 *
	 * @since 3.0
	 *
	 * @param array  $urls URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed.
	 *
	 * @return array
	 */
	public function dns_prefetch( $urls, $relation_type ) {
		// Add only if CDN active.
		if ( 'dns-prefetch' === $relation_type && $this->cdn_active && ! empty( $this->cdn_base ) ) {
			$urls[] = $this->cdn_base;
		}

		return $urls;
	}

	/**
	 * Generate CDN url from given image url.
	 *
	 * @since 3.0
	 *
	 * @param string $src Image url.
	 * @param array  $args Query parameters.
	 *
	 * @return string
	 */
	public function generate_cdn_url( $src, $args = array() ) {
		// Do not continue in case we try this when cdn is disabled.
		if ( ! $this->cdn_active ) {
			return $src;
		}

		/**
		 * Filter hook to alter image src before going through cdn.
		 *
		 * @since 3.4.0
		 * @see smush_image_src_before_cdn filter if you need earlier access with the image element.
		 *
		 * @param string $src  Image src.
		 */
		$src = apply_filters( 'smush_filter_generate_cdn_url', $src );

		// Support for WP installs in subdirectories: remove the site url and leave only the file path.
		$path = str_replace( get_site_url(), '', $src );

		// Parse url to get all parts.
		$url_parts = wp_parse_url( $path );

		// If path not found, do not continue.
		if ( empty( $url_parts['path'] ) ) {
			return $src;
		}

		// Arguments for CDN.
		$pro_args = array(
			'lossy' => $this->settings->get( 'lossy' ) ? 1 : 0,
			'strip' => $this->settings->get( 'strip_exif' ) ? 1 : 0,
			'webp'  => $this->settings->get( 'webp' ) ? 1 : 0,
		);

		$args = wp_parse_args( $pro_args, $args );

		// Replace base url with cdn base.
		$url = $this->cdn_base . ltrim( $url_parts['path'], '/' );

		// Now we need to add our CDN parameters for resizing.
		$url = add_query_arg( $args, $url );

		return $url;
	}

	/**
	 * Enables the CDN.
	 *
	 * @since 3.9.0
	 *
	 * @param bool $enable Whether to enable or disable the CDN.
	 * @return true|WP_error
	 */
	public function toggle_cdn( $enable ) {
		$this->settings->set( 'cdn', $enable );

		if ( $enable ) {
			$status = $this->settings->get_setting( 'wp-smush-cdn_status' );

			if ( ! $status ) {
				$status = WP_Smush::get_instance()->api()->check();
				$data   = $this->process_cdn_status( $status );

				if ( is_wp_error( $data ) ) {
					return $data;
				}

				$this->settings->set_setting( 'wp-smush-cdn_status', $data );
			}

			$this->schedule_cron();

			// Clear HB page cache.
			do_action( 'wphb_clear_page_cache' );
		} else {
			// Remove CDN settings if disabling.
			$this->settings->delete_setting( 'wp-smush-cdn_status' );

			self::unschedule_cron();
		}

		return true;
	}

	/**************************************
	 *
	 * PUBLIC METHODS CDN
	 *
	 * @see parse_image()
	 * @see parse_background_image()
	 * @see process_src()
	 * @see update_image_srcset()
	 * @see update_image_sizes()
	 * @see update_cdn_image_src_args()
	 * @see process_cdn_status()
	 * @see update_stats()
	 * @see unschedule_cron()
	 * @see schedule_cron()
	 * @see filter_rest_api_response()
	 */

	/**
	 * Parse image for CDN.
	 *
	 * @since 3.2.2  Moved out to a separate function.
	 * @since 3.5.0  Added $srcset and $type params.
	 *
	 * @param string $src     Image URL.
	 * @param string $image   Image tag (<img>).
	 * @param string $srcset  Image srcset content.
	 * @param string $type    Element type. Accepts: 'img', 'source' or 'iframe'. Default: 'img'.
	 *
	 * @return string
	 */
	public function parse_image( $src, $image, $srcset = '', $type = 'img' ) {
		/**
		 * Filter to skip a single image from cdn.
		 *
		 * @param bool       $skip   Should skip? Default: false.
		 * @param string     $src    Image url.
		 * @param array|bool $image  Image tag or false.
		 */
		if ( apply_filters( 'smush_skip_image_from_cdn', false, $src, $image ) ) {
			return $image;
		}

		$new_image = $image;

		/**
		 * Support for source in picture element.
		 */
		if ( 'source' === $type && $srcset ) {
			$links = Helpers\Parser::get_links_from_content( $srcset );
			if ( ! isset( $links[0] ) || ! is_array( $links[0] ) ) {
				return $new_image;
			}

			foreach ( $links[0] as $link ) {
				$src = $this->is_supported_path( $link );
				if ( ! $src ) {
					continue;
				}

				// Replace the data-envira-srcset of the image with CDN link.
				$src = $this->generate_cdn_url( $src );
				if ( $src ) {
					// Replace the src of the image with CDN link.
					$new_image = str_replace( $link, $src, $new_image );
				}
			}

			// We can exit early, to avoid additional parsing.
			return $new_image;
		}

		// Store the original $src to be used later on.
		$original_src = $src;

		/**
		 * Filter hook to alter image src at the earliest.
		 *
		 * @param string $src    Image src.
		 * @param string $image  Image tag.
		 */
		$src = apply_filters( 'wp_smush_cdn_before_process_src', $src, $image );

		// Make sure this image is inside a supported directory. Try to convert to valid path.
		$src = $this->is_supported_path( $src );
		if ( $src ) {
			$src = $this->process_src( $image, $src, false );

			// Replace the src of the image with CDN link.
			if ( ! empty( $src ) ) {
				$new_image = preg_replace( '#(src=["|\'])' . $original_src . '(["|\'])#i', '\1' . $src . '\2', $new_image, 1 );
			}

			/**
			 * See if srcset is already set.
			 *
			 * The preg_match is required to make sure that srcset is not already defined.
			 * For the majority of images, srcset will be parsed as part of the wp_calculate_image_srcset filter.
			 * But some images, for example, logos in Avada - will add their own srcset. For such images - generate our own.
			 */
			if ( ! preg_match( '/srcset=["\'](.*?smushcdn\.com[^"\']+)["\']/i', $image ) ) {
				if ( $this->settings->get( 'auto_resize' ) && ! apply_filters( 'smush_skip_adding_srcset', false ) ) {
					list( $srcset, $sizes ) = $this->generate_srcset( $original_src );

					if ( ! is_null( $srcset ) && false !== $srcset ) {
						// Remove possibly empty srcset attribute.
						Helpers\Parser::remove_attribute( $new_image, 'srcset' );
						Helpers\Parser::add_attribute( $new_image, 'srcset', $srcset );
					}

					if ( ! is_null( $srcset ) && false !== $sizes ) {
						// Remove possibly empty sizes attribute.
						Helpers\Parser::remove_attribute( $new_image, 'sizes' );
						Helpers\Parser::add_attribute( $new_image, 'sizes', $sizes );
					}
				} else {
					$data_attributes = array( 'srcset', 'data-srcset' );
					foreach ( $data_attributes as $attribute ) {
						$links = Helpers\Parser::get_attribute( $new_image, $attribute );
						$links = Helpers\Parser::get_links_from_content( $links );
						if ( isset( $links[0] ) && is_array( $links[0] ) ) {
							foreach ( $links[0] as $link ) {
								$src = $this->is_supported_path( $link );
								if ( ! $src ) {
									continue;
								}

								// Replace the data-envira-srcset of the image with CDN link.
								$src = $this->generate_cdn_url( $src );
								if ( $src ) {
									// Replace the src of the image with CDN link.
									$new_image = str_replace( $link, $src, $new_image );
								}
							}
						}
					}
				}
			}
		}

		// Support for 3rd party lazy loading plugins.
		$lazy_attributes = array( 'data-src', 'data-lazy-src', 'data-lazyload', 'data-original' );
		foreach ( $lazy_attributes as $attr ) {
			$data_src = Helpers\Parser::get_attribute( $new_image, $attr );
			$data_src = $this->is_supported_path( $data_src );
			if ( $data_src ) {
				$cdn_image = $this->process_src( $image, $data_src );
				Helpers\Parser::remove_attribute( $new_image, $attr );
				Helpers\Parser::add_attribute( $new_image, $attr, $cdn_image );
			}
		}

		/**
		 * Filter hook to alter image tag before replacing the image in content.
		 *
		 * @param string $image  Image tag.
		 */
		return apply_filters( 'smush_cdn_image_tag', $new_image );
	}

	/**
	 * Parse background image for CDN.
	 *
	 * @since 3.2.2
	 *
	 * @param string $src    Image URL.
	 * @param string $image  Image tag (<img>).
	 *
	 * @return string
	 */
	public function parse_background_image( $src, $image ) {
		/**
		 * Filter to skip a single image from cdn.
		 *
		 * @param bool       $skip   Should skip? Default: false.
		 * @param string     $src    Image url.
		 * @param array|bool $image  Image tag or false.
		 */
		if ( apply_filters( 'smush_skip_background_image_from_cdn', false, $src, $image ) ) {
			return $image;
		}

		$new_image = $image;

		// Store the original $src to be used later on.
		$original_src = $src;

		// Make sure this image is inside a supported directory. Try to convert to valid path.
		$src = $this->is_supported_path( $src );
		if ( $src ) {
			$src = $this->process_src( $image, $src );

			// Replace the src of the image with CDN link.
			if ( ! empty( $src ) ) {
				$new_image = str_replace( $original_src, $src, $new_image );
			}
		}

		/**
		 * Filter hook to alter image tag before replacing the background image in content.
		 *
		 * @param string $image  Image tag.
		 */
		return apply_filters( 'smush_cdn_bg_image_tag', $new_image );
	}

	/**
	 * Process src link and convert to CDN link.
	 *
	 * @since 3.2.1
	 *
	 * @param string $image     Image tag.
	 * @param string $src       Image src attribute.
	 * @param bool   $resizing  Add resizing arguments. Defaults to true.
	 *                          We should never add resize arguments to the images from src. But we can and should
	 *                          add them to the srcset and other possible attributes.
	 *
	 * @return string
	 */
	private function process_src( $image, $src, $resizing = true ) {
		$args = array();

		// Don't need to auto resize - return default args.
		if ( $resizing && $this->settings->get( 'auto_resize' ) ) {
			/**
			 * Filter hook to alter image src arguments before going through cdn.
			 *
			 * @param array  $args   Arguments.
			 * @param string $src    Image src.
			 * @param string $image  Image tag.
			 */
			$args = apply_filters( 'smush_image_cdn_args', array(), $image );
		}

		/**
		 * Filter hook to alter image src before going through cdn.
		 *
		 * @param string $src    Image src.
		 * @param string $image  Image tag.
		 */
		$src = apply_filters( 'smush_image_src_before_cdn', $src, $image );

		// Generate cdn url from local url.
		$src = $this->generate_cdn_url( $src, $args );

		/**
		 * Filter hook to alter image src after replacing with CDN base.
		 *
		 * @param string $src    Image src.
		 * @param string $image  Image tag.
		 */
		$src = apply_filters( 'smush_image_src_after_cdn', $src, $image );

		return $src;
	}

	/**
	 * Filters an array of image srcset values, replacing each URL with resized CDN urls.
	 *
	 * Keep the existing srcset sizes if already added by WP, then calculate extra sizes
	 * if required.
	 *
	 * @since 3.0
	 *
	 * @param array  $sources        One or more arrays of source data to include in the 'srcset'.
	 * @param array  $size_array     Array of width and height values in pixels.
	 * @param string $image_src      The 'src' of the image.
	 * @param array  $image_meta     The image metadata as returned by 'wp_get_attachment_metadata()'.
	 * @param int    $attachment_id  Image attachment ID or 0.
	 *
	 * @return array $sources
	 */
	public function update_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id = 0 ) {
		if ( ! is_array( $sources ) || ! $this->is_supported_path( $image_src ) ) {
			return $sources;
		}

		$main_image_url = false;

		// Try to get image URL from attachment ID.
		if ( empty( $attachment_id ) ) {
			$url            = wp_get_attachment_url( $attachment_id );
			$main_image_url = $url;
		}

		foreach ( $sources as $i => $source ) {
			if ( ! $this->is_valid_url( $source['url'] ) ) {
				continue;
			}

			if ( apply_filters( 'smush_cdn_skip_image', false, $source['url'], $source ) ) {
				continue;
			}

			list( $width, $height ) = $this->get_size_from_file_name( $source['url'] );

			// The file already has a resized version as a thumbnail.
			if ( 'w' === $source['descriptor'] && $width === (int) $source['value'] ) {
				$sources[ $i ]['url'] = $this->generate_cdn_url( $source['url'] );
				continue;
			}

			// If don't have attachment id, get original image by removing dimensions from url.
			if ( empty( $url ) ) {
				$url = $this->get_url_without_dimensions( $source['url'] );
			}

			$args = array();
			// If we got size from url, add them.
			if ( ! empty( $width ) && ! empty( $height ) ) {
				// Set size arg.
				$args = array(
					'size' => "{$width}x{$height}",
				);
			}

			// Replace with CDN url.
			$sources[ $i ]['url'] = $this->generate_cdn_url( $url, $args );
		}

		// Set additional sizes if required.
		if ( $this->settings->get( 'auto_resize' ) ) {
			$sources = $this->set_additional_srcset( $sources, $size_array, $main_image_url, $image_meta, $image_src );

			// Make it look good.
			ksort( $sources );
		}

		return $sources;
	}

	/**
	 * Update image sizes for responsive size.
	 *
	 * @since 3.0
	 *
	 * @param string $sizes A source size value for use in a 'sizes' attribute.
	 * @param array  $size  Requested size.
	 *
	 * @return string
	 */
	public function update_image_sizes( $sizes, $size ) {
		if ( ! doing_filter( 'the_content' ) ) {
			return $sizes;
		}

		// Get maximum content width.
		$content_width = $this->max_content_width();

		if ( is_array( $size ) && $size[0] < $content_width ) {
			return $sizes;
		}

		return sprintf( '(max-width: %1$dpx) 100vw, %1$dpx', $content_width );
	}

	/**
	 * Add resize arguments to content image src.
	 *
	 * @since 3.0
	 *
	 * @param array  $args  Current arguments.
	 * @param object $image Image tag object from DOM.
	 *
	 * @return array $args
	 */
	public function update_cdn_image_src_args( $args, $image ) {
		// Get registered image sizes.
		$image_sizes = WP_Smush::get_instance()->core()->image_dimensions();

		// Find the width and height attributes.
		$width  = false;
		$height = false;

		// Try to get the width and height from img tag.
		if ( preg_match( '/width=["|\']?(\b[[:digit:]]+(?!%)\b)["|\']?/i', $image, $width_string ) ) {
			$width = $width_string[1];
		}

		if ( preg_match( '/height=["|\']?(\b[[:digit:]]+(?!%)\b)["|\']?/i', $image, $height_string ) ) {
			$height = $height_string[1];
		}

		$size = array();

		// Detect WP registered image size from HTML class.
		if ( preg_match( '/size-([^"\'\s]+)[^"\']*["|\']?/i', $image, $size ) ) {
			$size = array_pop( $size );

			if ( ! array_key_exists( $size, $image_sizes ) ) {
				return $args;
			}

			// This is probably a correctly sized thumbnail - no need to resize.
			if ( (int) $width === $image_sizes[ $size ]['width'] || (int) $height === $image_sizes[ $size ]['height'] ) {
				return $args;
			}

			// If this size exists in registered sizes, add argument.
			if ( 'full' !== $size ) {
				$args['size'] = (int) $image_sizes[ $size ]['width'] . 'x' . (int) $image_sizes[ $size ]['height'];
			}
		} else {
			// It's not a registered thumbnail size.
			if ( $width && $height ) {
				$args['size'] = (int) $width . 'x' . (int) $height;
			}
		}

		return $args;
	}

	/**
	 * Process CDN status.
	 *
	 * @since 3.0
	 * @since 3.1  Moved from Ajax class.
	 *
	 * @param array|WP_Error $status  Status in JSON format.
	 *
	 * @return stdClass|WP_Error
	 */
	public function process_cdn_status( $status ) {
		if ( is_wp_error( $status ) ) {
			return $status;
		}

		$status = json_decode( $status['body'] );

		// Too many requests.
		if ( is_null( $status ) ) {
			return new \WP_Error( 'too_many_requests', __( 'Too many requests, please try again in a moment.', 'wp-smushit' ) );
		}

		// Some other error from API.
		if ( ! $status->success ) {
			return new \WP_Error( $status->data->error_code, $status->data->message );
		}

		return $status->data;
	}

	/**
	 * Update CDN stats (daily) cron task or via the get_cdn_stats ajax request.
	 *
	 * @since 3.1.0
	 */
	public function update_stats() {
		$status = $this->settings->get_setting( 'wp-smush-cdn_status' );

		$smush = WP_Smush::get_instance();

		if ( isset( $status->cdn_enabling ) && $status->cdn_enabling ) {
			$status = $this->process_cdn_status( $smush->api()->enable() );

			if ( is_wp_error( $status ) ) {
				$code = is_numeric( $status->get_error_code() ) ? $status->get_error_code() : null;
				wp_send_json_error(
					array(
						'message' => $status->get_error_message(),
					),
					$code
				);
			}

			$this->settings->set_setting( 'wp-smush-cdn_status', $status );
		}

		if ( ! wp_doing_cron() ) {
			// At this point we already know that $status->data is valid.
			wp_send_json_success( $status );
		}
	}

	/**
	 * Disable CDN stats update cron task.
	 *
	 * @since 3.1.0
	 */
	public static function unschedule_cron() {
		$timestamp = wp_next_scheduled( 'smush_update_cdn_stats' );
		wp_unschedule_event( $timestamp, 'smush_update_cdn_stats' );
	}

	/**
	 * Set cron task to update CDN stats daily.
	 *
	 * @since 3.1.0
	 */
	public function schedule_cron() {
		if ( ! wp_next_scheduled( 'smush_update_cdn_stats' ) ) {
			// Schedule first run for next day, as we've already checked just now.
			wp_schedule_event( time() + DAY_IN_SECONDS, 'daily', 'smush_update_cdn_stats' );
		}
	}

	/**
	 * Filters the API response.
	 *
	 * Allows modification of the response data after inserting
	 * embedded data (if any) and before echoing the response data.
	 *
	 * @since 3.6.0
	 *
	 * @param array $response  Response data to send to the client.
	 *
	 * @return array
	 */
	public function filter_rest_api_response( $response ) {
		if ( ! $this->settings->get( 'rest_api_support' ) ) {
			return $response;
		}

		if ( ! is_array( $response ) || ! isset( $response['content']['rendered'] ) ) {
			return $response;
		}

		$images = Helpers\Parser::get_links_from_content( $response['content']['rendered'] );

		if ( ! isset( $images[0] ) || empty( $images[0] ) ) {
			return $response;
		}

		foreach ( $images[0] as $key => $image ) {
			$image = $this->is_supported_path( $image );
			if ( ! $image ) {
				continue;
			}

			// Replace the data-envira-srcset of the image with CDN link.
			$image = $this->generate_cdn_url( $image );
			if ( $image ) {
				// Replace the src of the image with CDN link.
				$response['content']['rendered'] = str_replace( $images[0][ $key ], $image, $response['content']['rendered'] );
			}
		}

		return $response;
	}

	/**************************************
	 *
	 * PRIVATE METHODS
	 *
	 * Functions that are used by the public methods of this CDN class.
	 *
	 * @since 3.0.0:
	 *
	 * @see is_valid_url()
	 * @see get_size_from_file_name()
	 * @see get_url_without_dimensions()
	 * @see max_content_width()
	 * @see set_additional_srcset()
	 * @see generate_srcset()
	 * @see maybe_generate_srcset()
	 * @see is_supported_path()
	 */

	/**
	 * Check if we can use the image URL in CDN.
	 *
	 * @since 3.0
	 *
	 * @param string $url  Image URL.
	 *
	 * @return bool
	 */
	private function is_valid_url( $url ) {
		$parsed_url = wp_parse_url( $url );

		if ( ! $parsed_url ) {
			return false;
		}

		// No host or path found.
		if ( ! isset( $parsed_url['host'] ) || ! isset( $parsed_url['path'] ) ) {
			return false;
		}

		// If not supported extension - return false.
		if ( ! in_array( strtolower( pathinfo( $parsed_url['path'], PATHINFO_EXTENSION ) ), $this->supported_extensions, true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Try to determine height and width from strings WP appends to resized image filenames.
	 *
	 * @since 3.0
	 *
	 * @param string $src The image URL.
	 *
	 * @return array An array consisting of width and height.
	 */
	private function get_size_from_file_name( $src ) {
		$size = array();

		if ( preg_match( '/(\d+)x(\d+)\.(?:' . implode( '|', $this->supported_extensions ) . ')$/i', $src, $size ) ) {
			// Get size and width.
			$width  = (int) $size[1];
			$height = (int) $size[2];

			// Handle retina images.
			if ( strpos( $src, '@2x' ) ) {
				$width  = 2 * $width;
				$height = 2 * $height;
			}

			// Return width and height as array.
			if ( $width && $height ) {
				return array( $width, $height );
			}
		}

		return array( false, false );
	}

	/**
	 * Get full size image url from resized one.
	 *
	 * @since 3.0
	 *
	 * @param string $src Image URL.
	 *
	 * @return string
	 */
	private function get_url_without_dimensions( $src ) {
		if ( ! preg_match( '/(-\d+x\d+)\.(' . implode( '|', $this->supported_extensions ) . ')(?:\?.+)?$/i', $src, $src_parts ) ) {
			return $src;
		}

		// Remove WP's resize string to get the original image.
		$original_src = str_replace( $src_parts[1], '', $src );

		// Upload directory.
		$upload_dir = wp_get_upload_dir();

		// Extracts the file path to the image minus the base url.
		$file_path = substr( $original_src, strlen( $upload_dir['baseurl'] ) );

		// Continue only if the file exists.
		if ( file_exists( $upload_dir['basedir'] . $file_path ) ) {
			return $original_src;
		}

		// Revert to source if file does not exist.
		return $src;
	}

	/**
	 * Get $content_width global var value.
	 *
	 * @since 3.0
	 *
	 * @return bool|string
	 */
	private function max_content_width() {
		// Get global content width (if content width is empty, set 1900).
		$content_width = isset( $GLOBALS['content_width'] ) ? (int) $GLOBALS['content_width'] : 1920;

		// Avoid situations, when themes misuse the global.
		if ( 0 === $content_width ) {
			$content_width = 1920;
		}

		// Check to see if we are resizing the images (can not go over that value).
		$resize_sizes = $this->settings->get_setting( 'wp-smush-resize_sizes' );

		if ( isset( $resize_sizes['width'] ) && $resize_sizes['width'] < $content_width ) {
			return $resize_sizes['width'];
		}

		return $content_width;
	}

	/**
	 * Filters an array of image srcset values, and add additional values.
	 *
	 * @since 3.0
	 *
	 * @param array  $sources    An array of image urls and widths.
	 * @param array  $size_array Array of width and height values in pixels.
	 * @param string $url        Image URL.
	 * @param array  $image_meta The image metadata.
	 * @param string $image_src  The src of the image.
	 *
	 * @return array $sources
	 */
	private function set_additional_srcset( $sources, $size_array, $url, $image_meta, $image_src = '' ) {
		$content_width = $this->max_content_width();

		// If url is empty, try to get from src.
		if ( empty( $url ) ) {
			$url = $this->get_url_without_dimensions( $image_src );
		}

		// We need to add additional dimensions.
		$full_width     = $image_meta['width'];
		$full_height    = $image_meta['height'];
		$current_width  = $size_array[0];
		$current_height = $size_array[1];
		// Get width and height calculated by WP.
		list( $constrained_width, $constrained_height ) = wp_constrain_dimensions( $full_width, $full_height, $current_width, $current_height );

		// Calculate base width.
		// If $constrained_width sizes are smaller than current size, set maximum content width.
		if ( abs( $constrained_width - $current_width ) <= 1 && abs( $constrained_height - $current_height ) <= 1 ) {
			$base_width = $content_width;
		} else {
			$base_width = $current_width;
		}

		$current_widths = array_keys( $sources );
		$new_sources    = array();

		/**
		 * Filter to add/update/bypass additional srcsets.
		 *
		 * If empty value or false is retured, additional srcset
		 * will not be generated.
		 *
		 * @param array|bool $additional_multipliers Additional multipliers.
		 */
		$additional_multipliers = apply_filters(
			'smush_srcset_additional_multipliers',
			array(
				0.2,
				0.4,
				0.6,
				0.8,
				1,
				2,
				3,
			)
		);

		// Continue only if additional multipliers found or not skipped.
		// Filter already documented in class-cdn.php.
		if ( apply_filters( 'smush_skip_image_from_cdn', false, $url, false ) || empty( $additional_multipliers ) ) {
			return $sources;
		}

		// Loop through each multipliers and generate image.
		foreach ( $additional_multipliers as $multiplier ) {
			// New width by multiplying with original size.
			$new_width = (int) ( $base_width * $multiplier );

			// In most cases - going over the current width is not recommended and probably not what the user is expecting.
			if ( $new_width > $current_width ) {
				continue;
			}

			// If a nearly sized image already exist, skip.
			foreach ( $current_widths as $_width ) {
				// If +- 50 pixel difference - skip.
				if ( abs( $_width - $new_width ) < 50 || ( $new_width > $full_width ) ) {
					continue 2;
				}
			}

			// We need the width as well...
			$dimensions = wp_constrain_dimensions( $current_width, $current_height, $new_width );

			// Arguments for cdn url.
			$args = array(
				'size' => "{$new_width}x{$dimensions[1]}",
			);

			// Add new srcset item.
			$new_sources[ $new_width ] = array(
				'url'        => $this->generate_cdn_url( $url, $args ),
				'descriptor' => 'w',
				'value'      => $new_width,
			);
		}

		// Assign new srcset items to existing ones.
		if ( ! empty( $new_sources ) ) {
			// Loop through each items and replace/add.
			foreach ( $new_sources as $_width_key => $_width_values ) {
				$sources[ $_width_key ] = $_width_values;
			}
		}

		return $sources;
	}

	/**
	 * Try to generate the srcset for the image.
	 *
	 * @since 3.0
	 *
	 * @param string $src  Image source.
	 *
	 * @return array|bool
	 */
	private function generate_srcset( $src ) {
		/**
		 * Try to get the attachment URL.
		 *
		 * TODO: attachment_url_to_postid() can be resource intensive and cause 100% CPU spikes.
		 *
		 * @see https://core.trac.wordpress.org/ticket/41281
		 */
		$attachment_id = attachment_url_to_postid( $src );

		// Try to get width and height from image.
		if ( $attachment_id ) {
			list( $src, $width, $height ) = wp_get_attachment_image_src( $attachment_id, 'full' );

			// Revolution slider fix: images will always return 0 height and 0 width.
			if ( 0 === $width && 0 === $height ) {
				// Try to get the dimensions directly from the file.
				list( $width, $height ) = $this->get_image_size( $src );
			}

			$image_meta = wp_get_attachment_metadata( $attachment_id );
		} else {
			// Try to get the dimensions directly from the file.
			list( $width, $height ) = $this->get_image_size( $src );

			// This is an image placeholder - do not generate srcset.
			if ( $width === $height && 1 === $width ) {
				return false;
			}

			$image_meta = array(
				'width'  => $width,
				'height' => $height,
			);
		}

		$size_array = array( absint( $width ), absint( $height ) );
		$srcset     = wp_calculate_image_srcset( $size_array, $src, $image_meta, $attachment_id );

		/**
		 * In some rare cases, the wp_calculate_image_srcset() will not generate any srcset, because there are
		 * not image sizes defined. If that is the case, try to revert to our custom maybe_generate_srcset() to
		 * generate the srcset string.
		 *
		 * Also srcset will not be generated for images that are not part of the media library (no $attachment_id).
		 */
		if ( ! $srcset ) {
			$srcset = $this->maybe_generate_srcset( $width, $height, $src, $image_meta );
		}

		$sizes = $srcset ? wp_calculate_image_sizes( $size_array, $src, $image_meta, $attachment_id ) : false;

		return array( $srcset, $sizes );
	}

	/**
	 * Try to generate srcset.
	 *
	 * @since 3.0
	 *
	 * @param int    $width   Attachment width.
	 * @param int    $height  Attachment height.
	 * @param string $src     Image source.
	 * @param array  $meta    Image meta.
	 *
	 * @return bool|string
	 */
	private function maybe_generate_srcset( $width, $height, $src, $meta ) {
		$sources[ $width ] = array(
			'url'        => $this->generate_cdn_url( $src ),
			'descriptor' => 'w',
			'value'      => $width,
		);

		$sources = $this->set_additional_srcset(
			$sources,
			array( absint( $width ), absint( $height ) ),
			$src,
			$meta
		);

		$srcsets = array();

		if ( 1 < count( $sources ) ) {
			foreach ( $sources as $source ) {
				$srcsets[] = str_replace( ' ', '%20', $source['url'] ) . ' ' . $source['value'] . $source['descriptor'];
			}
			return implode( ',', $srcsets );
		}

		return false;
	}

	/**
	 * Check if the image path is supported by the CDN.
	 *
	 * @since 3.0
	 * @since 3.3.0 Changed access to public.
	 *
	 * @param string $src  Image path.
	 *
	 * @return bool|string
	 */
	public function is_supported_path( $src ) {
		// Remove whitespaces.
		$src = trim( $src );

		// No image? Return.
		if ( empty( $src ) ) {
			return false;
		}

		// Allow only these extensions in CDN.
		$path = wp_parse_url( $src, PHP_URL_PATH );
		$ext  = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
		if ( ! in_array( $ext, $this->supported_extensions, true ) ) {
			return false;
		}

		$url_parts = wp_parse_url( $src );

		// Unsupported scheme.
		if ( isset( $url_parts['scheme'] ) && 'http' !== $url_parts['scheme'] && 'https' !== $url_parts['scheme'] ) {
			return false;
		}

		if ( ! isset( $url_parts['scheme'] ) && 0 === strpos( $src, '//' ) ) {
			$src = is_ssl() ? 'https:' . $src : 'http:' . $src;
		}

		// This is a relative path, try to get the URL.
		if ( ! isset( $url_parts['host'] ) && ! isset( $url_parts['scheme'] ) ) {
			$src = site_url( $src );
		}

		$mapped_domain = $this->check_mapped_domain();

		/**
		 * There are chances for a custom uploads directory using UPLOADS constant.
		 *
		 * But some security plugins (for example, WP Hide & Security Enhance) will allow replacing paths via Nginx/Apache
		 * rules. So for this reason, we don't want the path to be replaced everywhere with the custom UPLOADS constant,
		 * we just want to let the user redefine it here, in the CDN.
		 *
		 * @since 3.4.0
		 *
		 * @param array $uploads {
		 *     Array of information about the upload directory.
		 *
		 *     @type string       $path    Base directory and subdirectory or full path to upload directory.
		 *     @type string       $url     Base URL and subdirectory or absolute URL to upload directory.
		 *     @type string       $subdir  Subdirectory if uploads use year/month folders option is on.
		 *     @type string       $basedir Path without subdir.
		 *     @type string       $baseurl URL path without subdir.
		 *     @type string|false $error   False or error message.
		 * }
		 *
		 * Usage (replace /wp-content/uploads/ with /media/ directory):
		 *
		 * add_filter(
		 *     'smush_cdn_custom_uploads_dir',
		 *     function( $uploads ) {
		 *         $uploads['baseurl'] = 'https://example.com/media';
		 *         return $uploads;
		 *     }
		 * );
		 */
		$uploads = apply_filters( 'smush_cdn_custom_uploads_dir', wp_get_upload_dir() );
		// Check if the src is within custom uploads directory.
		$uploads = isset( $uploads['baseurl'] ) ? false !== strpos( $src, $uploads['baseurl'] ) : true;

		if ( ( false === strpos( $src, content_url() ) && ! $uploads ) || ( is_multisite() && $mapped_domain && false === strpos( $src, $mapped_domain ) ) ) {
			return false;
		}

		return $src;
	}

	/**
	 * Support for domain mapping plugin.
	 *
	 * @since 3.1.1
	 */
	private function check_mapped_domain() {
		if ( ! is_multisite() ) {
			return false;
		}

		if ( ! defined( 'DOMAINMAP_BASEFILE' ) ) {
			return false;
		}

		$domain = wp_cache_get( 'smush_mapped_site_domain', 'smush' );

		if ( ! $domain ) {
			global $wpdb;

			$domain = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT domain FROM {$wpdb->base_prefix}domain_mapping WHERE blog_id = %d ORDER BY id LIMIT 1",
					get_current_blog_id()
				)
			); // Db call ok.

			if ( null !== $domain ) {
				wp_cache_add( 'smush_mapped_site_domain', $domain, 'smush' );
			}
		}

		return $domain;
	}

	/**
	 * Init the page parser.
	 */
	private function init_parser() {
		$background_images = $this->settings->get( 'background_images' );

		if ( $background_images ) {
			$this->parser->enable( 'background_images' );
		}

		$this->parser->enable( 'cdn' );
	}

	/**
	 * Try to get the image dimensions from a local file.
	 *
	 * @since 3.4.0
	 * @param string $url  Image URL.
	 *
	 * @return array|false
	 */
	private function get_image_size( $url ) {
		if ( $this->site_url !== $this->home_url ) {
			$url = str_replace( $this->site_url, $this->home_url, $url );
		}

		$path = wp_make_link_relative( $url );
		$path = wp_normalize_path( ABSPATH . $path );

		if ( ! file_exists( $path ) ) {
			return false;
		}

		return getimagesize( $path );
	}

}
