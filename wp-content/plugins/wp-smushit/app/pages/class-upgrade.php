<?php
/**
 * Smush upgrade page class: Upgrade extends Abstract_Page.
 *
 * @since 3.2.3
 * @package Smush\App\Pages
 */

namespace Smush\App\Pages;

use Smush\App\Abstract_Page;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Upgrade
 */
class Upgrade extends Abstract_Page {

	/**
	 * Render the page.
	 */
	public function render() {
		?>
		<div class="<?php echo $this->settings->get( 'accessible_colors' ) ? 'sui-wrap sui-color-accessible' : 'sui-wrap'; ?>">
			<?php $this->render_inner_content(); ?>
		</div>
		<?php
	}

	/**
	 * Render inner content.
	 */
	public function render_inner_content() {
		$this->view( 'smush-upgrade-page' );
	}

	/**
	 * On load actions.
	 */
	public function on_load() {
		add_action(
			'admin_enqueue_scripts',
			function() {
				wp_enqueue_script( 'smush-sui', WP_SMUSH_URL . 'app/assets/js/smush-sui.min.js', array( 'jquery', 'clipboard' ), WP_SHARED_UI_VERSION, true );
				wp_enqueue_script( 'smush-wistia', '//fast.wistia.com/assets/external/E-v1.js', array(), WP_SMUSH_VERSION, true );
				wp_enqueue_style( 'smush-admin', WP_SMUSH_URL . 'app/assets/css/smush-admin.min.css', array(), WP_SMUSH_VERSION );
			}
		);
	}

	/**
	 * Common hooks for all screens.
	 */
	public function add_action_hooks() {
		add_filter( 'admin_body_class', array( $this, 'smush_body_classes' ) );
	}

}
