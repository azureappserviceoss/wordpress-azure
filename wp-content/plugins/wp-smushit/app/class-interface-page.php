<?php
/**
 * Page interface.
 *
 * @package Smush\App
 */

namespace Smush\App;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Interface Interface_Page
 */
interface Interface_Page {
	/**
	 * Function triggered when the page is loaded before render any content.
	 */
	public function on_load();
}

