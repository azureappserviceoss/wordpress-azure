<?php
/**
 * Abstract class for Smush view: Abstract_Page
 *
 * @package Smush\App
 */

namespace Smush\App;

use Smush\Core\Helper;
use Smush\Core\Settings;
use WP_Smush;
use WPMUDEV_Dashboard;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Abstract_Page
 */
abstract class Abstract_Page {

	/**
	 * Page slug.
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * Page ID.
	 *
	 * @var false|string
	 */
	private $page_id;

	/**
	 * Meta boxes array.
	 *
	 * @var array
	 */
	protected $meta_boxes = array();

	/**
	 * Modals to render.
	 *
	 * @since 3.8.3
	 *
	 * @var array
	 */
	protected $modals = array();

	/**
	 * Submenu tabs.
	 *
	 * @var array
	 */
	protected $tabs = array();

	/**
	 * Settings instance for faster access.
	 *
	 * @since 3.0
	 *
	 * @var Settings
	 */
	protected $settings;

	/**
	 * Link to upgrade.
	 *
	 * @var string $upgrade_url
	 */
	protected $upgrade_url = 'https://wpmudev.com/project/wp-smush-pro/';

	/**
	 * Abstract_Page constructor.
	 *
	 * @param string $slug     Page slug.
	 * @param string $title    Page title.
	 * @param bool   $parent   Does a page have a parent (will be added as a sub menu).
	 * @param bool   $nextgen  Is that a NextGen subpage.
	 */
	public function __construct( $slug, $title, $parent = false, $nextgen = false ) {
		$this->slug     = $slug;
		$this->settings = Settings::get_instance();

		if ( ! $parent ) {
			$this->page_id = add_menu_page(
				$title,
				$title,
				'manage_options',
				$this->slug,
				null,
				$this->get_menu_icon()
			);
		} else {
			$this->page_id = add_submenu_page(
				$parent,
				$title,
				$title,
				$nextgen ? 'NextGEN Manage gallery' : 'manage_options',
				$this->slug,
				array( $this, 'render' )
			);
		}

		// No need to load these action on parent pages, as they are just placeholders for sub pages.
		if ( $parent ) {
			add_filter( 'load-' . $this->page_id, array( $this, 'on_load' ) );
			add_action( 'load-' . $this->page_id, array( $this, 'register_meta_boxes' ) );
			add_filter( 'load-' . $this->page_id, array( $this, 'add_action_hooks' ) );
		}
	}

	/**
	 * Common hooks for all screens
	 *
	 * @since 2.9.0
	 */
	public function add_action_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Notices.
		//add_action( 'admin_notices', array( $this, 'smush_upgrade_notice' ) );
		//add_action( 'network_admin_notices', array( $this, 'smush_upgrade_notice' ) );
		add_action( 'admin_notices', array( $this, 'smush_deactivated' ) );
		add_action( 'network_admin_notices', array( $this, 'smush_deactivated' ) );
		add_action( 'wp_smush_header_notices', array( $this, 'settings_updated' ) );
		// Check for any stored API message and show it.
		add_action( 'wp_smush_header_notices', array( $this, 'show_api_message' ) );

		add_action( 'admin_notices', array( $this, 'smush_dash_required' ) );
		add_action( 'network_admin_notices', array( $this, 'smush_dash_required' ) );
		add_action( 'wp_smush_render_setting_row', array( $this, 'render_row' ), 10, 4 );

		add_filter( 'admin_body_class', array( $this, 'smush_body_classes' ) );

		// Filter query args to remove from the URL.
		add_filter( 'removable_query_args', array( $this, 'add_removable_query_args' ) );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 3.8.8
	 *
	 * @param string $hook Hook from where the call is made.
	 */
	public function enqueue_scripts( $hook ) {}

	/**
	 * Return the admin menu slug
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Load an admin view.
	 *
	 * @param string $name  View name = file name.
	 * @param array  $args  Arguments.
	 * @param string $dir   Directory for the views. Default: views.
	 */
	public function view( $name, $args = array(), $dir = 'views' ) {
		$file    = WP_SMUSH_DIR . "app/{$dir}/{$name}.php";
		$content = '';

		if ( is_file( $file ) ) {
			ob_start();

			if ( isset( $args['id'] ) ) {
				$args['orig_id'] = $args['id'];
				$args['id']      = str_replace( '/', '-', $args['id'] );
			}
			extract( $args );

			/* @noinspection PhpIncludeInspection */
			include $file;

			$content = ob_get_clean();
		}

		echo $content;
	}

