<?php
/**
 * CDN page.
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
 * Class CDN
 */
class CDN extends Abstract_Summary_Page implements Interface_Page {
	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		parent::register_meta_boxes();

		if ( ! WP_Smush::is_pro() ) {
			$this->add_meta_box(
				'cdn/upsell',
				__( 'CDN', 'wp-smushit' )
			);

			return;
		}

		if ( ! $this->settings->get( 'cdn' ) ) {
			$this->add_meta_box(
				'cdn/disabled',
				__( 'CDN', 'wp-smushit' )
			);

			return;
		}

		$this->add_meta_box(
			'cdn',
			__( 'CDN', 'wp-smushit' ),
			array( $this, 'cdn_meta_box' ),
			null,
			array( $this, 'common_meta_box_footer' )
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
	 * CDN meta box.
	 *
	 * @since 3.0
	 */
	public function cdn_meta_box() {
		$status = WP_Smush::get_instance()->core()->mod->cdn->status();

		// Available values: warning (inactive), success (active) or error (expired).
		$status_msg = array(
			'enabled'    => __(
				'Your media is currently being served from the WPMU DEV CDN. Bulk and Directory smush features are treated separately and will continue to run independently.',
				'wp-smushit'
			),
			'disabled'   => __( 'CDN is not yet active. Configure your settings below and click Activate.', 'wp-smushit' ),
			'activating' => __(
				'Your settings have been saved and changes are now propagating to the CDN. Changes can take up to 30
				minutes to take effect but your images will continue to be served in the mean time, please be patient.',
				'wp-smushit'
			),
			'upgrade'    => sprintf(
				__(
				/* translators: %1$s - starting a tag, %2$s - closing a tag */
					"You're almost through your CDN bandwidth limit. Please contact your administrator to upgrade your Smush CDN plan to ensure you don't lose this service. %1\$sUpgrade now%2\$s",
					'wp-smushit'
				),
				'<a href="https://wpmudev.com/hub/account/" target="_blank">',
				'</a>'
			),
			'overcap'    => sprintf(
				__(
				/* translators: %1$s - starting a tag, %2$s - closing a tag */
					"You've gone through your CDN bandwidth limit, so we’ve stopped serving your images via the CDN. Contact your administrator to upgrade your Smush CDN plan to reactivate this service. %1\$sUpgrade now%2\$s",
					'wp-smushit'
				),
				'<a href="https://wpmudev.com/hub/account/" target="_blank">',
				'</a>'
			),
		);

		$status_color = array(
			'enabled'    => 'success',
			'disabled'   => 'error',
			'activating' => 'warning',
			'upgrade'    => 'warning',
			'overcap'    => 'error',
		);

		// Disable CDN on staging.
		if ( isset( $_SERVER['WPMUDEV_HOSTING_ENV'] ) && 'staging' === $_SERVER['WPMUDEV_HOSTING_ENV'] ) {
			$status_msg['disabled']   = __( 'Your Staging environment’s media is currently being served from your local server. If you move your Staging files into Production, your Production environment’s media will automatically be served from the Smush CDN.', 'wp-smushit' );
			$status_color['disabled'] = 'warning';
		}

		$this->view(
			'cdn/meta-box',
			array(
				'cdn_group'  => $this->settings->get_cdn_fields(),
				'settings'   => $this->settings->get(),
				'status_msg' => $status_msg[ $status ],
				'class'      => $status_color[ $status ],
				'status'     => $status,
			)
		);
	}
}
