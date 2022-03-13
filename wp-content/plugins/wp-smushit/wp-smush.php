<?php
/**
 * WP Smush plugin
 *
 * Reduce image file sizes, improve performance and boost your SEO using the free
 * <a href="https://wpmudev.com/">WPMU DEV</a> WordPress Smush API.
 *
 * @link              http://wpmudev.com/project/wp-smush-pro/
 * @since             1.0.0
 * @package           WP_Smush
 *
 * @wordpress-plugin
 * Plugin Name:       Smush
 * Plugin URI:        http://wordpress.org/plugins/wp-smushit/
 * Description:       Reduce image file sizes, improve performance and boost your SEO using the free <a href="https://wpmudev.com/">WPMU DEV</a> WordPress Smush API.
 * Version:           3.9.5
 * Author:            WPMU DEV
 * Author URI:        https://profiles.wordpress.org/wpmudev/
 * License:           GPLv2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-smushit
 * Domain Path:       /languages/
 */

/*
This plugin was originally developed by Alex Dunae (http://dialect.ca/).

Copyright 2007-2020 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WP_SMUSH_VERSION' ) ) {
	define( 'WP_SMUSH_VERSION', '3.9.5' );
}
// Used to define body class.
if ( ! defined( 'WP_SHARED_UI_VERSION' ) ) {
	define( 'WP_SHARED_UI_VERSION', 'sui-2-11-0' );
}
if ( ! defined( 'WP_SMUSH_BASENAME' ) ) {
	define( 'WP_SMUSH_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'WP_SMUSH_API' ) ) {
	define( 'WP_SMUSH_API', 'https://smushpro.wpmudev.com/1.0/' );
}
if ( ! defined( 'WP_SMUSH_UA' ) ) {
	define( 'WP_SMUSH_UA', 'WP Smush/' . WP_SMUSH_VERSION . '; ' . network_home_url() );
}
if ( ! defined( 'WP_SMUSH_DIR' ) ) {
	define( 'WP_SMUSH_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WP_SMUSH_URL' ) ) {
	define( 'WP_SMUSH_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WP_SMUSH_MAX_BYTES' ) ) {
	define( 'WP_SMUSH_MAX_BYTES', 5000000 );
}
if ( ! defined( 'WP_SMUSH_PREMIUM_MAX_BYTES' ) ) {
	define( 'WP_SMUSH_PREMIUM_MAX_BYTES', 32000000 );
}
if ( ! defined( 'WP_SMUSH_TIMEOUT' ) ) {
	define( 'WP_SMUSH_TIMEOUT', 150 );
}

/**
 * To support Smushing on staging sites like SiteGround staging where staging site urls are different
 * but redirects to main site url. Remove the protocols and www, and get the domain name.*
 * If Set to false, WP Smush switch backs to the Old Sync Optimisation.
 */
$site_url = str_replace( array( 'http://', 'https://', 'www.' ), '', site_url() );
// Compat with WPMU DEV staging.
$wpmu_host = isset( $_SERVER['WPMUDEV_HOSTING_ENV'] ) && 'staging' === sanitize_text_field( wp_unslash( $_SERVER['WPMUDEV_HOSTING_ENV'] ) );
if ( ! defined( 'WP_SMUSH_ASYNC' ) ) {
	if ( ( ! empty( $_SERVER['SERVER_NAME'] ) && 0 !== strpos( $site_url, sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) ) ) || $wpmu_host ) {
		define( 'WP_SMUSH_ASYNC', false );
	} else {
		define( 'WP_SMUSH_ASYNC', true );
	}
}

/**
 * If we are activating a version, while having another present and activated.
 * Leave in the Pro version, if it is available.
 *
 * @since 2.9.1
 */
