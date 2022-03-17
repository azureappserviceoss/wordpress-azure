<?php
/**
 * Abstract module class: Abstract_Module
 *
 * @since 3.0
 * @package Smush\Core\Modules
 */

namespace Smush\Core\Modules;

use Smush\Core\Settings;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Abstract_Module
 *
 * @since 3.0
 */
abstract class Abstract_Module {

	/**
	 * Settings instance.
	 *
	 * @since 3.0
	 * @var Settings
	 */
	protected $settings;

	/**
	 * Abstract_Module constructor.
	 *
	 * @since 3.0
	 */
	public function __construct() {
		$this->settings = Settings::get_instance();

		$this->init();
	}

	/**
	 * Initialize the module.
	 *
	 * Do not use __construct in modules, instead use init().
	 *
	 * @since 3.0
	 */
	protected function init() {}

}
