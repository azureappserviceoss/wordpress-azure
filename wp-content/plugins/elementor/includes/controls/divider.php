<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor divider control.
 *
 * A base control for creating divider control. Displays horizontal line in
 * the panel.
 *
 * @since 2.0.0
 */
class Control_Divider extends Base_UI_Control {

	/**
	 * Get divider control type.
	 *
	 * Retrieve the control type, in this case `divider`.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'divider';
	}

	/**
	 * Get divider control default settings.
	 *
	 * Retrieve the default settings of the divider control. Used to
	 * return the default settings while initializing the divider
	 * control.
	 *
	 * @since 2.0.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'separator' => 'none',
		];
	}

	/**
	 * Render divider control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function content_template() {}
}
