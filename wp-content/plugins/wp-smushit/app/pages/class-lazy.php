<?php
/**
 * Lazy load page.
 *
 * @package Smush\App\Pages
 */

namespace Smush\App\Pages;

use Smush\App\Abstract_Summary_Page;
use Smush\App\Interface_Page;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Lazy
 */
class Lazy extends Abstract_Summary_Page implements Interface_Page {
	/**
	 * Register meta boxes.
	 */
	public function register_meta_boxes() {
		parent::register_meta_boxes();

		if ( ! $this->settings->get( 'lazy_load' ) ) {
			$this->add_meta_box(
				'lazyload/disabled',
				__( 'Lazy Load', 'wp-smushit' ),
				null,
				null,
				null,
				'main',
				array(
					'box_class' => 'sui-box sui-message sui-no-padding',
				)
			);

			return;
		}

		$this->add_meta_box(
			'lazyload',
			__( 'Lazy Load', 'wp-smushit' ),
			array( $this, 'lazy_load_meta_box' ),
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
	 * Lazy loading meta box.
	 *
	 * @since 3.2.0
	 */
	public function lazy_load_meta_box() {
		$this->view(
			'lazyload/meta-box',
			array(
				'conflicts' => get_transient( 'wp-smush-conflict_check' ),
				'settings'  => $this->settings->get_setting( 'wp-smush-lazy_load' ),
				'cpts'      => get_post_types( // custom post types.
					array(
						'public'   => true,
						'_builtin' => false,
					),
					'objects'
				),
			)
		);
	}
}