	/**
	 * Shows Notice for free users, displays a discount coupon
	 */
	public function smush_upgrade_notice() {
		// Return, If a pro user, or not super admin, or don't have the admin privileges.
		if ( WP_Smush::is_pro() || ! current_user_can( 'edit_others_posts' ) || ! is_super_admin() ) {
			return;
		}

		// Return if notice is already dismissed.
		if ( get_site_option( 'wp-smush-hide_upgrade_notice' ) ) {
			return;
		}

		$core = WP_Smush::get_instance()->core();

		$install_type = get_site_option( 'wp-smush-install-type', false );

		if ( ! $install_type ) {
			$install_type = $core->smushed_count > 0 ? 'existing' : 'new';
			update_site_option( 'wp-smush-install-type', $install_type );
		}

		// Prepare notice.
		if ( 'new' === $install_type ) {
			/* translators: 1. opening 'strong' tag, 2. closing 'strong' tag. */
			$message = __( 'Thanks for installing Smush! %1$sGet a free trial + 30%% OFF%2$s Smush Pro for a limited time - an exclusive welcome discount for free version users! Grab it while it lasts.', 'wp-smushit' );
		} else {
			/* translators: 1. opening 'strong' tag, 2. closing 'strong' tag. */
			$message = __( 'Thanks for updating Smush! %1$sGet 30%% OFF Smush Pro + Free Trial%2$s - Did you know we now offer Smush Pro only plans? With a limited time intro discount! Grab it while it lasts.', 'wp-smushit' );
		}

		$upgrade_url = add_query_arg(
			array(
				'coupon'       => 'SMUSH30OFF',
				'checkout'     => 0,
				'utm_source'   => 'smush',
				'utm_medium'   => 'plugin',
				'utm_campaign' => 'smush_dashboard_upgrade_notice',
			),
			$this->upgrade_url
		);
		?>
		<div class="notice smush-notice">
			<div class="smush-notice-logo">
				<img
					src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/incsub-logo.png' ); ?>"
					srcset="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/incsub-logo@2x.png' ); ?> 2x"
					alt="<?php esc_html_e( 'Smush CDN', 'wp-smushit' ); ?>"
				>
			</div>
			<div class="smush-notice-message<?php echo 'new' === $install_type ? ' wp-smush-fresh' : ' wp-smush-existing'; ?>">
				<?php printf( esc_html( $message ), '<strong>', '</strong>' ); ?><br/>
				<small><?php esc_html_e( '*Only admin users can see this message', 'wp-smushit' ); ?></small>
			</div>
			<div class="smush-notice-cta">
				<a href="<?php echo esc_url( $upgrade_url ); ?>" class="smush-notice-act button-primary" target="_blank">
					<?php esc_html_e( 'Try Smush Pro Free', 'wp-smushit' ); ?>
				</a>
				<button class="smush-notice-dismiss smush-dismiss-welcome" data-msg="<?php esc_html_e( 'Saving', 'wp-smushit' ); ?>">
					<?php esc_html_e( 'Dismiss', 'wp-smushit' ); ?>
				</button>
			</div>
		</div>
		<?php
	}

	/**
	 * Display a admin notice about plugin deactivation.
	 */
	public function smush_deactivated() {
		// Display only in backend for administrators.
		if ( ! is_admin() || ! is_super_admin() || ! get_site_option( 'smush_deactivated' ) ) {
			return;
		}
		?>
		<div class="updated">
			<p>
				<?php esc_html_e( 'Smush Free was deactivated. You have Smush Pro active!', 'wp-smushit' ); ?>
			</p>
		</div>
		<?php
		delete_site_option( 'smush_deactivated' );
	}

