<?php
/**
 * Tutorials page
 *
 * @package Smush\App\Pages
 */

namespace Smush\App\Pages;

use Smush\App\Abstract_Page;
use Smush\App\Interface_Page;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Tutorials
 */
class Tutorials extends Abstract_Page implements Interface_Page {

	/**
	 * Function triggered when the page is loaded before render any content.
	 */
	public function on_load() {}

	/**
	 * Enqueue scripts.
	 *
	 * @since 3.9.0
	 *
	 * @param string $hook Hook from where the call is made.
	 */
	public function enqueue_scripts( $hook ) {
		$this->enqueue_tutorials_scripts();
	}

	/**
	 * Render page header.
	 */
	public function render_page_header() {
		$tutorials = add_query_arg(
			array(
				'utm_source'   => 'smush',
				'utm_medium'   => 'plugin',
				'utm_campaign' => 'smush_tutorials_page',
			),
			esc_url( 'https://wpmudev.com/blog/tutorials/tutorial-category/smush-pro/' )
		);

		?>
		<div class="sui-header">
			<h1 class="sui-header-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<div class="sui-actions-right">
				<?php if ( ! apply_filters( 'wpmudev_branding_hide_doc_link', false ) ) : ?>
					<a href="<?php echo esc_url( $tutorials ); ?>" target="_blank" class="sui-button sui-button-ghost">
						<span class="sui-icon-open-new-window" aria-hidden="true"></span>
						<?php esc_html_e( 'View All', 'wp-smushit' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

}
