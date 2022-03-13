<?php
/**
 * Smush API class that handles communications with WPMU DEV API: API class
 *
 * @since 3.0
 * @package Smush\Core\Api
 */

namespace Smush\Core\Api;

use Exception;
use WP_Error;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Smush_API.
 */
class Smush_API extends Abstract_API {

	/**
	 * Endpoint name.
	 *
	 * @since 3.0
	 *
	 * @var string
	 */
	public $name = 'smush';

	/**
	 * Endpoint version.
	 *
	 * @since 3.0
	 *
	 * @var string
	 */
	public $version = 'v1';

	/**
	 * Check CDN status (same as verify the is_pro status).
	 *
	 * @since 3.0
	 *
	 * @param bool $manual  If it's a manual check. Only manual on button click.
	 *
	 * @return mixed|WP_Error
	 */
	public function check( $manual = false ) {
		if ( isset( $_SERVER['WPMUDEV_HOSTING_ENV'] ) && 'staging' === $_SERVER['WPMUDEV_HOSTING_ENV'] ) {
			return new WP_Error( '503', __( 'Unable to check status on staging.', 'wp-smushit' ) );
		}

		return $this->request->get(
			"check/{$this->api_key}",
			array(
				'api_key' => $this->api_key,
				'domain'  => $this->request->get_this_site(),
			),
			$manual
		);
	}

	/**
	 * Enable CDN for site.
	 *
	 * @since 3.0
	 *
	 * @param bool $manual  If it's a manual check. Overwrites the exponential back off.
	 *
	 * @return mixed|WP_Error
	 */
	public function enable( $manual = false ) {
		return $this->request->post(
			'cdn',
			array(
				'api_key' => $this->api_key,
				'domain'  => $this->request->get_this_site(),
			),
			$manual
		);
	}

}
