<?php
/**
 * Tools page.
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
 * Class Tools
 */
class Tools extends Abstract_Page implements Interface_Page {
	/**
	 * Function triggered when the page is loaded before render any content.
	 */
	public function on_load() {
		add_action( 'smush_setting_column_right_inside', array( $this, 'detection_settings' ), 25, 2 );
	}

	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		$box_body_class = WP_Smush::is_pro() ? '' : 'sui-upsell-items';

		$this->add_meta_box(
			'tools',
			__( 'Tools', 'wp-smushit' ),
			array( $this, 'tools_meta_box' ),
			null,
			array( $this, 'common_meta_box_footer' ),
			'main',
			array(
				'box_content_class' => "sui-box-body {$box_body_class}",
			)
		);

		$this->modals['restore-images'] = array();
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
	 * Tools meta box.
	 *
	 * @since 3.2.1
	 */
	public function tools_meta_box() {
		$this->view(
			'tools/meta-box',
			array(
				'detection'     => $this->settings->get( 'detection' ),
				'backups_count' => count( WP_Smush::get_instance()->core()->mod->backup->get_attachments_with_backups() ),
			)
		);
	}

	/**
	 * Display a description in Tools - Image Resize Detection.
	 *
	 * @since 3.2.1
	 *
	 * @param string $name  Setting name.
	 */
	public function detection_settings( $name ) {
		// Add only to full size settings.
		if ( 'detection' !== $name ) {
			return;
		}
		?>

		<span class="sui-description sui-toggle-description">
			<?php esc_html_e( 'Note: The highlighting will only be visible to administrators – visitors won’t see the highlighting.', 'wp-smushit' ); ?>
			<?php if ( $this->settings->get( 'detection' ) ) : ?>
				<?php if ( $this->settings->get( 'cdn' ) && $this->settings->get( 'auto_resize' ) ) : ?>
					<div class="sui-notice smush-highlighting-notice">
						<div class="sui-notice-content">
							<div class="sui-notice-message">
								<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
								<p>
									<?php
									esc_html_e(
										'Note: Images served via the Smush CDN are automatically resized to fit their containers, these will be skipped.',
										'wp-smushit'
									);
									?>
								</p>
							</div>
						</div>
					</div>
				<?php else : ?>
					<div class="sui-notice sui-notice-info smush-highlighting-notice">
						<div class="sui-notice-content">
							<div class="sui-notice-message">
								<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
								<p>
									<?php
									printf(
									/* translators: %1$s: opening a tag, %2$s: closing a tag */
										esc_html__(
											'Incorrect image size highlighting is active. %1$sView the frontend%2$s of your website to see if any images aren\'t the correct size for their containers.',
											'wp-smushit'
										),
										'<a href="' . esc_url( home_url() ) . '" target="_blank">',
										'</a>'
									);
									?>
								</p>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="sui-notice sui-notice-warning smush-highlighting-warning sui-hidden">
					<div class="sui-notice-content">
						<div class="sui-notice-message">
							<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
							<p><?php esc_html_e( 'Almost there! To finish activating this feature you must save your settings.', 'wp-smushit' ); ?></p>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</span>
		<?php
	}
}