	/**
	 * Show notice when Smush Pro is installed only with a key.
	 */
	public function smush_dash_required() {
		if ( WP_Smush::is_pro() || ! is_super_admin() || ( class_exists( 'WPMUDEV_Dashboard' ) && WPMUDEV_Dashboard::$api->has_key() ) ) {
			return;
		}

		// Do not show on free versions of the plugin.
		if ( false !== strpos( WP_SMUSH_DIR, 'wp-smushit' ) ) {
			return;
		}

		$function = is_multisite() ? 'network_admin_url' : 'admin_url';

		$url = wp_nonce_url(
			$function( 'update.php?action=install-plugin&plugin=install_wpmudev_dash' ),
			'install-plugin_install_wpmudev_dash'
		);
		?>
		<div class="notice smush-notice">
			<div class="smush-notice-logo">
				<img
					src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/incsub-logo.png' ); ?>"
					srcset="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/incsub-logo@2x.png' ); ?> 2x"
					alt="<?php esc_html_e( 'Smush CDN', 'wp-smushit' ); ?>"
				>
			</div>
			<div class="smush-notice-message">
				<?php esc_html_e( 'Smush Pro requires the WPMU DEV Dashboard plugin to unlock pro features. Please make sure you have installed, activated and logged into the Dashboard.', 'wp-smushit' ); ?>
			</div>
			<div class="smush-notice-cta">
				<?php if ( class_exists( 'WPMUDEV_Dashboard' ) && ! WPMUDEV_Dashboard::$api->has_key() ) : ?>
					<a href="<?php echo esc_url( network_admin_url( 'admin.php?page=wpmudev' ) ); ?>" class="smush-notice-act button-primary" target="_blank">
						<?php esc_html_e( 'Log In', 'wp-smushit' ); ?>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( $url ); ?>" class="smush-notice-act button-primary">
						<?php esc_html_e( 'Install Plugin', 'wp-smushit' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add Share UI Class.
	 *
	 * @param string $classes  Classes string.
	 *
	 * @return string
	 */
	public function smush_body_classes( $classes ) {
		// Exit if function doesn't exists.
		if ( ! function_exists( 'get_current_screen' ) ) {
			return $classes;
		}

		// If not on plugin page.
		if ( ! in_array( get_current_screen()->id, Admin::$plugin_pages, true ) && false === strpos( get_current_screen()->id, 'page_smush' ) ) {
			return $classes;
		}

		// Remove old wpmud class from body of smush page to avoid style conflict.
		$classes = str_replace( 'wpmud ', '', $classes );

		$classes .= ' ' . WP_SHARED_UI_VERSION;

		return $classes;
	}

	/**
	 * Filters the query args to remove from the URL.
	 *
	 * @since 3.8.0
	 *
	 * @param array $args Removable query args.
	 * @return array
	 */
	public function add_removable_query_args( $args ) {
		$args[] = 'notice';
		return $args;
	}

	/**
	 * Allows to register meta boxes for the page.
	 *
	 * @since 2.9.0
	 */
	public function register_meta_boxes() {}

	/**
	 * Add meta box.
	 *
	 * @param string   $id               Meta box ID.
	 * @param string   $title            Meta box title.
	 * @param callable $callback         Callback for meta box content.
	 * @param callable $callback_header  Callback for meta box header.
	 * @param callable $callback_footer  Callback for meta box footer.
	 * @param string   $context          Meta box context.
	 * @param array    $args             Arguments.
	 */
	public function add_meta_box( $id, $title, $callback = null, $callback_header = null, $callback_footer = null, $context = 'main', $args = array() ) {
		$default_args = array(
			'box_class'         => 'sui-box',
			'box_header_class'  => 'sui-box-header',
			'box_content_class' => 'sui-box-body',
			'box_footer_class'  => 'sui-box-footer',
		);

		$args = wp_parse_args( $args, $default_args );

		if ( ! isset( $this->meta_boxes[ $this->slug ] ) ) {
			$this->meta_boxes[ $this->slug ] = array();
		}

		if ( ! isset( $this->meta_boxes[ $this->slug ][ $context ] ) ) {
			$this->meta_boxes[ $this->slug ][ $context ] = array();
		}

		if ( ! isset( $this->meta_boxes[ $this->slug ][ $context ] ) ) {
			$this->meta_boxes[ $this->slug ][ $context ] = array();
		}

		$meta_box = array(
			'id'              => $id,
			'title'           => $title,
			'callback'        => $callback,
			'callback_header' => $callback_header,
			'callback_footer' => $callback_footer,
			'args'            => $args,
		);

		if ( $meta_box ) {
			$this->meta_boxes[ $this->slug ][ $context ][ $id ] = $meta_box;
		}
	}

	/**
	 * Render the page
	 */
	public function render() {
		// Shared UI wrapper with accessible color option.
		$classes = $this->settings->get( 'accessible_colors' ) ? 'sui-wrap sui-color-accessible' : 'sui-wrap';
		echo '<div class="' . esc_attr( $classes ) . ' wrap-' . esc_attr( $this->slug ) . '">';

		$this->render_page_header();
		$this->render_modals();
		$this->render_inner_content();

		// Nonce field.
		wp_nonce_field( 'save_wp_smush_options', 'wp_smush_options_nonce', '' );

		// Close shared ui wrapper.
		echo '</div>';
	}

	/**
	 * Renders all the modals to be used in the page.
	 *
	 * @since 3.7.0
	 */
	private function render_modals() {
		$hide_quick_setup = false !== get_option( 'skip-smush-setup' );

		// Show configure screen for only a new installation and for only network admins.
		if ( ( ! is_multisite() && ! $hide_quick_setup ) || ( is_multisite() && ! is_network_admin() && ! $this->settings->is_network_enabled() && ! $hide_quick_setup ) ) {
			$this->modals['onboarding']     = array();
			$this->modals['checking-files'] = array();
		}

		// Show new features modal if the modal wasn't dismissed.
		if ( get_site_option( 'wp-smush-show_upgrade_modal' ) ) {
			// Display only on single installs and on Network admin for multisites.
			if ( ( ! is_multisite() && $hide_quick_setup ) || ( is_multisite() && is_network_admin() ) ) {
				$cta_url = $this->get_url( 'smush-bulk' );
				if ( is_multisite() ) {
					$access = get_site_option( 'wp-smush-networkwide' );
					if ( '1' === $access || ( is_array( $access ) && in_array( 'bulk', $access, true ) ) ) {
						$cta_url = $this->get_url( 'smush' );
					}
				}

				$this->modals['updated'] = array(
					'cta_url' => $cta_url,
				);
			}
		}

		// Render all modals.
		foreach ( $this->modals as $modal_file => $args ) {
			$this->view( $modal_file, $args, 'modals' );
		}
	}

	/**
	 * Get the current screen tab
	 *
	 * @return string
	 */
	public function get_current_tab() {
		$tabs = $this->get_tabs();
		$view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRING );

		if ( array_key_exists( $view, $tabs ) ) {
			return $view;
		}

		if ( empty( $tabs ) ) {
			return false;
		}

		reset( $tabs );
		return key( $tabs );
	}

	/**
	 * Display tabs navigation
	 */
	public function show_tabs() {
		$this->view(
			'tabs',
			array(
				'tabs' => $this->get_tabs(),
			)
		);
	}

	/**
	 * Get a tab URL
	 *
	 * @param string $tab  Tab ID.
	 *
	 * @return string
	 */
	public function get_tab_url( $tab ) {
		$tabs = $this->get_tabs();
		if ( ! isset( $tabs[ $tab ] ) ) {
			return '';
		}

		if ( is_multisite() && is_network_admin() ) {
			return network_admin_url( 'admin.php?page=' . $this->slug . '&view=' . $tab );
		} else {
			return admin_url( 'admin.php?page=' . $this->slug . '&view=' . $tab );
		}
	}

	/**
	 * Get the list of tabs for this screen
	 *
	 * @return array
	 */
	protected function get_tabs() {
		return apply_filters( 'wp_smush_admin_page_tabs_' . $this->slug, $this->tabs );
	}

	/**
	 * Render inner content.
	 */
	protected function render_inner_content() {
		$this->view( 'smush-page' );
	}

	/**
	 * Render meta box.
	 *
	 * @param string $context  Meta box context. Default: main.
	 */
	protected function do_meta_boxes( $context = 'main' ) {
		if ( empty( $this->meta_boxes[ $this->slug ][ $context ] ) ) {
			return;
		}

		do_action_ref_array( 'wp_smush_admin_do_meta_boxes_' . $this->slug, array( &$this ) );

		foreach ( $this->meta_boxes[ $this->slug ][ $context ] as $id => $box ) {
			$args = array(
				'title'           => $box['title'],
				'id'              => $id,
				'callback'        => $box['callback'],
				'callback_header' => $box['callback_header'],
				'callback_footer' => $box['callback_footer'],
				'args'            => $box['args'],
			);
			$this->view( 'meta-box', $args );
		}
	}

	/**
	 * Check if there is any meta box for a given context.
	 *
	 * @param string $context  Meta box context.
	 *
	 * @return bool
	 */
	protected function has_meta_boxes( $context ) {
		return ! empty( $this->meta_boxes[ $this->slug ][ $context ] );
	}

	/**
	 * Check if view exists.
	 *
	 * @param string $name  View name = file name.
	 *
	 * @return bool
	 */
	protected function view_exists( $name ) {
		$file = WP_SMUSH_DIR . "app/views/{$name}.php";
		return is_file( $file );
	}

	/**
	 * Smush icon svg image
	 *
	 * @return string
	 */
	private function get_menu_icon() {
		ob_start();
		?>
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_2_23)">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10 2C11.5823 2 13.129 2.46921 14.4446 3.34826C15.7601 4.22731 16.7855 5.47672 17.391 6.93853C17.9965 8.40034 18.155 10.0089 17.8463 11.5607C17.762 11.9845 17.6439 12.3988 17.494 12.8C17.498 12.7002 17.5 12.6002 17.5 12.5C17.5 10.5109 16.7098 8.60324 15.3033 7.19672C13.8968 5.7902 11.9891 5.00001 10 5.00001C8.51664 5.00001 7.06659 5.43987 5.83323 6.26398C4.59986 7.08809 3.63856 8.25944 3.0709 9.62989C2.65354 10.6375 2.46275 11.7195 2.506 12.8C2.1731 11.909 2.00001 10.961 2.00001 9.99999C2.00001 7.87826 2.84286 5.84343 4.34315 4.34314C5.84344 2.84285 7.87827 2 10 2ZM13.8268 19.2388C12.6157 19.7405 11.3239 19.9966 10.0256 20H10.0152C10.005 20 9.99483 20 9.98464 20H9.97449C9.33106 19.9983 8.68626 19.9346 8.04911 19.8078C6.1093 19.422 4.32746 18.4696 2.92893 17.0711C1.53041 15.6726 0.578002 13.8907 0.192151 11.9509C-0.193701 10.0111 0.00433534 8.00041 0.761211 6.17315C1.51809 4.34589 2.79982 2.78411 4.44431 1.6853C6.0888 0.586488 8.02219 0 10 0C12.6522 0 15.1957 1.05359 17.0711 2.92895C18.9464 4.80432 20 7.34783 20 9.99999C20 11.9778 19.4135 13.9112 18.3147 15.5557C17.2159 17.2002 15.6541 18.4819 13.8268 19.2388ZM10.0139 18H9.98611C9.59872 17.9982 9.21338 17.9214 8.85196 17.7717C8.30378 17.5446 7.83523 17.1601 7.50559 16.6667C7.17594 16.1734 7 15.5933 7 15C7 14.2043 7.31606 13.4413 7.87866 12.8787C8.44127 12.3161 9.20435 12 10 12C10.5933 12 11.1734 12.176 11.6667 12.5056C12.1601 12.8353 12.5446 13.3038 12.7716 13.852C12.9987 14.4002 13.0581 15.0033 12.9424 15.5853C12.8266 16.1672 12.5409 16.7018 12.1213 17.1213C11.7017 17.5409 11.1672 17.8266 10.5853 17.9424C10.3962 17.98 10.2049 17.9991 10.0139 18ZM14.996 14.8C14.9459 13.5466 14.426 12.3549 13.5355 11.4645C12.5978 10.5268 11.3261 10 10 10C9.01109 10 8.04439 10.2932 7.22214 10.8427C6.3999 11.3921 5.75903 12.173 5.38059 13.0866C5.15387 13.6339 5.02745 14.2142 5.004 14.8C4.67336 14.0818 4.50001 13.2975 4.50001 12.5C4.50265 11.0421 5.08296 9.64471 6.11384 8.61383C7.14471 7.58296 8.54212 7.00265 10 7.00001C11.0878 7.00001 12.1512 7.3226 13.0556 7.92695C13.9601 8.53129 14.665 9.39027 15.0813 10.3953C15.4976 11.4003 15.6065 12.5061 15.3943 13.573C15.3097 13.9984 15.1755 14.41 14.996 14.8Z" fill="#F0F6FC"/>
            </g>
            <defs>
                <clipPath id="clip0_2_23">
                    <rect width="20" height="20" fill="white"/>
                </clipPath>
            </defs>
        </svg>

		<?php
		$svg = ob_get_clean();

		return 'data:image/svg+xml;base64,' . base64_encode( $svg );
	}

