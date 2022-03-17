<?php
/**
 * Local WebP page.
 *
 * @package Smush\App\Pages
 */

namespace Smush\App\Pages;

use Smush\App\Abstract_Summary_Page;
use Smush\App\Interface_Page;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class WebP
 */
class WebP extends Abstract_Summary_Page implements Interface_Page {

	/**
	 * Enqueue scripts.
	 *
	 * @since 3.9.0
	 *
	 * @param string $hook Hook from where the call is made.
	 */
	public function enqueue_scripts( $hook ) {
		// We only need this script for the wizard.
		if ( ! $this->is_wizard() ) {
			return;
		}

		wp_enqueue_script(
			'smush-react-webp',
			WP_SMUSH_URL . 'app/assets/js/smush-react-webp.min.js',
			array( 'wp-i18n', 'smush-sui', 'clipboard' ),
			WP_SMUSH_VERSION,
			true
		);

		wp_add_inline_script(
			'smush-react-webp',
			'wp.i18n.setLocaleData( ' . wp_json_encode( $this->get_locale_data() ) . ', "wp-smushit" );',
			'before'
		);

		$webp = WP_Smush::get_instance()->core()->mod->webp;

		// Defining this here to esc_html before using dangerouslySetInnerHTML on frontend.
		$third_step_message = ! is_multisite()
			? sprintf(
				/* translators: 1. opening 'b' tag, 2. closing 'b' tag */
				esc_html__(
					'WebP versions of existing images in the Media Library can only be created by ‘smushing’ the originals via the Bulk Smush page. Click %1$sConvert Now%2$s to be redirected to the Bulk Smush page to start smushing your images.',
					'wp-smushit'
				),
				'<b>',
				'</b>'
			)
			: sprintf(
				/* translators: 1. opening 'b' tag, 2. closing 'b' tag */
				esc_html__(
					'WebP versions of existing images in the Media Library can only be created by ‘smushing’ the originals using the %1$sBulk Smush%2$s tool on each subsite.',
					'wp-smushit'
				),
				'<b>',
				'</b>'
			);

		wp_localize_script(
			'smush-react-webp',
			'smushReact',
			array(
				'nonce'          => wp_create_nonce( 'wp-smush-webp-nonce' ),
				'isPro'          => WP_Smush::is_pro(),
				'detectedServer' => $webp->get_server_type(),
				'apacheRules'    => $webp->get_apache_code_to_print(),
				'nginxRules'     => $webp->get_nginx_code(),
				'startStep'      => true !== $webp->is_configured() || ! WP_Smush::is_pro() ? 1 : 3,
				'isMultisite'    => is_multisite(),
				'isWpmudevHost'  => isset( $_SERVER['WPMUDEV_HOSTED'] ),
				'isWhitelabel'   => apply_filters( 'wpmudev_branding_hide_doc_link', false ),
				'isS3Enabled'    => $this->settings->get( 's3' ) && ! WP_Smush::get_instance()->core()->s3->setting_status(),
				'thirdStepMsg'   => $third_step_message,
				'urls'           => array(
					'bulkPage' => esc_url( admin_url( 'admin.php?page=smush-bulk' ) ),
					'support'  => 'https://wpmudev.com/hub2/support/#get-support',
					'freeImg'  => esc_url( WP_SMUSH_URL . 'app/assets/images/smush-no-media.png' ),
					'webpDoc'  => 'https://wpmudev.com/blog/local-webp-support-smush/',
					'upsell'   => add_query_arg(
						array(
							'utm_source'   => 'smush',
							'utm_medium'   => 'plugin',
							'utm_campaign' => 'smush_webp_upgrade_button',
						),
						$this->upgrade_url
					),
				),
			)
		);
	}

	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		parent::register_meta_boxes();

		if ( $this->is_wizard() ) {
			return;
		}

		if ( ! $this->settings->get( 'webp_mod' ) ) {
			$this->add_meta_box(
				'webp/disabled',
				__( 'Local WebP', 'wp-smushit' ),
				null,
				array( $this, 'webp_meta_box_header' )
			);

			return;
		}

		$this->add_meta_box(
			'webp/webp',
			__( 'Local WebP', 'wp-smushit' ),
			null,
			array( $this, 'webp_meta_box_header' )
		);

		$this->modals['webp-delete-all'] = array();
	}

	/**
	 * WebP meta box header.
	 *
	 * @since 3.8.0
	 */
	public function webp_meta_box_header() {
		$this->view(
			'webp/meta-box-header',
			array(
				'is_disabled'   => ! $this->settings->get( 'webp_mod' ),
				'is_configured' => true === WP_Smush::get_instance()->core()->mod->webp->is_configured(),
			)
		);
	}

	/**
	 * Whether the wizard should be displayed.
	 *
	 * @since 3.9.0
	 *
	 * @return bool
	 */
	protected function is_wizard() {
		return ( ! WP_Smush::is_pro() || ( $this->settings->get( 'webp_mod' ) && ! get_site_option( 'wp-smush-webp_hide_wizard' ) ) );
	}
}
