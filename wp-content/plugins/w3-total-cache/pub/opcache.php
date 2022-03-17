<?php
/**
 * W3 Total Cache OPcache
 */

if ( !defined( 'ABSPATH' ) ) {
	if ( file_exists( dirname( __FILE__ ) . '/../../../../wp-load.php' ) ) {
		require_once dirname( __FILE__ ) . '/../../../../wp-load.php';
	}
	else {
		require_once dirname( __FILE__ ) . '/../../w3tc-wp-loader.php';
	}
}

if ( !defined( 'W3TC_DIR' ) ) {
	define( 'W3TC_DIR', WP_PLUGIN_DIR . '/w3-total-cache' );
}

if ( !@is_dir( W3TC_DIR ) || !file_exists( W3TC_DIR . '/w3-total-cache-api.php' ) ) {
	@header( 'X-Robots-Tag: noarchive, noodp, nosnippet' );
	echo sprintf( '<strong>W3 Total Cache Error:</strong> some files appear to be missing or out of place. Please re-install plugin or remove <strong>%s</strong>.<br />', dirname( __FILE__ ) );
}



$command = \W3TC\Util_Request::get_string( 'command' );
$nonce = \W3TC\Util_Request::get_string( 'nonce' );
$uri = $_SERVER['REQUEST_URI'];
if ( hash_equals( wp_hash( $uri ), $nonce ) ) {
	/**
	 *
	 *
	 * @var $w3_cache W3_CacheFlush
	 */
	$w3_cache = \W3TC\Dispatcher::component( 'CacheFlush' );
	$result = false;
	switch ( $command ) {
	case 'flush':
		$result = $w3_cache->opcache_flush();
		break;
	}
	if ( $result ) {
		header( $_SERVER["SERVER_PROTOCOL"] . " 200 OK" );
		die( 'Success' );
	} else {
		header( $_SERVER["SERVER_PROTOCOL"] . " 500 OK" );
		die( 'command could not be executed' );
	}

} else {
	header( $_SERVER["SERVER_PROTOCOL"] . " 401" );
	die( "Unauthorized access. " );
}