	/**
	 * Get the documentation url.
	 *
	 * @since 3.8.6
	 *
	 * @return string
	 */
	public function get_doc_url() {
		$doc = 'https://wpmudev.com/docs/wpmu-dev-plugins/smush/';
		if ( WP_Smush::is_pro() ) {
			$doc = 'https://wpmudev.com/docs/wpmu-dev-plugins/smush/?utm_source=smush&utm_medium=plugin&utm_campaign=smush_pluginlist_docs';
		}

		switch ( $this->get_slug() ) {
			case 'smush-bulk':
				$doc .= '#bulk-smush';
				break;

			case 'smush-directory':
				$doc .= '#directory-smush';
				break;

			case 'smush-lazy-load':
				$doc .= '#lazy-loading';
				break;

			case 'smush-cdn':
				$doc .= '#cdn';
				break;

			case 'smush-webp':
				$doc .= '#local-webp';
				break;

			case 'smush-integrations':
				$doc .= '#integrations';
				break;

			case 'smush-tools':
				$doc .= '#tools';
				break;

			case 'smush-settings':
				$doc .= '#settings';
				break;
		}

		return $doc;
	}

	/**
	 * Prints out the page header for bulk smush page.
	 *
	 * @return void
	 */
	public function render_page_header() {
		$current_screen = get_current_screen();
		?>
		<div class="sui-header">
			<h1 class="sui-header-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<div class="sui-actions-right">
				<?php
				if (
					! is_network_admin() &&
					( 'smush-bulk' === $this->get_slug() || in_array( $this->page_id, array( 'nextgen-gallery_page_wp-smush-nextgen-bulk', 'gallery_page_wp-smush-nextgen-bulk' ), true ) )
				) :
					?>
					<?php $data_type = in_array( $current_screen->id, array( 'nextgen-gallery_page_wp-smush-nextgen-bulk', 'gallery_page_wp-smush-nextgen-bulk' ), true ) ? 'nextgen' : 'media'; ?>
					<button class="sui-button wp-smush-scan" data-tooltip="<?php esc_attr_e( 'Lets you check if any images can be further optimized. Useful after changing settings.', 'wp-smushit' ); ?>" data-type="<?php echo esc_attr( $data_type ); ?>">
						<span class="sui-loading-text wp-smush-default-text">
							<i class="sui-icon-update" aria-hidden="true"></i>
							<?php esc_html_e( 'Re-Check Images', 'wp-smushit' ); ?>
						</span>
						<span class="sui-hidden wp-smush-completed-text">
							<i class="sui-icon-check-tick" aria-hidden="true"></i>
							<?php esc_html_e( 'Check Complete', 'wp-smushit' ); ?>
						</span>
						<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
					</button>
				<?php endif; ?>
				<?php if ( ! apply_filters( 'wpmudev_branding_hide_doc_link', false ) ) : ?>
					<a href="<?php echo esc_url( $this->get_doc_url() ); ?>" class="sui-button sui-button-ghost" target="_blank">
						<i class="sui-icon-academy" aria-hidden="true"></i> <?php esc_html_e( 'Documentation', 'wp-smushit' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="sui-floating-notices">
			<div role="alert" id="wp-smush-ajax-notice" class="sui-notice" aria-live="assertive"></div>
			<?php do_action( 'wp_smush_header_notices', $this->get_current_tab() ); ?>
		</div>
		<?php
	}

	/**
	 * Display a stored API message.
	 */
	public function show_api_message() {
		// Do not show message for any other users.
		if ( ! is_network_admin() && ! is_super_admin() ) {
			return;
		}

		$api_message = get_site_option( 'wp-smush-api_message', array() );
		$api_message = current( $api_message );

		// Return if the API message is not set or user dismissed it earlier.
		if ( empty( $api_message ) || ! is_array( $api_message ) || 'show' !== $api_message['status'] ) {
			return;
		}

		$message      = empty( $api_message['message'] ) ? '' : $api_message['message'];
		$message_type = ( is_array( $api_message ) && ! empty( $api_message['type'] ) ) ? $api_message['type'] : 'info';
		$type_class   = 'warning' === $message_type ? 'sui-notice-warning' : 'sui-notice-info';
		?>

		<div class="sui-notice <?php echo esc_attr( $type_class ); ?>" id="wp-smush-api-message">
			<div class="sui-notice-content">
				<div class="sui-notice-message">
					<i class="sui-notice-icon sui-icon-info" aria-hidden="true"></i>
					<p><?php echo wp_kses_post( $message ); ?></p>
				</div>
				<div class="sui-notice-actions">
					<button class="sui-button-icon">
						<i class="sui-icon-check" aria-hidden="true"></i>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Dismiss', 'wp-smushit' ); ?></span>
					</button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Displays a admin notice for settings update.
	 *
	 * @TODO: Refactor. This is a weird way to check for settings update.
	 */
	public function settings_updated() {
		// Check if network-wide settings are enabled, do not show settings updated message.
		if ( is_multisite() && ! is_network_admin() && ! Settings::can_access( 'bulk' ) ) {
			return;
		}

		// Show settings saved message.
		if ( ! get_option( 'wp-smush-settings_updated' ) ) {
			return;
		}

		$core = WP_Smush::get_instance()->core();

		// Default message.
		$message = esc_html__( 'Your settings have been updated!', 'wp-smushit' );
		// Notice class.
		$message_class = 'success';

		if ( 'smush-cdn' === $this->get_slug() ) {
			$cdn = $this->settings->get_setting( 'wp-smush-cdn_status' );
			if ( isset( $cdn->cdn_enabling ) && $cdn->cdn_enabling ) {
				$message = esc_html__( 'Your settings have been saved and changes are now propagating to the CDN. Changes can take up to 30 minutes to take effect but your images will continue to be served in the mean time, please be patient.', 'wp-smushit' );
			}
		}

		// Additional message if we got work to do!
		$resmush_count = is_array( $core->resmush_ids ) && count( $core->resmush_ids ) > 0;
		$smush_count   = is_array( $core->remaining_count ) && $core->remaining_count > 0;

		if ( $smush_count || $resmush_count ) {
			$message_class = 'warning';
			// Show link to bulk smush tab from other tabs.
			$bulk_smush_link = 'smush-bulk' === $this->get_slug() ? '<a href="#" class="wp-smush-trigger-bulk">' : '<a href="' . $this->get_page_url() . '">';
			/* translators: %1$s - <a>, %2$s - </a> */
			$message .= ' ' . sprintf( esc_html__( 'You have images that need smushing. %1$sBulk smush now!%2$s', 'wp-smushit' ), $bulk_smush_link, '</a>' );
		}
		?>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				window.SUI.openNotice(
					'wp-smush-ajax-notice',
					'<p><?php echo $message; ?></p>',
					{
						type: '<?php echo $message_class; ?>',
						icon: 'info',
					}
				);
			});
		</script>
		<?php
		// Remove the option.
		$this->settings->delete_setting( 'wp-smush-settings_updated' );
	}

