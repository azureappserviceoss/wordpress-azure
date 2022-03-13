<?php
/**
 * Smush Abstract_API class that handles communications with WPMU DEV API: API class
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
 * Class Abstract_API.
 */
abstract class Abstract_API {

	/**
	 * API key.
	 *
	 * @since 3.0
	 *
	 * @var string
	 */
	public $api_key = '';

	/**
	 * API request instance.
	 *
	 * @since 3.0
	 *
	 * @var Request
	 */
	protected $request;

	/**
	 * API constructor.
	 *
	 * @since 3.0
	 *
	 * @param string $key  API key.
	 *
	 * @throws Exception  API Request exception.
	 */
	public function __construct( $key ) {
		$this->api_key = $key;

		// The Request class needs these to make requests.
		if ( empty( $this->version ) || empty( $this->name ) ) {
			throw new Exception( __( 'API instances require a version and name properties', 'wp-smushit' ), 404 );
		}

		$this->request = new Request( $this );
	}
}
