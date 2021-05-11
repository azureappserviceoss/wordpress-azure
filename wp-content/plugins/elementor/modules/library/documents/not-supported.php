<?php
namespace Elementor\Modules\Library\Documents;

use Elementor\TemplateLibrary\Source_Local;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor section library document.
 *
 * Elementor section library document handler class is responsible for
 * handling a document of a section type.
 *
 */
class Not_Supported extends Library_Document {

	/**
	 * Get document properties.
	 *
	 * Retrieve the document properties.
	 *
	 * @access public
	 * @static
	 *
	 * @return array Document properties.
	 */
	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['admin_tab_group'] = '';
		$properties['register_type'] = false;
		$properties['is_editable'] = false;
		$properties['show_in_library'] = false;

		$properties['cpt'] = [
			Source_Local::CPT,
		];

		return $properties;
	}

	/**
	 * Get document name.
	 *
	 * Retrieve the document name.
	 *
	 * @access public
	 *
	 * @return string Document name.
	 */
	public function get_name() {
		return 'not-supported';
	}

	/**
	 * Get document title.
	 *
	 * Retrieve the document title.
	 *
	 * @access public
	 * @static
	 *
	 * @return string Document title.
	 */
	public static function get_title() {
		return __( 'Not Supported', 'elementor' );
	}

	public function save_template_type() {
		// Do nothing.
	}

	public function print_admin_column_type() {
		echo self::get_title();
	}

	public function filter_admin_row_actions( $actions ) {
		unset( $actions['view'] );

		return $actions;
	}
}
