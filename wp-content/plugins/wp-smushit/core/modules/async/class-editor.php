<?php
/**
 * Class Editor
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
 * Class Editor
 */
class Editor extends Abstract_Async {

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
	protected $action = 'wp_save_image_editor_file';

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
		// Store the post data in $data variable.
		if ( ! empty( $data ) ) {
			$data = array_merge( $data, $_POST );
		}

		// Store the image path.
		$data['filepath']  = ! empty( $data[1] ) ? $data[1] : '';
		$data['wp-action'] = ! empty( $data['action'] ) ? $data['action'] : '';
		unset( $data['action'], $data[1] );

		return $data;
	}

	/**
	 * Run the async task action
	 *
	 * TODO: Add a check for image
	 * TODO: See if auto smush is enabled or not
	 * TODO: Check if async is enabled or not
	 */
	protected function run_action() {
		if ( isset( $_POST['wp-action'], $_POST['do'], $_POST['postid'] )
			 && 'image-editor' === $_POST['wp-action']
			 && check_ajax_referer( 'image_editor-' . $_POST['postid'] )
			 && 'open' != $_POST['do']
		) {
			$postid = ! empty( $_POST['postid'] ) ? $_POST['postid'] : '';
			// Allow the Asynchronous task to run.
			do_action( "wp_async_$this->action", $postid, $_POST );
		}
	}

}
