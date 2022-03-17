<?php
/**
 * Auto resize functionality: Resize_Detection class
 *
 * @package Smush\Core\Modules
 * @version 2.8.0
 *
 * @author Joel James <joel@incsub.com>
 *
 * @copyright (c) 2018, Incsub (http://incsub.com)
 */

namespace Smush\Core\Modules;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Resize_Detection
 */
class Resize_Detection extends Abstract_Module {

	/**
	 * Is auto detection enabled.
	 *
	 * @var bool
	 */
	private $can_auto_detect = false;

	/**
	 * Resize_Detection constructor.
	 */
	public function init() {
		// Set auto resize flag.
		add_action( 'wp', array( $this, 'init_flags' ) );

		// Load js file that is required in public facing pages.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_resize_assets' ) );

		// Set a flag to media library images.
		add_filter( 'smush_cdn_image_tag', array( $this, 'skip_image_resize_detection' ) );

		// Generate markup for the template engine.
		add_action( 'wp_footer', array( $this, 'generate_markup' ) );
	}

	/**
	 * Check if auto resize can be performed.
	 *
	 * Allow only if current user is admin and auto resize
	 * detection is enabled in settings.
	 */
	public function init_flags() {
		// Only required for admin users.
		if ( $this->settings->get( 'detection' ) && current_user_can( 'manage_options' ) ) {
			$this->can_auto_detect = true;
		}
	}

	/**
	 * Enqueue JS files required in public pages.
	 *
	 * Enqueue resize detection js and css files to public
	 * facing side of the site. Load only if auto detect
	 * is enabled.
	 *
	 * @return void
	 */
	public function enqueue_resize_assets() {
		// Required only if auto detection is required.
		if ( ! $this->can_auto_detect ) {
			return;
		}

		// Required scripts for front end.
		wp_enqueue_script(
			'smush-resize-detection',
			WP_SMUSH_URL . 'app/assets/js/smush-rd.min.js',
			array( 'jquery' ),
			WP_SMUSH_VERSION,
			true
		);

		// Required styles for front end.
		wp_enqueue_style(
			'smush-resize-detection',
			WP_SMUSH_URL . 'app/assets/css/smush-rd.min.css',
			array(),
			WP_SMUSH_VERSION
		);

		// Define ajaxurl var.
		wp_localize_script(
			'smush-resize-detection',
			'wp_smush_resize_vars',
			array(
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'ajax_nonce'  => wp_create_nonce( 'smush_resize_nonce' ),
				// translators: %s - width, %s - height.
				'large_image' => sprintf( __( 'This image is too large for its container. Adjust the image dimensions to %1$s x %2$spx for optimal results.', 'wp-smushit' ), 'width', 'height' ),
				// translators: %s - width, %s - height.
				'small_image' => sprintf( __( 'This image is too small for its container. Adjust the image dimensions to %1$s x %2$spx for optimal results.', 'wp-smushit' ), 'width', 'height' ),
			)
		);
	}

	/**
	 * Generate markup for the template engine.
	 *
	 * @since 2.9
	 */
	public function generate_markup() {
		// Required only if auto detection is required.
		if ( ! $this->can_auto_detect ) {
			return;
		}
		?>
		<div id="smush-image-bar-toggle" class="closed">
			<i class="sui-icon-loader" aria-hidden="true"> </i>
		</div>
		<div id="smush-image-bar" class="closed">
			<h3><?php esc_html_e( 'Image Issues', 'wp-smushit' ); ?></h3>
			<p>
				<?php esc_html_e( 'The images listed below are being resized to fit a container. To avoid serving oversized or blurry images, try to match the images to their container sizes.', 'wp-smushit' ); ?>
			</p>

			<div id="smush-image-bar-items-bigger">
				<strong><?php esc_html_e( 'Oversized', 'wp-smushit' ); ?></strong>
			</div>
			<div id="smush-image-bar-items-smaller">
				<strong><?php esc_html_e( 'Undersized', 'wp-smushit' ); ?></strong>
			</div>

			<div id="smush-image-bar-notice">
				<p><?php esc_html_e( 'All images are properly sized', 'wp-smushit' ); ?></p>
			</div>
			<p id="smush-image-bar-notice-desc">
				<?php esc_html_e( 'Note: It’s not always easy to make this happen, fix up what you can.', 'wp-smushit' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Exclude images that are hosted on CDN and have auto resize enabled.
	 *
	 * @since 3.2.0
	 *
	 * @param string $image  Image tag.
	 *
	 * @return string
	 */
	public function skip_image_resize_detection( $image ) {
		// No need to add attachment id if auto detection is not enabled.
		if ( ! $this->can_auto_detect ) {
			return $image;
		}

		// CDN with auto resize need to be enabled.
		if ( ! $this->settings->get( 'cdn' ) || ! $this->settings->get( 'auto_resize' ) ) {
			return $image;
		}

		Helpers\Parser::add_attribute( $image, 'no-resize-detection' );

		return $image;
	}

}
