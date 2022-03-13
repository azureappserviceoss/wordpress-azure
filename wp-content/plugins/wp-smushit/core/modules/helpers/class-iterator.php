<?php
/**
 * Filters the list of directories, exclude the media subfolders.
 *
 * @package Smush\Core\Modules\Helpers
 */

namespace Smush\Core\Modules\Helpers;

use RecursiveFilterIterator;
use WP_Smush;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Iterator extends RecursiveFilterIterator
 */
class Iterator extends RecursiveFilterIterator {
	/**
	 * Accept method.
	 *
	 * @return bool
	 */
	public function accept() {
		$path = $this->current()->getPathname();

		if ( $this->isDir() && ! WP_Smush::get_instance()->core()->mod->dir->skip_dir( $path ) ) {
			return true;
		}

		if ( ! $this->isDir() ) {
			return true;
		}

		return false;
	}
}
