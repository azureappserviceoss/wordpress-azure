<?php
/**
 * Integrations page.
 *
 * @package Smush\App\Pages
 */

namespace Smush\App\Pages;

use Smush\App\Abstract_Page;
use Smush\App\Interface_Page;
use Smush\Core\Settings;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Integrations
 */
class Integrations extends Abstract_Page implements Interface_Page {
	/**
	 * Function triggered when the page is loaded before render any content.
	 */
	public function on_load() {
		add_action( 'smush_setting_column_right_inside', array( $this, 'settings_desc' ), 10, 2 );
	}

	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		$class = WP_Smush::is_pro() ? 'smush-integrations-wrapper wp-smush-pro' : 'smush-integrations-wrapper';

		$this->add_meta_box(
			'integrations',
			__( 'Integrations', 'wp-smushit' ),
			array( $this, 'integrations_meta_box' ),
			null,
			array( $this, 'common_meta_box_footer' ),
			'main',
			array(
				'box_class'         => "sui-box {$class}",
				'box_content_class' => 'sui-box-body sui-upsell-items',
			)
		);
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
	 * Integrations meta box.
	 *
	 * Free and pro version settings are shown in same section. For free users, pro settings won't be shown.
	 * To print full size smush, resize and backup in group, we hook at `smush_setting_column_right_end`.
	 */
	public function integrations_meta_box() {
		$this->view(
			'integrations/meta-box',
			array(
				'basic_features'    => Settings::$basic_features,
				'is_pro'            => WP_Smush::is_pro(),
				'integration_group' => $this->settings->get_integrations_fields(),
				'settings'          => $this->settings->get(),
			)
		);
	}

	/**
	 * Show additional descriptions for settings.
	 *
	 * @param string $setting_key Setting key.
	 */
	public function settings_desc( $setting_key = '' ) {
		if ( empty( $setting_key ) || 's3' !== $setting_key ) {
			return;
		}
		?>
		<span class="sui-description sui-toggle-description" id="s3-desc">
			<?php esc_html_e( 'Note: For this process to happen automatically you need automatic smushing enabled.', 'wp-smushit' ); ?>
		</span>
		<?php
	}
}
