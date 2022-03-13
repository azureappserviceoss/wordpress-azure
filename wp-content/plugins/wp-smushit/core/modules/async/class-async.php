<?php
/**
 * Class Async
 *
 * @package Smush\Core\Modules\Async
 * @since 2.5
 *
 * @author Umesh Kumar <umesh@incsub.com>
 *
 * @copyright (c) 2016, Incsub (http://incsub.com)
 */

namespace Smush\Core\Modules\Async;

use Exception;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Async
 */
class Async extends Abstract_Async {

	/**
	 * Argument count.
	 *
	 * @var int $argument_count
	 */
	protected $argument_count = 2;

	/**
	 * Priority.
	 *
	 * @var int $priority
	 */
	protected $priority = 12;

	/**
	 * Whenever a attachment metadata is generated
	 * Had to be hooked on generate and not update, else it goes in infinite loop
	 *
	 * @var string
	 */
	protected $action = 'wp_generate_attachment_metadata';

	/**
	 * Prepare data for the asynchronous request
	 *
	 * @throws Exception If for any reason the request should not happen.
	 *
	 * @param array $data An array of data sent to the hook.
	 *
	 * @return array
	 */
	protected function prepare_data( $data ) {
		// We don't have the data, bail out.
		if ( empty( $data ) ) {
			return $data;
		}

		// Return a associative array.
		$image_meta             = array();
		$image_meta['metadata'] = ! empty( $data[0] ) ? $data[0] : '';
		$image_meta['id']       = ! empty( $data[1] ) ? $data[1] : '';

		/**
		 * AJAX Thumbnail Rebuild integration.
		 *
		 * @see https://app.asana.com/0/14491813218786/730814863045197/f
		 */
		if ( ! empty( $_POST['action'] ) && 'ajax_thumbnail_rebuild' === $_POST['action'] && ! empty( $_POST['thumbnails'] ) ) { // Input var ok.
			$image_meta['regen'] = wp_unslash( $_POST['thumbnails'] ); // Input var ok.
		}

		return $image_meta;
	}

	/**
	 * Run the async task action
	 *
	 * TODO: See if auto smush is enabled or not.
	 * TODO: Check if async is enabled or not.
	 */
	protected function run_action() {
		$metadata = ! empty( $_POST['metadata'] ) ? $_POST['metadata'] : '';
		$id       = ! empty( $_POST['id'] ) ? $_POST['id'] : '';

		// Get metadata from $_POST.
		if ( ! empty( $metadata ) && wp_attachment_is_image( $id ) ) {
			// Allow the Asynchronous task to run.
			do_action( "wp_async_$this->action", $id );
		}
	}

}