	/**
	 * Check if the page should be rendered.
	 *
	 * @since 3.2.2
	 * @since 3.8.0  Added $tab parameter.
	 *
	 * @param string $page  Page to check for.
	 *
	 * @return bool
	 */
	public static function should_render( $page = '' ) {
		// Render all pages on single site installs.
		if ( ! is_multisite() ) {
			return true;
		}

		if ( empty( $page ) ) {
			return false;
		}

		$access = get_site_option( 'wp-smush-networkwide' );

		if ( ! $access || in_array( $page, array( 'directory', 'webp', 'configs' ), true ) ) {
			return is_network_admin();
		}

		if ( '1' === $access ) {
			return ! is_network_admin();
		}

		if ( is_array( $access ) ) {
			if ( is_network_admin() && ! in_array( $page, $access, true ) ) {
				return true;
			}

			if ( ! is_network_admin() && in_array( $page, $access, true ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Return this menu page URL
	 *
	 * @since 3.5.0
	 *
	 * @return string
	 */
	public function get_page_url() {
		if ( is_multisite() && is_network_admin() ) {
			global $_parent_pages;

			if ( isset( $_parent_pages[ $this->slug ] ) ) {
				$parent_slug = $_parent_pages[ $this->slug ];
				if ( $parent_slug && ! isset( $_parent_pages[ $parent_slug ] ) ) {
					$url = network_admin_url( add_query_arg( 'page', $this->slug, $parent_slug ) );
				} else {
					$url = network_admin_url( 'admin.php?page=' . $this->slug );
				}
			} else {
				$url = '';
			}

			return esc_url( $url );
		} else {
			return menu_page_url( $this->slug, false );
		}
	}

	/**
	 * Get a page URL.
	 *
	 * @param string $page  Page slug.
	 *
	 * @return string
	 */
	public function get_url( $page = '' ) {
		if ( ! $page ) {
			$page = $this->get_slug();
		}

		if ( is_multisite() && is_network_admin() ) {
			return network_admin_url( 'admin.php?page=' . $page );
		}

		return admin_url( 'admin.php?page=' . $page );
	}

	/**
	 * Render setting row.
	 *
	 * @param string $name     Setting name.
	 * @param bool   $value    Setting value.
	 * @param bool   $disable  Disable row/option.
	 * @param bool   $upsell   Is the row an upsell.
	 */
	public function render_row( $name, $value, $disable = false, $upsell = false ) {
		$this->view( 'settings-row', compact( 'name', 'value', 'disable', 'upsell' ) );
	}

	/**
	 * Enqueue scripts.
	 * Used by the Tutorials and Dashboard pages.
	 */
	protected function enqueue_tutorials_scripts() {
		wp_enqueue_script(
			'smush-tutorials',
			WP_SMUSH_URL . 'app/assets/js/smush-tutorials.min.js',
			array( 'wp-i18n' ),
			WP_SMUSH_VERSION,
			true
		);

		$strings = array(
			'tutorials'         => esc_html__( 'Tutorials', 'wp-smushit' ),
			'tutorials_link'    => 'https://wpmudev.com/blog/tutorials/tutorial-category/smush-pro/',
			'tutorials_strings' => array(
				array(
					'loading'      => esc_html__( 'Loading tutorials...', 'wp-smushit' ),
					'min_read'     => esc_html__( 'min read', 'wp-smushit' ),
					'read_article' => esc_html__( 'Read article', 'wp-smushit' ),
				),
			),
		);

		wp_localize_script( 'smush-tutorials', 'smush_tutorials', $strings );
	}

	/**
	 * Enqueue the scripts for configs.
	 * Used in the Settings and Dashboard pages.
	 *
	 * @since 3.9.0
	 */
	protected function enqueue_configs_scripts() {
		// Configs are only used on single installs and on the network admin on MU.
		if ( is_multisite() && ! is_network_admin() ) {
			return;
		}

		wp_enqueue_script(
			'smush-react-configs',
			WP_SMUSH_URL . 'app/assets/js/smush-react-configs.min.js',
			array( 'wp-i18n', 'smush-sui' ),
			WP_SMUSH_VERSION,
			true
		);

		wp_add_inline_script(
			'smush-react-configs',
			'wp.i18n.setLocaleData( ' . wp_json_encode( $this->get_locale_data() ) . ', "wp-smushit" );',
			'before'
		);

		// Configs.
		wp_localize_script(
			'smush-react-configs',
			'smushReact',
			array(
				'hideBranding' => apply_filters( 'wpmudev_branding_hide_branding', false ),
				'isPro'        => WP_Smush::is_pro(),
				'links'        => array(
					'configsPage'  => network_admin_url( 'admin.php?page=smush-settings&view=configs' ),
					'accordionImg' => WP_SMUSH_URL . 'app/assets/images/smush-config-icon@2x.png',
					'hubConfigs'   => 'https://wpmudev.com/hub2/configs/my-configs',
					'hubWelcome'   => 'https://wpmudev.com/hub-welcome/?utm_source=smush&utm_medium=plugin&utm_campaign=smush_hub_config',
				),
				'requestsData' => array(
					'root'           => esc_url_raw( rest_url( 'wp-smush/v1/' . \Smush\Core\Configs::OPTION_NAME ) ),
					'nonce'          => wp_create_nonce( 'wp_rest' ),
					'apiKey'         => \Smush\Core\Helper::get_wpmudev_apikey(),
					'hubBaseURL'     => defined( 'WPMUDEV_CUSTOM_API_SERVER' ) && WPMUDEV_CUSTOM_API_SERVER ? trailingslashit( WPMUDEV_CUSTOM_API_SERVER ) . 'api/hub/v1/package-configs' : null,
					// Hardcoding these because the Free version doesn't have the WDP ID header in wp-smushit.php.
					'pluginData'     => array(
						'name' => 'Smush' . ( WP_Smush::is_pro() ? ' Pro' : '' ),
						'id'   => '912164',
					),
					'pluginRequests' => array(
						'nonce'        => wp_create_nonce( 'smush_handle_config' ),
						'uploadAction' => 'smush_upload_config',
						'createAction' => 'smush_save_config',
						'applyAction'  => 'smush_apply_config',
					),
				),
			)
		);
	}

	/**
	 * Gets the translated strings for javascript translations.
	 *
	 * @since 3.8.5
	 * @since 3.9.0 Moved from Smush\App\Admin to here.
	 *
	 * @return array
	 */
	protected function get_locale_data() {
		$translations = get_translations_for_domain( 'wp-smushit' );

		$locale = array(
			'' => array(
				'domain' => 'wp-smushit',
				'lang'   => get_user_locale(),
			),
		);

		if ( ! empty( $translations->headers['Plural-Forms'] ) ) {
			$locale['']['plural_forms'] = $translations->headers['Plural-Forms'];
		}

		foreach ( $translations->entries as $msgid => $entry ) {
			$locale[ $msgid ] = $entry->translations;
		}

		return $locale;
	}
}
