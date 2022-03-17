<?php
/**
 * Smush integration with WPBakery Page Builder: Composer class
 *
 * @package Smush\Core\Integrations
 * @since 3.2.1
 *
 * @author Anton Vanyukov <anton@incsub.com>
 *
 * @copyright (c) 2018, Incsub (http://incsub.com)
 */

namespace Smush\Core\Integrations;

use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Composer for WPBakery Page Builder integration.
 *
 * @since 3.2.1
 */
class Composer extends Abstract_Integration {

	/**
	 * Composer constructor.
	 *
	 * @since 3.2.1
	 */
	public function __construct() {
		$this->module = 'js_builder';
		$this->class  = 'free';

		$this->check_for_js_builder();

		parent::__construct();

		// Hook at the end of setting row to output a error div.
		add_action( 'smush_setting_column_right_inside', array( $this, 'additional_notice' ) );

		if ( $this->settings->get( 'js_builder' ) ) {
			add_filter( 'image_make_intermediate_size', array( $this, 'process_image_resize' ) );
		}
	}

	/**************************************
	 *
	 * OVERWRITE PARENT CLASS FUNCTIONALITY
	 */

	/**
	 * Filters the setting variable to add NextGen setting title and description
	 *
	 * @since 3.2.1
	 *
	 * @param array $settings Settings.
	 *
	 * @return mixed
	 */
	public function register( $settings ) {
		$settings[ $this->module ] = array(
			'label'       => esc_html__( 'Enable WPBakery Page Builder integration', 'wp-smushit' ),
			'short_label' => esc_html__( 'WPBakery Page Builder', 'wp-smushit' ),
			'desc'        => esc_html__( 'Allow smushing images resized in WPBakery Page Builder editor.', 'wp-smushit' ),
		);

		return $settings;
	}

	/**
	 * Show additional notice if the required plugins are not installed.
	 *
	 * @since 3.2.1
	 *
	 * @param string $name  Setting name.
	 */
	public function additional_notice( $name ) {
		if ( $this->module === $name && ! $this->enabled ) {
			?>
			<div class="sui-toggle-content">
				<div class="sui-notice">
					<div class="sui-notice-content">
						<div class="sui-notice-message">
							<i class="sui-notice-icon sui-icon-info" aria-hidden="true"></i>
							<p><?php esc_html_e( 'To use this feature you need be using WPBakery Page Builder.', 'wp-smushit' ); ?></p>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	/**************************************
	 *
	 * PUBLIC CLASSES
	 */

	/**
	 * Check if the file source is a registered attachment and if not - Smush it.
	 *
	 * TODO: with little adjustments this can be used for all page builders.
	 *
	 * @since 3.2.1
	 *
	 * @param string $image_src  Image src.
	 *
	 * @return string
	 */
	public function process_image_resize( $image_src ) {
		$vc_editable = filter_input( INPUT_GET, 'vc_editable', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
		$vc_action   = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );

		global $pagename, $vc_manager;

		/**
		 * There are three types of situations:
		 * 1. $vc_editable and $vc_action will be set in the frontend page builder
		 * 2. $pagename in the backend.
		 * 3. $vc_manager is a fallback (could possibly cause issues).
		 */
		if ( ( ! $vc_editable || 'vc_load_shortcode' !== $vc_action ) && ( ! isset( $pagename ) || 'page-builder' !== $pagename ) && ( ! $vc_manager || ! is_object( $vc_manager ) ) ) {
			return $image_src;
		}

		// Save the original image source.
		$vc_image = $image_src;

		// Remove the [width]x[height] params from URL.
		$size = array();
		if ( preg_match( '/(\d+)x(\d+)\.(?:' . implode( '|', array( 'gif', 'jpg', 'jpeg', 'png' ) ) . '){1}$/i', $image_src, $size ) ) {
			$image_src = str_replace( '-' . $size[1] . 'x' . $size[2], '', $image_src );
		}

		// Convert image src to URL.
		$upload_dir = wp_get_upload_dir();
		$image_url  = str_replace( $upload_dir['path'], $upload_dir['url'], $image_src );

		// Try to get the attachment ID.
		$attachment_id = attachment_url_to_postid( $image_url );

		if ( ! wp_attachment_is_image( $attachment_id ) ) {
			return $vc_image;
		}

		$image = image_get_intermediate_size( $attachment_id, array( $size[1], $size[2] ) );

		if ( $image ) {
			return $vc_image;
		}

		// Smush image. TODO: should we update the stats?
		WP_Smush::get_instance()->core()->mod->smush->do_smushit( $vc_image );

		return $vc_image;
	}

	/**************************************
	 *
	 * PRIVATE CLASSES
	 */

	/**
	 * Should only be active when WPBakery Page Builder is installed.
	 *
	 * @since 3.2.1
	 */
	private function check_for_js_builder() {
		// This function exists since WPBakery 4.0 (02.03.2014) and is listed
		// on their API docs. It should be stable enough to rely on it.
		// @see https://kb.wpbakery.com/docs/inner-api/vc_disable_frontend/
		$this->enabled = defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_disable_frontend' );
	}

}
