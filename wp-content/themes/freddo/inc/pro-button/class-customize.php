<?php
/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
use WPTRT\Customize\Section\Button;
final class Freddo_Updgrade_Pro_Button {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	 */
	public function sections( $manager ) {
		
		get_template_part( '/inc/pro-button/Button');

		$manager->register_section_type( Button::class );

		$manager->add_section(
			new Button( $manager, 'cresta_freddo_buy_pro', [
				'title'       => __( 'Freddo PRO Theme', 'freddo' ),
				'button_text' => __( 'More Info',        'freddo' ),
				'button_url'  => 'https://crestaproject.com/downloads/freddo/',
				'priority' => 1,
			] )
		);
		
		$manager->add_section(
			new Button( $manager, 'cresta_freddo_documentation', [
				'title'       => __( 'Need help?', 'freddo' ),
				'button_text' => __( 'Theme Documentation',        'freddo' ),
				'button_url'  => admin_url( add_query_arg( array( 'page' => 'freddo-welcome', 'tab' => 'documentation' ), 'themes.php' ) ),
				'priority' => 999,
			] )
		);
		
		$manager->add_section(
			new Button( $manager, 'cresta_freddo_locked_pro', [
				'title'       => __( 'Change sections position', 'freddo' ),
				'button_text' => __( 'PRO Version',        'freddo' ),
				'button_url'  => 'https://crestaproject.com/downloads/freddo/',
				'panel'  => 'cresta_freddo_onepage',
				'priority' => 1,
			] )
		);

	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'freddo-customize-controls-pro-button', trailingslashit( get_template_directory_uri() ) . 'inc/pro-button/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'freddo-customize-controls-pro-button', trailingslashit( get_template_directory_uri() ) . 'inc/pro-button/customize-controls.css' );
	}
}

// Doing this customizer thang!
Freddo_Updgrade_Pro_Button::get_instance();