if ( WP_SMUSH_BASENAME !== plugin_basename( __FILE__ ) ) {
	$pro_installed = false;
	if ( file_exists( WP_PLUGIN_DIR . '/wp-smush-pro/wp-smush.php' ) ) {
		$pro_installed = true;
	}

	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( is_plugin_active( 'wp-smush-pro/wp-smush.php' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		update_site_option( 'smush_deactivated', 1 );
		return; // Return to avoid errors with free-dashboard module.
	} elseif ( $pro_installed && is_plugin_active( WP_SMUSH_BASENAME ) ) {
		deactivate_plugins( WP_SMUSH_BASENAME );
		// If WordPress is already in the process of activating - return.
		if ( defined( 'WP_SANDBOX_SCRAPING' ) && WP_SANDBOX_SCRAPING ) {
			return;
		}
		activate_plugin( plugin_basename( __FILE__ ) );
	}
}

/* @noinspection PhpIncludeInspection */
require_once WP_SMUSH_DIR . 'core/class-installer.php';
register_activation_hook( __FILE__, array( 'Smush\\Core\\Installer', 'smush_activated' ) );
register_deactivation_hook( __FILE__, array( 'Smush\\Core\\Installer', 'smush_deactivated' ) );

// Init the plugin and load the plugin instance for the first time.
add_action( 'plugins_loaded', array( 'WP_Smush', 'get_instance' ) );

if ( ! class_exists( 'WP_Smush' ) ) {
	/**
	 * Class WP_Smush
	 */
	class WP_Smush {

		/**
		 * Plugin instance.
		 *
		 * @since 2.9.0
		 * @var null|WP_Smush
		 */
		private static $instance = null;

		/**
		 * Plugin core.
		 *
		 * @since 2.9.0
		 * @var Smush\Core\Core
		 */
		private $core;

		/**
		 * Plugin admin.
		 *
		 * @since 2.9.0
		 * @var Smush\App\Admin
		 */
		private $admin;

		/**
		 * Plugin API.
		 *
		 * @since 3.0
		 * @var Smush\Core\Api\Smush_API
		 */
		private $api = '';

		/**
		 * Media library UI.
		 *
		 * @since 3.4.0
		 * @var Smush\App\Media_Library
		 */
		private $library;

		/**
		 * Stores the value of validate_install function.
		 *
		 * @var bool $is_pro
		 */
		private static $is_pro;

		/**
		 * Return the plugin instance.
		 *
		 * @return WP_Smush
		 */
		public static function get_instance() {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * WP_Smush constructor.
		 */
		private function __construct() {
			spl_autoload_register( array( $this, 'autoload' ) );

			add_action( 'admin_init', array( '\\Smush\\Core\\Installer', 'upgrade_settings' ) );
			add_action( 'current_screen', array( '\\Smush\\Core\\Installer', 'maybe_create_table' ) );
			add_action( 'admin_init', array( $this, 'register_free_modules' ) );

			// The dash-notification actions are hooked into "init" with a priority of 10.
			add_action( 'init', array( $this, 'register_pro_modules' ), 5 );

			$this->init();
		}

		/**
		 * Autoload method.
		 *
		 * @since 3.1.0
		 * @param string $class  Class name to autoload.
		 */
		public function autoload( $class ) {
			// Project-specific namespace prefix.
			$prefix = 'Smush\\';

			// Does the class use the namespace prefix?
			$len = strlen( $prefix );
			if ( 0 !== strncmp( $prefix, $class, $len ) ) {
				// No, move to the next registered autoloader.
				return;
			}

			// Get the relative class name.
			$relative_class = substr( $class, $len );

			$path = explode( '\\', strtolower( str_replace( '_', '-', $relative_class ) ) );
			$file = array_pop( $path );
			$file = WP_SMUSH_DIR . implode( '/', $path ) . '/class-' . $file . '.php';

			// If the file exists, require it.
			if ( file_exists( $file ) ) {
				/* @noinspection PhpIncludeInspection */
				require $file;
			}
		}

		/**
		 * Init core module.
		 *
		 * @since 2.9.0
		 */
		private function init() {
			try {
				$this->api = new Smush\Core\Api\Smush_API( Smush\Core\Helper::get_wpmudev_apikey() );
			} catch ( Exception $e ) {
				$this->api = '';
			}

			$this->validate_install();

			$this->core    = new Smush\Core\Core();
			$this->library = new Smush\App\Media_Library( $this->core() );
			if ( is_admin() ) {
				$this->admin = new Smush\App\Admin( $this->library() );
			}

			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::add_command( 'smush', '\\Smush\\Core\\CLI' );
			}
		}

		/**
		 * Getter method for core.
		 *
		 * @since 2.9.0
		 *
		 * @return Smush\Core\Core
		 */
		public function core() {
			return $this->core;
		}

		/**
		 * Getter method for core.
		 *
		 * @since 2.9.0
		 *
		 * @return Smush\App\Admin
		 */
		public function admin() {
			return $this->admin;
		}

		/**
		 * Getter method for core.
		 *
		 * @since 3.0
		 *
		 * @return Smush\Core\Api\Smush_API
		 */
		public function api() {
			return $this->api;
		}

		/**
		 * Getter method for library.
		 *
		 * @since 3.4.0
		 *
		 * @return Smush\App\Media_Library
		 */
		public function library() {
			return $this->library;
		}

		/**
		 * Return PRO status.
		 *
		 * @since 2.9.0
		 *
		 * @return bool
		 */
		public static function is_pro() {
			return self::$is_pro;
		}

		/**
		 * Filters the rating message, include stats if greater than 1Mb
		 *
		 * @param string $message  Message text.
		 *
		 * @return string
		 */
		public function wp_smush_rating_message( $message ) {
			if ( empty( $this->core()->stats ) ) {
				$this->core()->setup_global_stats();
			}

			$savings    = $this->core()->stats;
			$show_stats = false;

			// If there is any saving, greater than 1Mb, show stats.
			if ( ! empty( $savings ) && ! empty( $savings['bytes'] ) && $savings['bytes'] > 1048576 ) {
				$show_stats = true;
			}

			$message = "Hey %s, you've been using %s for a while now, and we hope you're happy with it.";

			// Conditionally Show stats in rating message.
			if ( $show_stats ) {
				$message .= sprintf( " You've smushed <strong>%s</strong> from %d images already, improving the speed and SEO ranking of this site!", $savings['human'], $savings['total_images'] );
			}
			$message .= " We've spent countless hours developing this free plugin for you, and we would really appreciate it if you dropped us a quick rating!";

			return $message;
		}

		/**
		 * Register sub-modules.
		 * Only for wordpress.org members.
		 */
		public function register_free_modules() {
			if ( false === strpos( WP_SMUSH_DIR, 'wp-smushit' ) ) {
				return;
			}

			/* @noinspection PhpIncludeInspection */
			require_once WP_SMUSH_DIR . 'core/external/free-dashboard/module.php';
			/* @noinspection PhpIncludeInspection */
			require_once WP_SMUSH_DIR . 'core/external/plugin-notice/notice.php';

			// Add the Mailchimp group value.
			add_action(
				'frash_subscribe_form_fields',
				function ( $mc_list_id ) {
					if ( '4b14b58816' === $mc_list_id ) {
						echo '<input type="hidden" id="mce-group[53]-53-1" name="group[53][2]" value="2" />';
					}
				}
			);

			// Register the current plugin.
			do_action(
				'wdev-register-plugin',
				/* 1             Plugin ID */ WP_SMUSH_BASENAME,
				/* 2          Plugin Title */ 'Smush',
				/* 3 https://wordpress.org */ '/plugins/wp-smushit/',
				/* 4      Email Button CTA */ __( 'Get Fast!', 'wp-smushit' ),
				/* 5  Mailchimp List id for the plugin - e.g. 4b14b58816 is list id for Smush */ '4b14b58816'
			);

			// The rating message contains 2 variables: user-name, plugin-name.
			add_filter( 'wdev-rating-message-' . WP_SMUSH_BASENAME, array( $this, 'wp_smush_rating_message' ) );
			// The email message contains 1 variable: plugin-name.
			add_filter(
				'wdev-email-message-' . WP_SMUSH_BASENAME,
				function () {
					return "You're awesome for installing %s! Make sure you get the most out of it, boost your Google PageSpeed score with these tips and tricks - just for users of Smush!";
				}
			);

			// Recommended plugin notice.
			do_action(
				'wpmudev-recommended-plugins-register-notice',
				WP_SMUSH_BASENAME,
				__( 'Smush', 'wp-smushit' ),
				\Smush\App\Admin::$plugin_pages,
				array( 'before', '.sui-wrap .sui-floating-notices, .sui-wrap .sui-upgrade-page' )
			);
		}

		/**
		 * Register sub-modules.
		 * Only for WPMU DEV Members.
		 */
		public function register_pro_modules() {
			if ( ! file_exists( WP_SMUSH_DIR . 'core/external/dash-notice/wpmudev-dash-notification.php' ) ) {
				return;
			}

			// Register items for the dashboard plugin.
			global $wpmudev_notices;
			$wpmudev_notices[] = array(
				'id'      => 912164,
				'name'    => 'WP Smush Pro',
				'screens' => array(
					'upload',
				),
			);

			/* @noinspection PhpIncludeInspection */
			require_once WP_SMUSH_DIR . 'core/external/dash-notice/wpmudev-dash-notification.php';
		}

		/**
		 * Check if user is premium member, check for API key.
		 *
		 * @param bool $manual  Is it a manual check? Default: false.
		 */
		public function validate_install( $manual = false ) {
			if ( isset( self::$is_pro ) && ! $manual ) {
				return;
			}

			// No API key set, always false.
			$api_key = Smush\Core\Helper::get_wpmudev_apikey();

			if ( empty( $api_key ) ) {
				return;
			}

			// Flag to check if we need to revalidate the key.
			$revalidate = false;

			$api_auth = get_site_option( 'wp_smush_api_auth' );

			// Check if we need to revalidate.
			if ( ! $api_auth || empty( $api_auth ) || ! is_array( $api_auth ) || empty( $api_auth[ $api_key ] ) ) {
				$api_auth   = array();
				$revalidate = true;
			} else {
				$last_checked = $api_auth[ $api_key ]['timestamp'];
				$valid        = $api_auth[ $api_key ]['validity'];

				// Difference in hours.
				$diff = ( current_time( 'timestamp' ) - $last_checked ) / HOUR_IN_SECONDS;

				if ( 24 < $diff ) {
					$revalidate = true;
				}
			}

			// If we are supposed to validate API, update the results in options table.
			if ( $revalidate || $manual ) {
				if ( empty( $api_auth[ $api_key ] ) ) {
					// For api key resets.
					$api_auth[ $api_key ] = array();

					// Storing it as valid, unless we really get to know from API call.
					$valid                            = 'valid';
					$api_auth[ $api_key ]['validity'] = 'valid';
				}

				// This is the first check.
				if ( ! isset( $api_auth[ $api_key ]['timestamp'] ) ) {
					$api_auth[ $api_key ]['timestamp'] = current_time( 'timestamp' );
				}

				$request = $this->api()->check( $manual );

				if ( ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request ) ) {
					// Update the timestamp only on successful attempts.
					$api_auth[ $api_key ]['timestamp'] = current_time( 'timestamp' );
					update_site_option( 'wp_smush_api_auth', $api_auth );

					$result = json_decode( wp_remote_retrieve_body( $request ) );
					if ( ! empty( $result->success ) && $result->success ) {
						$valid = 'valid';
						update_site_option( 'wp-smush-cdn_status', $result->data );
					} else {
						$valid = 'invalid';
					}
				} elseif ( ! isset( $valid ) || 'valid' !== $valid ) {
					// Invalidate only in case when it was not valid before.
					$valid = 'invalid';
				}

				$api_auth[ $api_key ]['validity'] = $valid;

				// Update API validity.
				update_site_option( 'wp_smush_api_auth', $api_auth );
			}

			self::$is_pro = isset( $valid ) && 'valid' === $valid;
		}
	}
}
