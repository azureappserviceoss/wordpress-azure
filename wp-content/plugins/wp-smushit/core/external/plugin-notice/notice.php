<?php
/**
 * WPMUDEV - Recommended Plugins Notice
 *
 * @author  WPMUDEV (https://wpmudev.com)
 * @license GPLv2
 */

if ( ! class_exists( 'WPMUDEV_Recommended_Plugins_Notice_Registered_Plugin' ) ) {
	/**
	 * Class WPMUDEV_Recommended_Plugins_Notice_Registered_Plugin
	 *
	 * Hold registered plugin as object
	 */
	class WPMUDEV_Recommended_Plugins_Notice_Registered_Plugin { //phpcs:ignore

		/**
		 * Plugin basename
		 * plugin-dir/plugin-name.php
		 *
		 * @var string
		 */
		protected $basename = '';

		/**
		 * Plugin nice name
		 *
		 * `My Plugin`
		 *
		 * @var string
		 */
		public $name = '';

		/**
		 * Screens which notice should be displayed
		 *
		 * @var array
		 */
		public $screens = array();

		/**
		 * Time the plugin registered to notice
		 *
		 * @var int
		 */
		public $registered_at = 0;

		/**
		 * Element selector which notice should be append-ed
		 *
		 * @var array
		 */
		public $selector = array();

		/**
		 * Current active screen being displayed
		 *
		 * @var string
		 */
		protected $active_screen = '';

		/**
		 * WPMUDEV_Recommended_Plugins_Notice_Registered_Plugin constructor.
		 *
		 * @param string $basename
		 */
		public function __construct( $basename ) {
			$this->basename = $basename;
		}

		/**
		 * Get plugin basename
		 *
		 * @return string
		 */
		public function get_basename() {
			return $this->basename;
		}

		/**
		 * Build object properties from array
		 *
		 * @param array $data
		 */
		public function from_array( $data ) {
			if ( is_array( $data ) ) {
				if ( isset( $data['registered_at'] ) ) {
					$this->registered_at = (int) $data['registered_at'];
				}
			}
		}

		/**
		 * Export to array
		 *
		 * @return array
		 */
		public function to_array() {
			return array(
				'registered_at' => $this->registered_at,
			);
		}

		/**
		 * Check if screen is listen on plugin screen
		 *
		 * @param string $screen_id
		 *
		 * @return bool
		 */
		public function is_my_screen( $screen_id ) {
			foreach ( $this->screens as $screen ) {
				if ( $screen_id === $screen ) {
					$this->active_screen = $screen_id;

					return true;
				}
			}

			return false;
		}

		/**
		 * Get where notice should be moved
		 *
		 * @return array
		 */
		public function get_selector() {
			$selector      = $this->selector;
			$active_screen = $this->active_screen;

			/**
			 * Filter selector which notice should be moved to
			 *
			 * @param array  $selector
			 * @param string $active_screen
			 *
			 * @return array
			 */
			$selector = apply_filters( "wpmudev-recommended-plugin-{$this->basename}-notice-selector", $selector, $active_screen );

			return $selector;
		}

		/**
		 * Check whether now is the time to display
		 *
		 * @return bool
		 */
		public function is_time_to_display_notice() {
			$active_screen            = $this->active_screen;
			$seconds_after_registered = 14 * DAY_IN_SECONDS;

			/**
			 * Filter how much seconds after registered before notice displayed
			 *
			 * this filter globally used
			 *
			 * @param int    $seconds_after_registered
			 * @param string $active_screen
			 *
			 * @return string
			 */
			$seconds_after_registered = apply_filters( "wpmudev-recommended-plugins-notice-display-seconds-after-registered", $seconds_after_registered, $active_screen );


			/**
			 * Filter how much seconds after registered before notice displayed
			 *
			 * this filter is for plugin based, overriding global value
			 *
			 * @param int    $seconds_after_registered
			 * @param string $active_screen
			 *
			 * @return string
			 */
			$seconds_after_registered = apply_filters( "wpmudev-recommended-plugin-{$this->basename}-notice-display-seconds-after-registered", $seconds_after_registered, $active_screen );

			$now = time();

			if ( $now >= ( $this->registered_at + $seconds_after_registered ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Get pre text on displayed notice
		 *
		 * @return string
		 */
		public function get_pre_text_notice() {
			$pre_text_notice = sprintf( __( 'Enjoying %s? Try out a few of our other popular free plugins...', 'wpmudev_recommended_plugins_notice' ), $this->name );

			/**
			 * Filter pre text on displayed notice
			 *
			 * @param string $pre_text_notice
			 *
			 * @return string
			 */
			$pre_text_notice = apply_filters( "wpmudev-recommended-plugin-{$this->basename}-pre-text-notice", $pre_text_notice );

			return $pre_text_notice;
		}

	}
}

if ( ! class_exists( 'WPMUDEV_Recommended_Plugins_Notice' ) ) {

	/**
	 * Class WPMUDEV_Recommended_Plugins_Notice
	 *
	 * @internal
	 */
	class WPMUDEV_Recommended_Plugins_Notice {//phpcs:ignore

		/**
		 * @var WPMUDEV_Recommended_Plugins_Notice
		 */
		private static $instance = null;

		/**
		 * Collection of recommended plugins
		 *
		 * @var array
		 */
		protected $recommended_plugins = array();

		/**
		 * Version
		 */
		const VERSION = '1.0.0';

		/**
		 * Pointer name
		 */
		const POINTER_NAME = 'wpmudev_recommended_plugins';

		/**
		 * Registered plugins
		 */
		const OPTION_NAME = 'wpmudev_recommended_plugins_registered';

		/**
		 * Collection of registered plugins to use this notice
		 *
		 * @var WPMUDEV_Recommended_Plugins_Notice_Registered_Plugin[]
		 */
		protected $registered_plugins = array();

		/**
		 * Active registered plugin on this screen
		 *
		 * @var null
		 */
		protected $active_registered_plugin = null;

		/**
		 * WPMUDEV_Recommended_Plugins_Notice constructor.
		 */
		public function __construct() {
			// only do things when its on admin screen
			if ( is_admin() ) {
				$this->init_recommended_plugins();

				$this->parse_saved_registered_plugins();
				add_action( 'wpmudev-recommended-plugins-register-notice', array( $this, 'register' ), 10, 4 );

				add_action( 'all_admin_notices', array( $this, 'display' ), 6 );
			}

		}

		/**
		 * Init recommended plugins
		 *
		 * @return void
		 */
		private function init_recommended_plugins() {
			$recommended_plugins = array(
				array(
					'name'         => 'Smush Image Compression',
					'desc'         => __( 'Resize, optimize and compress all of your images to the max.', 'wpmudev_recommended_plugins_notice' ),
					'image'        => trailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/images/plugins-smush.png',
					'free_slug'    => 'wp-smushit/wp-smush.php',
					'pro_slug'     => 'wp-smush-pro/wp-smush.php',
					'install_link' => 'https://wordpress.org/plugins/wp-smushit/',
				),
				array(
					'name'         => 'Hummingbird Performance',
					'desc'         => __( 'Add powerful caching and optimize your assets.', 'wpmudev_recommended_plugins_notice' ),
					'image'        => trailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/images/plugins-hummingbird.png',
					'free_slug'    => 'hummingbird-performance/wp-hummingbird.php',
					'pro_slug'     => 'wp-hummingbird/wp-hummingbird.php',
					'install_link' => 'https://wordpress.org/plugins/hummingbird-performance/',
				),
				array(
					'name'         => 'Defender Security',
					'desc'         => __( 'Secure and protect your site from malicious hackers and bots.', 'wpmudev_recommended_plugins_notice' ),
					'image'        => trailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/images/plugins-defender.png',
					'free_slug'    => 'defender-security/wp-defender.php',
					'pro_slug'     => 'wp-defender/wp-defender.php',
					'install_link' => 'https://wordpress.org/plugins/defender-security/',
				),
				array(
					'name'         => 'SmartCrawl SEO',
					'desc'         => __( 'Configure your markup for optimal page and social ranking.', 'wpmudev_recommended_plugins_notice' ),
					'image'        => trailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/images/plugins-smartcrawl.png',
					'free_slug'    => 'smartcrawl-seo/wpmu-dev-seo.php',
					'pro_slug'     => 'wpmu-dev-seo/wpmu-dev-seo.php',
					'install_link' => 'https://wordpress.org/plugins/smartcrawl-seo/',
				),
				array(
					'name'         => 'Forminator Forms, Polls & Quizzes',
					'desc'         => __( 'Create dynamic forms easily and quickly with our form builder.', 'wpmudev_recommended_plugins_notice' ),
					'image'        => trailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/images/plugins-forminator.png',
					'free_slug'    => 'forminator/forminator.php',
					'pro_slug'     => '',
					'install_link' => 'https://wordpress.org/plugins/forminator/',
				),
				array(
					'name'         => 'Hustle Marketing',
					'desc'         => __( 'Generate leads with pop-ups, slide-ins and email opt-ins.', 'wpmudev_recommended_plugins_notice' ),
					'image'        => trailingslashit( plugin_dir_url( __FILE__ ) ) . '/assets/images/plugins-hustle.png',
					'free_slug'    => 'wordpress-popup/popover.php',
					'pro_slug'     => 'hustle/opt-in.php',
					'install_link' => 'https://wordpress.org/plugins/wordpress-popup/',
				),
			);

			$recommended_plugins = apply_filters( 'wpmudev-all-recommended-plugins', $recommended_plugins );

			$this->recommended_plugins = $recommended_plugins;
		}

		/**
		 * Get recommended plugins to be displayed on notice
		 *
		 * This function will only return recommended plugins that `not installed` yet
		 *
		 * @param int $min minimum plugins to be displayed
		 * @param int $max maximum plugins to be displayed
		 *
		 * @return array
		 */
		protected function get_recommended_plugins_for_notice( $min = 2, $max = 2 ) {
			$recommended_plugins_for_notice = array();
			$recommended_plugins            = $this->recommended_plugins;

			foreach ( $recommended_plugins as $recommended_plugin ) {

				if ( $this->is_plugin_installed( $recommended_plugin ) ) {
					continue;
				}

				$recommended_plugins_for_notice[] = $recommended_plugin;
				// stop when we reached max
				if ( count( $recommended_plugins_for_notice ) >= $max ) {
					break;
				}
			}

			// not enough!
			if ( count( $recommended_plugins_for_notice ) < $min ) {
				$recommended_plugins_for_notice = array();
			}

			/**
			 * Filter recommended plugins to be displayed on notice
			 *
			 * @param array $recommended_plugins_for_notice recommended plugins to be displayed
			 * @param array $recommended_plugins            all recommended plugins
			 * @param int   $min                            minimum plugins to be displayed
			 * @param int   $max                            maximum plugins to be displayed
			 *
			 * @return array
			 */
			$recommended_plugins_for_notice = apply_filters( 'wpmudev-recommended-plugins', $recommended_plugins_for_notice, $recommended_plugins, $min, $max );

			return $recommended_plugins_for_notice;
		}

		/**
		 * Check whether plugin is installed
		 *
		 * @uses get_plugins()
		 *
		 * @param $plugin_data
		 *
		 * @return bool
		 */
		protected function is_plugin_installed( $plugin_data ) {
			$is_installed = false;

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$installed_plugins = get_plugins();

			// check the free one
			if ( isset( $plugin_data['free_slug'] ) && ! empty( $plugin_data['free_slug'] ) ) {

				if ( isset( $installed_plugins[ $plugin_data['free_slug'] ] ) ) {
					$is_installed = true;
				}
			}

			// check the pro one
			if ( ! $is_installed ) {
				if ( isset( $plugin_data['pro_slug'] ) && ! empty( $plugin_data['pro_slug'] ) ) {
					if ( isset( $installed_plugins[ $plugin_data['pro_slug'] ] ) ) {
						$is_installed = true;
					}
				}
			}

			/**
			 * Filter is_installed status of recommended plugin
			 *
			 * @param bool  $is_installed
			 * @param array $plugin_data       plugin to be check
			 * @param array $installed_plugins current installed plugins
			 *
			 * @return bool
			 */
			$is_installed = apply_filters( 'wpmudev-recommended-plugin-is-installed', $is_installed, $plugin_data, $installed_plugins );

			return $is_installed;
		}

		/**
		 * Display the notice
		 *
		 * @return void
		 */
		public function display() {

			/**
			 * Fires before displaying notice
			 *
			 * this action fired before any check done
			 */
			do_action( 'wpmudev-recommended-plugins-before-display' );

			$is_displayable = $this->is_displayable();

			/**
			 * Filter whether notice is displayable
			 *
			 * @param bool $is_displayable
			 *
			 * @return bool
			 */
			$is_displayable = apply_filters( 'wpmudev-recommended-plugins-is-displayable', $is_displayable );
			if ( ! $is_displayable ) {
				return;
			}

			$active_registered_plugin = $this->active_registered_plugin;

			/**
			 * Filter whether notice is displayable
			 *
			 * @param bool $is_displayable
			 *
			 * @return bool
			 */
			$active_registered_plugin = apply_filters( 'wpmudev-recommended-plugin-active-registered', $active_registered_plugin );

			if ( ! $active_registered_plugin instanceof WPMUDEV_Recommended_Plugins_Notice_Registered_Plugin ) {
				return;
			}

			$recommended_plugins = $this->get_recommended_plugins_for_notice();

			// no plugins to be recommended
			if ( empty( $recommended_plugins ) ) {
				return;
			}

			wp_register_style( 'wpmudev-recommended-plugins-css', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/css/notice.css', array(), self::VERSION );
			wp_enqueue_style( 'wpmudev-recommended-plugins-css' );

			wp_register_script( 'wpmudev-recommended-plugins-js', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/js/notice.js', array( 'jquery' ), self::VERSION, true );
			wp_enqueue_script( 'wpmudev-recommended-plugins-js' );

			$dismissed_text = __( 'Dismiss', 'wpmudev_recommended_plugins_notice' );

			/**
			 * Filter dismissed text
			 *
			 * @param string $dismissed_text
			 *
			 * @return string
			 */
			$dismissed_text = apply_filters( 'wpmudev-recommended-plugins-dismissed-text', $dismissed_text );

			// placement customizer
			$selector         = $active_registered_plugin->get_selector();
			$data_selector_el = '';
			$data_selector_fn = '';

			if ( is_array( $selector ) ) {
				if ( isset( $selector[0] ) ) {
					$data_selector_fn = (string) $selector[0];
				}
				if ( isset( $selector[1] ) ) {
					$data_selector_el = (string) $selector[1];
				}
			}

			?>
			<div class="wpmudev-recommended-plugins" style="display: none"
			     data-selector-el="<?php echo esc_attr( $data_selector_el ); ?>"
			     data-selector-fn="<?php echo esc_attr( $data_selector_fn ); ?>">
				<p class="wpmudev-notice-status"><?php echo $active_registered_plugin->get_pre_text_notice();// wpcs xss ok. allow html here ?></p>
				<div class="wpmudev-recommended-plugin-blocks">
				<?php foreach ( $recommended_plugins as $recommended_plugin ) : ?>
					<div class="wpmudev-recommended-plugin-block">
						<a href="<?php echo esc_attr( $recommended_plugin['install_link'] ); ?>" target="_blank">
							<div class="wpmudev-recommended-plugin-block-image">
								<img src="<?php echo esc_url( $recommended_plugin['image'] ); ?>" alt="<?php echo esc_attr( $recommended_plugin['name'] ); ?>"/>
							</div>
							<div class="wpmudev-recommended-plugin-block-detail">
								<h3 class="wpmudev-plugin-name"><?php echo esc_html( $recommended_plugin['name'] ); ?></h3>
								<p class="wpmudev-plugin-description"><?php echo esc_html( $recommended_plugin['desc'] ); ?></p>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
				</div>

				<div class="wpmudev-recommended-plugins-dismiss">
					<a 	class="dismiss"
						href="#"
					  	aria-label="Dismiss"
					   	data-action="dismiss-wp-pointer"
					   	data-pointer="<?php echo esc_attr( self::POINTER_NAME ); ?>">
						<i class="icon-close" aria-hidden="true"></i>
					</a>
				</div>
			</div>
			<?php

			/**
			 * Fires after displaying notice
			 *
			 * this action fired after notice markup sent
			 * if any check above fails, this action won't fired
			 */
			do_action( 'wpmudev-recommended-plugins-after-display' );
		}

		/**
		 * Check if notice can be displayed
		 *
		 * @uses current_user_can()
		 *
		 * @return bool
		 */
		public function is_displayable() {

			/**
			 * CHECK #1 whether dismissed previously ?
			 *
			 * @uses dismissed_wp_pointers use builtin dismissed_wp_pointers to hold data, to avoid add more rows on WP
			 */
			$dismissed_wp_pointers = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
			$dismissed_wp_pointers = explode( ',', (string) $dismissed_wp_pointers );

			$dismissed = ( is_array( $dismissed_wp_pointers ) && in_array( self::POINTER_NAME, $dismissed_wp_pointers, true ) );

			/**
			 * Filter flag of dismissed status
			 *
			 * @param bool $dismissed
			 *
			 * @return bool
			 */
			$dismissed = apply_filters( 'wpmudev-recommended-plugins-dismissed', $dismissed, $dismissed_wp_pointers );

			if ( $dismissed ) {
				return false;
			}

			/**
			 * CHECK #2 Cap check
			 * default is for user that capable of `install_plugins`, which make sense because this notice will suggest user to install plugin
			 */
			$capability_to_check = 'install_plugins';

			/**
			 * Filter user capability which will be shown this notice
			 *
			 * @param string $capability_to_check
			 *
			 * @return string
			 */
			$capability_to_check = apply_filters( 'wpmudev-recommended-plugins-capability', $capability_to_check );

			if ( ! current_user_can( $capability_to_check ) ) {
				return false;
			}

			/**
			 * CHECK #3 is_wpmudev_member
			 *
			 * This guy is a pro!!
			 * - wpmudev dash activated and or membership type = full
			 */
			if ( function_exists( 'is_wpmudev_member' ) ) {
				if ( is_wpmudev_member() ) {
					return false;
				}
			}

			/**
			 * CHECK #4 only display on screens that defined by plugin
			 */
			$current_screen = get_current_screen();

			if ( ! $current_screen instanceof WP_Screen || ! isset( $current_screen->id ) ) {
				return false;
			}

			$active_registered_plugin = null;
			foreach ( $this->registered_plugins as $registered_plugin ) {
				if ( $registered_plugin->is_my_screen( $current_screen->id ) ) {
					$active_registered_plugin = $registered_plugin;
					break;
				}
			}

			if ( is_null( $active_registered_plugin ) ) {
				return false;
			}

			/**
			 * CHECK #5 time after register to display notice
			 */
			if ( ! $active_registered_plugin->is_time_to_display_notice() ) {
				return false;
			}

			$this->active_registered_plugin = $active_registered_plugin;

			return true;
		}

		/**
		 * Register plugin for notice to be added
		 *
		 * @param string $plugin_basename
		 * @param string $plugin_name
		 * @param array  $screens
		 * @param array  $selector [jqueryFn, jQuerySelector]
		 */
		public function register( $plugin_basename, $plugin_name, $screens = array(), $selector = array() ) {

			// invalid register
			if ( empty( $plugin_basename ) || empty( $plugin_name ) ) {
				return;
			}

			if ( ! isset( $this->registered_plugins[ $plugin_basename ] ) ) {
				$registered_plugin_object                     = new WPMUDEV_Recommended_Plugins_Notice_Registered_Plugin( $plugin_basename );
				$registered_plugin_object->registered_at      = time();
				$this->registered_plugins[ $plugin_basename ] = $registered_plugin_object;

				$this->save_registered_plugins();
			}

			$registered_plugin_object           = $this->registered_plugins[ $plugin_basename ];
			$registered_plugin_object->name     = $plugin_name;
			$registered_plugin_object->screens  = $screens;
			$registered_plugin_object->selector = $selector;

			$this->registered_plugins[ $plugin_basename ] = $registered_plugin_object;
		}

		/**
		 * Parse registered plugins from storage
		 *
		 */
		public function parse_saved_registered_plugins() {

			/**
			 * Fired before saved registered plugins being parse
			 */
			do_action( 'wpmudev-recommended-plugins-before-parse-saved-registered-plugins' );

			$registered_plugins = get_site_option( self::OPTION_NAME, array() );

			if ( is_array( $registered_plugins ) ) {
				foreach ( $registered_plugins as $plugin_basename => $registered_plugin ) {
					$registered_plugin_object = new WPMUDEV_Recommended_Plugins_Notice_Registered_Plugin( $plugin_basename );
					$registered_plugin_object->from_array( $registered_plugin );
					$this->registered_plugins[ $plugin_basename ] = $registered_plugin_object;
				}
			}
		}

		/**
		 * Save registered plugins to storage
		 */
		public function save_registered_plugins() {
			$registered_plugins = array();

			foreach ( $this->registered_plugins as $registered_plugin ) {
				$registered_plugins[ $registered_plugin->get_basename() ] = $registered_plugin->to_array();
			}

			update_site_option( self::OPTION_NAME, $registered_plugins );
		}

		/**
		 * Un-dismiss the notice
		 *
		 * @internal
		 */
		public function un_dismiss() {
			$dismissed_wp_pointers = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
			$dismissed_wp_pointers = explode( ',', (string) $dismissed_wp_pointers );

			$dismissed = ( is_array( $dismissed_wp_pointers ) && in_array( self::POINTER_NAME, $dismissed_wp_pointers, true ) );

			if ( $dismissed ) {
				foreach ( $dismissed_wp_pointers as $key => $dismissed_wp_pointer ) {
					if ( self::POINTER_NAME === $dismissed_wp_pointer ) {
						unset( $dismissed_wp_pointers[ $key ] );
					}
				}

				$dismissed_wp_pointers = implode( ',', $dismissed_wp_pointers );

				update_user_meta( get_current_user_id(), 'dismissed_wp_pointers', $dismissed_wp_pointers );
			}
		}

		/**
		 * Reset saved registered plugins
		 *
		 * @internal
		 */
		public function reset_registered_plugins() {
			delete_site_option( self::OPTION_NAME );
		}

		/**
		 * @return WPMUDEV_Recommended_Plugins_Notice
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

	$GLOBALS['WPMUDEV_Recommended_Plugins_Notice'] = WPMUDEV_Recommended_Plugins_Notice::get_instance();
}
