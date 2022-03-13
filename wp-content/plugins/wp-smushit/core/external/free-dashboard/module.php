<?php
/**
 * WPMUDEV Frash - Free Dashboard Notification module.
 * Used by wordpress.org hosted plugins.
 *
 * @version 1.3
 * @author  Incsub (Philipp Stracker)
 * @package wdev_frash
 */

if ( ! class_exists( 'WDev_Frash' ) ) {
	/**
	 * Class WDev_Frash
	 */
	class WDev_Frash {

		/**
		 * List of all registered plugins.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		protected $plugins = array();

		/**
		 * Module options that are stored in database.
		 * Timestamps are stored here.
		 *
		 * Note that this option is stored in site-meta for multisite installs.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		protected $stored = array();

		/**
		 * User id /API Key for Mailchimp subscriber list
		 *
		 * @since 1.2
		 *
		 * @var string
		 */
		private $mc_user_id = '53a1e972a043d1264ed082a5b';

		/**
		 * Initializes and returns the singleton instance.
		 *
		 * @since  1.0.0
		 */
		public static function instance() {
			static $instance = null;

			if ( null === $instance ) {
				$instance = new WDev_Frash();
			}

			return $instance;
		}

		/**
		 * Set up the WDev_Frash module. Private singleton constructor.
		 *
		 * @since  1.0.0
		 */
		private function __construct() {
			$this->read_stored_data();

			$this->add_action( 'wdev-register-plugin', 5 );
			$this->add_action( 'load-index.php' );

			$this->add_action( 'wp_ajax_frash_act' );
			$this->add_action( 'wp_ajax_frash_dismiss' );
		}

		/**
		 * Load persistent module-data from the WP Database.
		 *
		 * @since  1.0.0
		 */
		protected function read_stored_data() {
			$data = get_site_option( 'wdev-frash', false, false );

			if ( ! is_array( $data ) ) {
				$data = array();
			}

			// A list of all plugins with timestamp of first registration.
			if ( ! isset( $data['plugins'] ) || ! is_array( $data['plugins'] ) ) {
				$data['plugins'] = array();
			}

			// A list with pending messages and earliest timestamp for display.
			if ( ! isset( $data['queue'] ) || ! is_array( $data['queue'] ) ) {
				$data['queue'] = array();
			}

			// A list with all messages that were handles already.
			if ( ! isset( $data['done'] ) || ! is_array( $data['done'] ) ) {
				$data['done'] = array();
			}

			$this->stored = $data;
		}

		/**
		 * Save persistent module-data to the WP database.
		 *
		 * @since  1.0.0
		 */
		protected function store_data() {
			update_site_option( 'wdev-frash', $this->stored );
		}

		/**
		 * Action handler for 'wdev-register-plugin'
		 * Register an active plugin.
		 *
		 * @since  1.0.0
		 * @param  string $plugin_id   WordPress plugin-ID (see: plugin_basename).
		 * @param  string $title       Plugin name for display.
		 * @param  string $url_wp      URL to the plugin on wp.org (domain not needed).
		 * @param  string $cta_email   Title of the Email CTA button.
		 * @param  string $mc_list_id  Required. Mailchimp mailing list id for the plugin.
		 */
		public function wdev_register_plugin( $plugin_id, $title, $url_wp, $cta_email = '', $mc_list_id = '' ) {
			// Ignore incorrectly registered plugins to avoid errors later.
			if ( empty( $plugin_id ) || empty( $title ) || empty( $url_wp ) ) {
				return;
			}

			if ( false === strpos( $url_wp, '://' ) ) {
				$url_wp = 'https://wordpress.org/' . trim( $url_wp, '/' );
			}

			$this->plugins[ $plugin_id ] = (object) array(
				'id'         => $plugin_id,
				'title'      => $title,
				'url_wp'     => $url_wp,
				'cta_email'  => $cta_email,
				'mc_list_id' => $mc_list_id,
			);

			/*
			 * When the plugin is registered the first time we store some infos
			 * in the persistent module-data that help us later to find out
			 * if/which message should be displayed.
			 */
			if ( empty( $this->stored['plugins'][ $plugin_id ] ) ) {
				// First register the plugin permanently.
				$this->stored['plugins'][ $plugin_id ] = time();

				$hash = md5( $plugin_id . '-email' );

				// Second schedule the messages to display.
				$this->stored['queue'][ $hash ] = array(
					'plugin'  => $plugin_id,
					'type'    => 'email',
					'show_at' => time(),  // Earliest time to display note.
				);

				$hash = md5( $plugin_id . '-rate' );

				$this->stored['queue'][ $hash ] = array(
					'plugin'  => $plugin_id,
					'type'    => 'rate',
					'show_at' => time() + WEEK_IN_SECONDS,
				);

				// Finally save the details.
				$this->store_data();
			}
		}

		/**
		 * Ajax handler called when the user chooses the CTA button.
		 *
		 * @since  1.0.0
		 */
		public function wp_ajax_frash_act() {
			$plugin = filter_input( INPUT_POST, 'plugin_id', FILTER_SANITIZE_STRING );
			$type   = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING );

			$this->mark_as_done( $plugin, $type, 'ok' );

			wp_die();
		}

		/**
		 * Ajax handler called when the user chooses the dismiss button.
		 *
		 * @since  1.0.0
		 */
		public function wp_ajax_frash_dismiss() {
			$plugin = filter_input( INPUT_POST, 'plugin_id', FILTER_SANITIZE_STRING );
			$type   = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING );

			$this->mark_as_done( $plugin, $type, 'ignore' );

			wp_die();
		}

		/**
		 * Action handler for 'load-index.php'
		 * Set-up the Dashboard notification.
		 *
		 * @since  1.0.0
		 */
		public function load_index_php() {
			if ( is_super_admin() ) {
				$this->add_action( 'all_admin_notices' );
			}
		}

		/**
		 * Action handler for 'admin_notices'
		 * Display the Dashboard notification.
		 *
		 * @since  1.0.0
		 */
		public function all_admin_notices() {
			$info = $this->choose_message();
			if ( ! $info ) {
				return;
			}

			$this->render_message( $info );
		}

		/**
		 * Check to see if there is a pending message to display and returns
		 * the message details if there is.
		 *
		 * Note that this function is only called on the main Dashboard screen
		 * and only when logged in as super-admin.
		 *
		 * @since  1.0.0
		 * @return object|false
		 *         string $type   [rate|email] Which message type?
		 *         string $plugin WordPress plugin ID?
		 */
		protected function choose_message() {
			$obj      = false;
			$chosen   = false;
			$earliest = false;

			$now = time();

			// The "current" time can be changed via $_GET to test the module.
			$custom_time = filter_input( INPUT_GET, 'time', FILTER_SANITIZE_STRING );
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG && ! empty( $custom_time ) ) {
				if ( ' ' === $custom_time[0] ) {
					$custom_time[0] = '+';
				}

				if ( $custom_time ) {
					$now = strtotime( $custom_time );
				}

				if ( ! $now ) {
					$now = time();
				}
			}

			$tomorrow = $now + DAY_IN_SECONDS;

			foreach ( $this->stored['queue'] as $hash => $item ) {
				$show_at   = (int) $item['show_at'];
				$is_sticky = ! empty( $item['sticky'] );

				if ( ! isset( $this->plugins[ $item['plugin'] ] ) ) {
					// Deactivated plugin before the message was displayed.
					continue;
				}
				$plugin = $this->plugins[ $item['plugin'] ];

				$can_display = true;
				if ( wp_is_mobile() ) {
					// Do not display rating message on mobile devices.
					if ( 'rate' === $item['type'] ) {
						$can_display = false;
					}
				}
				if ( 'email' === $item['type'] ) {
					// If we don't have mailchimp list id.
					if ( ! $plugin->mc_list_id || ! $plugin->cta_email ) {
						// Do not display email message with missing email params.
						$can_display = false;
					}
				}
				if ( $now < $show_at ) {
					// Do not display messages that are not due yet.
					$can_display = false;
				}

				if ( ! $can_display ) {
					continue;
				}

				if ( $is_sticky ) {
					// If sticky item is present then choose it!
					$chosen = $hash;
					break;
				} elseif ( ! $earliest || $earliest < $show_at ) {
					$earliest = $show_at;
					$chosen   = $hash;
					// Don't use `break` because a sticky item might follow...
					// Find the item with the earliest schedule.
				}
			}

			if ( $chosen ) {
				// Make the chosen item sticky.
				$this->stored['queue'][ $chosen ]['sticky'] = true;

				// Re-schedule other messages that are due today.
				foreach ( $this->stored['queue'] as $hash => $item ) {
					$show_at = (int) $item['show_at'];

					if ( empty( $item['sticky'] ) && $tomorrow > $show_at ) {
						$this->stored['queue'][ $hash ]['show_at'] = $tomorrow;
					}
				}

				// Save the changes.
				$this->store_data();

				$obj = (object) $this->stored['queue'][ $chosen ];
			}

			return $obj;
		}

		/**
		 * Moves a message from the queue to the done list.
		 *
		 * @since  1.0.0
		 * @param  string $plugin  Plugin ID.
		 * @param  string $type    Message type [rate|email].
		 * @param  string $state   Button clicked [ok|ignore].
		 */
		protected function mark_as_done( $plugin, $type, $state ) {
			$done_item = false;

			foreach ( $this->stored['queue'] as $hash => $item ) {
				unset( $this->stored['queue'][ $hash ]['sticky'] );

				if ( $item['plugin'] === $plugin && $item['type'] === $type ) {
					$done_item = $item;
					unset( $this->stored['queue'][ $hash ] );
				}
			}

			if ( $done_item ) {
				$done_item['state']      = $state;
				$done_item['hash']       = $hash;
				$done_item['handled_at'] = time();
				unset( $done_item['sticky'] );

				$this->stored['done'][] = $done_item;
				$this->store_data();
			}
		}

		/**
		 * Renders the actual Notification message.
		 *
		 * @since  1.0.0
		 *
		 * @param object $info  Plugin info.
		 */
		protected function render_message( $info ) {
			$plugin  = $this->plugins[ $info->plugin ];
			$css_url = plugin_dir_url( __FILE__ ) . 'assets/admin.css';
			$js_url  = plugin_dir_url( __FILE__ ) . 'assets/admin.js';

			wp_enqueue_style( 'wdev-frash-css', $css_url, array(), '1.3.0' );
			wp_enqueue_script( 'wpev-frash-js', $js_url, array(), '1.3.0', true );
			?>
			<div class="notice frash-notice frash-notice-<?php echo esc_attr( $info->type ); ?>" style="display:none">
				<input type="hidden" name="type" value="<?php echo esc_attr( $info->type ); ?>" />
				<input type="hidden" name="plugin_id" value="<?php echo esc_attr( $info->plugin ); ?>" />
				<input type="hidden" name="url_wp" value="<?php echo esc_attr( $plugin->url_wp ); ?>" />
				<?php
				if ( 'email' === $info->type ) {
					$this->render_email_message( $plugin );
				} elseif ( 'rate' === $info->type ) {
					$this->render_rate_message( $plugin );
				}
				?>
			</div>
			<?php
		}

		/**
		 * Output the contents of the email message.
		 * No return value. The code is directly output.
		 *
		 * @since  1.0.0
		 *
		 * @param object $plugin  Plugin info.
		 */
		protected function render_email_message( $plugin ) {
			$admin_email = get_site_option( 'admin_email' );

			$action = "https://edublogs.us1.list-manage.com/subscribe/post-json?u={$this->mc_user_id}&id={$plugin->mc_list_id}&c=?";

			/* translators: %s - plugin name */
			$msg = __( "We're happy that you've chosen to install %s! Are you interested in how to make the most of this plugin? How would you like a quick 5 day email crash course with actionable advice on building your membership site? Only the info you want, no subscription!", 'wdev_frash' );
			$msg = apply_filters( 'wdev-email-message-' . $plugin->id, $msg );

			$mc_list_id = $plugin->mc_list_id;

			?>
			<div class="frash-notice-logo"><span></span></div>
				<div class="frash-notice-message">
					<?php
					printf(
						esc_html( $msg ),
						'<strong>' . esc_html( $plugin->title ) . '</strong>'
					);
					?>
				</div>
				<div class="frash-notice-cta">
					<?php
					/**
					 * Fires before subscribe form renders.
					 *
					 * @since 1.3
					 *
					 * @param int $mc_list_id  Mailchimp list ID.
					 */
					do_action( 'frash_before_subscribe_form_render', $mc_list_id );
					?>
					<form action="<?php echo esc_attr( $action ); ?>" method="get" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
						<input type="email" name="EMAIL" class="email" id="mce-EMAIL" value="<?php echo esc_attr( $admin_email ); ?>" required="required"/>
						<button class="frash-notice-act button-primary" data-msg="<?php esc_attr_e( 'Thanks :)', 'wdev_frash' ); ?>" type="submit">
							<?php echo esc_html( $plugin->cta_email ); ?>
						</button>
						<button class="frash-notice-dismiss" data-msg="<?php esc_attr_e( 'Saving', 'wdev_frash' ); ?>">
							<?php esc_html_e( 'No thanks', 'wdev_frash' ); ?>
						</button>
						<?php
						/**
						 * Fires after subscribe form fields are rendered.
						 * Use this hook to add additional fields for on the sub form.
						 *
						 * Make sure that the additional field has is also present on the
						 * actual MC subscribe form.
						 *
						 * @since 1.3
						 *
						 * @param int $mc_list_id  Mailchimp list ID.
						 */
						do_action( 'frash_subscribe_form_fields', $mc_list_id );
						?>
					</form>
					<?php
					/**
					 * Fires after subscribe form is rendered
					 *
					 * @since 1.3
					 *
					 * @param int $mc_list_id  Mailchimp list ID.
					 */
					do_action( 'frash_before_subscribe_form_render', $mc_list_id );
					?>
				</div>
			<?php
		}

		/**
		 * Output the contents of the rate-this-plugin message.
		 * No return value. The code is directly output.
		 *
		 * @since  1.0.0
		 *
		 * @param object $plugin  Plugin info.
		 */
		protected function render_rate_message( $plugin ) {
			$user = wp_get_current_user();

			/* translators: %1$s - user name, %2$s - plugin name, %2$s - new line <br> */
			$msg = __( "Hey %1\$s, you've been using %2\$s for a while now, and we hope you're happy with it.", 'wdev_frash' ) . '%3$s' . __( "We've spent countless hours developing this free plugin for you, and we would really appreciate it if you dropped us a quick rating!", 'wdev_frash' );
			$msg = apply_filters( 'wdev-rating-message-' . $plugin->id, $msg );

			?>
			<div class="frash-notice-logo"><span></span></div>
				<div class="frash-notice-message">
					<?php
					printf(
						esc_html( $msg ),
						'<strong>' . esc_html( $user->display_name ) . '</strong>',
						'<strong>' . esc_html( $plugin->title ) . '</strong>',
						'<br>'
					);
					?>
				</div>
				<div class="frash-notice-cta">
					<button class="frash-notice-act button-primary" data-msg="<?php esc_attr_e( 'Thanks :)', 'wdev_frash' ); ?>">
						<?php
						printf( /* translators: %s - plugin name */
							esc_html__( 'Rate %s', 'wdev_frash' ),
							esc_html( $plugin->title )
						);
						?>
					</button>
					<button class="frash-notice-dismiss" data-msg="<?php esc_attr_e( 'Saving', 'wdev_frash' ); ?>">
						<?php esc_html_e( 'No thanks', 'wdev_frash' ); ?>
					</button>
				</div>
			<?php
		}

		/**
		 * Registers a new action handler. The callback function has the same
		 * name as the action hook.
		 *
		 * @since 1.0.0
		 *
		 * @param string $hook    Hook name.
		 * @param int    $params  Number of passed in params.
		 */
		protected function add_action( $hook, $params = 1 ) {
			$method_name = strtolower( $hook );
			$method_name = preg_replace( '/[^a-z0-9]/', '_', $method_name );
			add_action( $hook, array( $this, $method_name ), 5, $params );
		}
	}

	// Initialize the module.
	WDev_Frash::instance();
}
