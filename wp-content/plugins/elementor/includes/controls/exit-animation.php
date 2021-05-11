<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor exit animation control.
 *
 * A control for creating exit animation. Displays a select box
 * with the available exit animation effects @see Control_Exit_Animation::get_animations() .
 *
 * @since 2.5.0
 */
class Control_Exit_Animation extends Control_Animation {

	/**
	 * Get control type.
	 *
	 * Retrieve the animation control type.
	 *
	 * @since 2.5.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'exit_animation';
	}

	/**
	 * Get animations list.
	 *
	 * Retrieve the list of all the available animations.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return array Control type.
	 */
	public static function get_animations() {
		$animations = [
			'Fading' => [
				'fadeIn' => 'Fade Out',
				'fadeInDown' => 'Fade Out Up',
				'fadeInLeft' => 'Fade Out Left',
				'fadeInRight' => 'Fade Out Right',
				'fadeInUp' => 'Fade Out Down',
			],
			'Zooming' => [
				'zoomIn' => 'Zoom Out',
				'zoomInDown' => 'Zoom Out Up',
				'zoomInLeft' => 'Zoom Out Left',
				'zoomInRight' => 'Zoom Out Right',
				'zoomInUp' => 'Zoom Out Down',
			],
			'Sliding' => [
				'slideInDown' => 'Slide Out Up',
				'slideInLeft' => 'Slide Out Left',
				'slideInRight' => 'Slide Out Right',
				'slideInUp' => 'Slide Out Down',
			],
			'Rotating' => [
				'rotateIn' => 'Rotate Out',
				'rotateInDownLeft' => 'Rotate Out Up Left',
				'rotateInDownRight' => 'Rotate Out Up Right',
				'rotateInUpRight' => 'Rotate Out Down Left',
				'rotateInUpLeft' => 'Rotate Out Down Right',
			],
			'Light Speed' => [
				'lightSpeedIn' => 'Light Speed Out',
			],
			'Specials' => [
				'rollIn' => 'Roll Out',
			],
		];

		/**
		 * Element appearance animations list.
		 *
		 * @since 2.5.0
		 *
		 * @param array $additional_animations Additional Animations array.
		 */
		$additional_animations = apply_filters( 'elementor/controls/exit-animations/additional_animations', [] );

		return array_merge( $animations, $additional_animations );
	}
}
