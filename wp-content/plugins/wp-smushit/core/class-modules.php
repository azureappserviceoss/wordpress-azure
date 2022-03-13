<?php
/**
 * Class Modules.
 *
 * Used in Core to type hint the $mod variable. For example, this way any calls to
 * \Smush\WP_Smush::get_instance()->core()->mod->settings will be typehinted as a call to Settings module.
 *
 * @package Smush\Core
 */

namespace Smush\Core;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Modules
 */
class Modules {

	/**
	 * Directory Smush module.
	 *
	 * @var Modules\Dir
	 */
	public $dir;

	/**
	 * Main Smush module.
	 *
	 * @var Modules\Smush
	 */
	public $smush;

	/**
	 * Backup module.
	 *
	 * @var Modules\Backup
	 */
	public $backup;

	/**
	 * PNG 2 JPG module.
	 *
	 * @var Modules\Png2jpg
	 */
	public $png2jpg;

	/**
	 * Resize module.
	 *
	 * @var Modules\Resize
	 */
	public $resize;

	/**
	 * CDN module.
	 *
	 * @var Modules\CDN
	 */
	public $cdn;

	/**
	 * Image lazy load module.
	 *
	 * @since 3.2
	 *
	 * @var Modules\Lazy
	 */
	public $lazy;

	/**
	 * Modules constructor.
	 */
	public function __construct() {
		new Api\Hub(); // Init hub endpoints.

		new Modules\Resize_Detection();
		new Rest();

		if ( is_admin() ) {
			$this->dir = new Modules\Dir();
		}

		$this->smush   = new Modules\Smush();
		$this->backup  = new Modules\Backup();
		$this->png2jpg = new Modules\Png2jpg();
		$this->resize  = new Modules\Resize();

		$page_parser = new Modules\Helpers\Parser();
		$page_parser->init();

		$this->cdn  = new Modules\CDN( $page_parser );
		$this->webp = new Modules\WebP();
		$this->lazy = new Modules\Lazy( $page_parser );
	}

}
