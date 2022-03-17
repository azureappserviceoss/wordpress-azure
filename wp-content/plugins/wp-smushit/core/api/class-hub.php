<?php
/**
 * WPMU DEV Hub endpoints.
 *
 * Class allows syncing plugin data with the Hub.
 *
 * @since 3.7.0
 * @package Smush\Core\Api
 */

namespace Smush\Core\Api;

use Smush\Core\Configs;
use Smush\Core\Settings;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Hub
 */
class Hub {

	/**
	 * Endpoints array.
	 *
	 * @since 3.7.0
	 * @var array
	 */
	private $endpoints = array(
		'get_stats',
		'import_settings',
		'export_settings',
	);

	/**
	 * Hub constructor.
	 *
	 * @since 3.7.0
	 */
	public function __construct() {
		add_filter( 'wdp_register_hub_action', array( $this, 'add_endpoints' ) );
	}

	/**
	 * Add Hub endpoints.
	 *
	 * Every Hub Endpoint name is build following the structure: 'smush-$endpoint-$action'
	 *
	 * @since 3.7.0
	 * @param array $actions  Endpoint action.
	 *
	 * @return array
	 */
	public function add_endpoints( $actions ) {
		foreach ( $this->endpoints as $endpoint ) {
			$actions[ "smush_{$endpoint}" ] = array( $this, 'action_' . $endpoint );
		}

		return $actions;
	}

	/**
	 * Retrieve data for endpoint.
	 *
	 * @since 3.7.0
	 * @param array  $params  Parameters.
	 * @param string $action  Action.
	 */
	public function action_get_stats( $params, $action ) {
		$status   = array();
		$settings = Settings::get_instance();

		$status['cdn']   = $settings->get( 'cdn' );
		$status['super'] = $settings->get( 'lossy' );

		$lazy = $settings->get_setting( 'wp-smush-lazy_load' );

		$status['lazy'] = array(
			'enabled' => $settings->get( 'lazy_load' ),
			'native'  => is_array( $lazy ) && isset( $lazy['native'] ) ? $lazy['native'] : false,
		);

		$core = \WP_Smush::get_instance()->core();

		if ( ! isset( $core->stats ) ) {
			// Setup stats, if not set already.
			$core->setup_global_stats();
		}
		// Total, Smushed, Unsmushed, Savings.
		$status['count_total']   = $core->total_count;
		$status['count_smushed'] = $core->smushed_count;
		// Considering the images to be resmushed.
		$status['count_unsmushed'] = $core->remaining_count;
		$status['savings']         = $core->stats;

		$status['dir']   = $core->dir_stats;

		wp_send_json_success( (object) $status );
	}

	/**
	 * Applies the given config sent by the Hub via the Dashboard plugin.
	 *
	 * @since 3.8.5
	 *
	 * @param object $config_data The config sent by the Hub.
	 */
	public function action_import_settings( $config_data ) {
		if ( empty( $config_data->configs ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Missing config data', 'wp-smushit' ),
				)
			);
		}

		// The Hub returns an object, we use an array.
		$config_array = json_decode( wp_json_encode( $config_data->configs ), true );

		$configs_handler = new \Smush\Core\Configs();
		$configs_handler->apply_config( $config_array );

		wp_send_json_success();
	}

	/**
	 * Exports the current settings as a config for the Hub.
	 *
	 * @since 3.8.5
	 */
	public function action_export_settings() {
		$configs_handler = new \Smush\Core\Configs();
		$config          = $configs_handler->get_config_from_current();

		wp_send_json_success( $config['config'] );
	}
}
