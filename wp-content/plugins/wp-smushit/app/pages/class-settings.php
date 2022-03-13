<?php
/**
 * Settings page.
 *
 * @package Smush\App\Pages
 */

namespace Smush\App\Pages;

use Smush\App\Abstract_Page;
use Smush\App\Interface_Page;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Settings
 */
class Settings extends Abstract_Page implements Interface_Page {
	/**
	 * Function triggered when the page is loaded before render any content.
	 */
	public function on_load() {
		// Init the tabs.
		$this->tabs = apply_filters(
			'smush_setting_tabs',
			array(
				'general'       => __( 'General', 'wp-smushit' ),
				'configs'       => __( 'Configs', 'wp-smushit' ),
				'permissions'   => __( 'Permissions', 'wp-smushit' ),
				'data'          => __( 'Data & Settings', 'wp-smushit' ),
				'accessibility' => __( 'Accessibility', 'wp-smushit' ),
			)
		);

		// Disabled on all subsites.
		if ( ! is_multisite() || ! is_network_admin() ) {
			unset( $this->tabs['permissions'] );
		}

		add_action( 'smush_setting_column_right_inside', array( $this, 'usage_settings' ), 25, 2 );
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 3.9.0
	 *
	 * @param string $hook Hook from where the call is made.
	 */
	public function enqueue_scripts( $hook ) {
		// Scripts for Configs.
		$this->enqueue_configs_scripts();
	}

	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		$this->add_meta_box(
			'settings/general',
			__( 'General', 'wp-smushit' ),
			array( $this, 'general_meta_box' ),
			null,
			array( $this, 'common_meta_box_footer' ),
			'general'
		);

		if ( is_multisite() && is_network_admin() ) {
			$this->add_meta_box(
				'settings/permissions',
				__( 'Permissions', 'wp-smushit' ),
				array( $this, 'permissions_meta_box' ),
				null,
				array( $this, 'common_meta_box_footer' ),
				'permissions'
			);
		}

		$this->add_meta_box(
			'settings/data',
			__( 'Data & Settings', 'wp-smushit' ),
			array( $this, 'data_meta_box' ),
			null,
			array( $this, 'common_meta_box_footer' ),
			'data'
		);

		$this->add_meta_box(
			'settings/accessibility',
			__( 'Accessibility', 'wp-smushit' ),
			array( $this, 'accessibility_meta_box' ),
			null,
			array( $this, 'common_meta_box_footer' ),
			'accessibility'
		);

		if ( 'data' === $this->get_current_tab() ) {
			$this->modals['reset-settings'] = array();
		}
	}

	/**
	 * Display a description in Settings - Usage Tracking.
	 *
	 * @since 3.1.0
	 *
	 * @param string $name  Setting name.
	 */
	public function usage_settings( $name ) {
		// Add only to full size settings.
		if ( 'usage' !== $name ) {
			return;
		}
		?>

		<span class="sui-description sui-toggle-description">
			<?php
			esc_html_e( 'Note: Usage tracking is completely anonymous. We are only tracking what features you are/aren’t using to make our feature decisions more informed.', 'wp-smushit' );
			?>
		</span>
		<?php
	}

	/**
	 * Common footer meta box.
	 *
	 * @since 3.2.0
	 */
	public function common_meta_box_footer() {
		$this->view( 'meta-box-footer', array(), 'common' );
	}

	/**
	 * General settings meta box.
	 */
	public function general_meta_box() {
		$link = WP_Smush::is_pro() ? 'https://wpmudev.com/translate/projects/wp-smushit/' : 'https://translate.wordpress.org/projects/wp-plugins/wp-smushit';

		$site_locale = get_locale();

		if ( 'en' === $site_locale || 'en_US' === $site_locale ) {
			$site_language = 'English';
		} else {
			require_once ABSPATH . 'wp-admin/includes/translation-install.php';
			$translations  = wp_get_available_translations();
			$site_language = isset( $translations[ $site_locale ] ) ? $translations[ $site_locale ]['native_name'] : __( 'Error detecting language', 'wp-smushit' );
		}

		$this->view(
			'settings/general-meta-box',
			array(
				'site_language'    => $site_language,
				'tracking'         => (bool) $this->settings->get( 'usage' ),
				'translation_link' => $link,
			)
		);
	}

	/**
	 * Permissions meta box.
	 */
	public function permissions_meta_box() {
		$this->view(
			'settings/permissions-meta-box',
			array(
				'networkwide' => get_site_option( 'wp-smush-networkwide' ),
			)
		);
	}

	/**
	 * Data & Settings meta box.
	 */
	public function data_meta_box() {
		$this->view(
			'settings/data-meta-box',
			array(
				'keep_data' => (bool) $this->settings->get( 'keep_data' ),
			)
		);
	}

	/**
	 * Accessibility meta box.
	 */
	public function accessibility_meta_box() {
		$this->view(
			'settings/accessibility-meta-box',
			array(
				'accessible_colors' => (bool) $this->settings->get( 'accessible_colors' ),
			)
		);
	}
}
