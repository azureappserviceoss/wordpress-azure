<?php
/**
 * Abstract class for an integration module: class Abstract_Integration
 *
 * @since 2.9.0
 * @package Smush\Core\Modules\Integrations
 */

namespace Smush\Core\Integrations;

use Smush\Core\Settings;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Abstract_Integration
 */
abstract class Abstract_Integration {

	/**
	 * Module slug.
	 *
	 * @var string $module
	 */
	protected $module;

	/**
	 * Module class - free module by default, can be pro.
	 *
	 * @var string $class  Accepts: 'free', 'pro'.
	 */
	protected $class = 'free';

	/**
	 * Module status.
	 *
	 * @var bool $enabled
	 */
	protected $enabled = false;

	/**
	 * Settings class instance for easier access.
	 *
	 * @since 3.0
	 *
	 * @var Settings
	 */
	protected $settings;

	/**
	 * Abstract_Integration constructor.
	 */
	public function __construct() {
		$this->settings = Settings::get_instance();

		// Filters the setting variable to add module setting title and description.
		add_filter( 'wp_smush_settings', array( $this, 'register' ) );

		// Disable setting.
		add_filter( 'wp_smush_integration_status_' . $this->module, array( $this, 'setting_status' ) );
	}

	/**
	 * Update setting status - disable module functionality if not enabled.
	 *
	 * @since 2.8.1
	 *
	 * @return bool
	 */
	public function setting_status() {
		return ! $this->enabled;
	}

}
