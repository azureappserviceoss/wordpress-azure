<?php
/**
 * Smush integration with Gutenberg editor: Gutenberg class
 *
 * @package Smush\Core\Integrations
 * @since 2.8.1
 *
 * @author Anton Vanyukov <anton@incsub.com>
 *
 * @copyright (c) 2018, Incsub (http://incsub.com)
 */

namespace Smush\Core\Integrations;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Gutenberg for Gutenberg integration.
 *
 * @since 2.8.1
 */
class Gutenberg extends Abstract_Integration {

	/**
	 * Gutenberg constructor.
	 *
	 * @since 2.8.1
	 */
	public function __construct() {
		$this->module = 'gutenberg';
		$this->class  = 'free';

		$this->check_for_gutenberg();

		parent::__construct();

		if ( ! $this->enabled ) {
			// Disable setting if Gutenberg is not active.
			add_filter( 'wp_smush_integration_status_' . $this->module, '__return_true' );

			// Hook at the end of setting row to output an error div.
			add_action( 'smush_setting_column_right_inside', array( $this, 'integration_error' ) );

			return;
		}

		// Show submit button when Gutenberg is active.
		add_filter( 'wp_smush_integration_show_submit', '__return_true' );

		// Register gutenberg block assets.
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_gb' ) );
	}

	/**************************************
	 *
	 * OVERWRITE PARENT CLASS FUNCTIONALITY
	 */

	/**
	 * Filters the setting variable to add Gutenberg setting title and description.
	 *
	 * @since 2.8.1
	 *
	 * @param array $settings  Settings array.
	 *
	 * @return mixed
	 */
	public function register( $settings ) {
		$settings[ $this->module ] = array(
			'label'       => esc_html__( 'Show Smush stats in Gutenberg blocks', 'wp-smushit' ),
			'short_label' => esc_html__( 'Gutenberg Support', 'wp-smushit' ),
			'desc'        => esc_html__(
				'Add statistics and the manual smush button to Gutenberg blocks that
							display images.',
				'wp-smushit'
			),
		);

		return $settings;
	}

	/**************************************
	 *
	 * PUBLIC CLASSES
	 */

	/**
	 * Prints the message for Gutenberg setup.
	 *
	 * @since 2.8.1
	 *
	 * @param string $setting_key  Settings key.
	 */
	public function integration_error( $setting_key ) {
		// Return if not Gutenberg integration.
		if ( $this->module !== $setting_key ) {
			return;
		}
		?>
		<div class="sui-toggle-content">
			<div class="sui-notice">
				<div class="sui-notice-content">
					<div class="sui-notice-message">
						<i class="sui-notice-icon sui-icon-info" aria-hidden="true"></i>
						<p><?php esc_html_e( 'To use this feature you need to install and activate the Gutenberg plugin.', 'wp-smushit' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueue Gutenberg block assets for backend editor.
	 *
	 * `wp-blocks`: includes block type registration and related functions.
	 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
	 * `wp-i18n`: To internationalize the block's text.
	 *
	 * @since 2.8.1
	 */
	public function enqueue_gb() {
		$enabled = $this->settings->get( $this->module );

		if ( ! $enabled ) {
			return;
		}

		// Gutenberg block scripts.
		wp_enqueue_script(
			'smush-gutenberg',
			WP_SMUSH_URL . 'app/assets/js/smush-blocks.min.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
			WP_SMUSH_VERSION,
			true
		);
	}

	/**************************************
	 *
	 * PRIVATE CLASSES
	 */

	/**
	 * Make sure we only enqueue when Gutenberg is active.
	 *
	 * For WordPress pre 5.0 - only when Gutenberg plugin is installed.
	 * For WordPress 5.0+ - only when Classic Editor is NOT installed.
	 *
	 * @since 3.0.2
	 */
	private function check_for_gutenberg() {
		global $wp_version;

		if ( ! function_exists( 'is_plugin_active' ) ) {
			/* @noinspection PhpIncludeInspection */
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Check if WordPress 5.0 or higher.
		$is_wp5point0 = version_compare( $wp_version, '4.9.9', '>' );

		if ( $is_wp5point0 ) {
			$this->enabled = ! is_plugin_active( 'classic-editor/classic-editor.php' );
		} else {
			$this->enabled = is_plugin_active( 'gutenberg/gutenberg.php' );
		}
	}

}
