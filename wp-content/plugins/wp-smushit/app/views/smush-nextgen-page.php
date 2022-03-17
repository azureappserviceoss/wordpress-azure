<?php
/**
 * Render Smush NextGen pages.
 *
 * @package WP_Smush
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$this->do_meta_boxes( 'summary' );
$this->do_meta_boxes( 'bulk' );

$this->view( 'footer-links', array(), 'common' );
