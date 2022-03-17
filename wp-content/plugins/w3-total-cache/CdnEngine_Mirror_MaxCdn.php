<?php
namespace W3TC;

define( 'W3TC_CDN_NETDNA_URL', 'netdna-cdn.com' );

class CdnEngine_Mirror_MaxCdn extends CdnEngine_Mirror {
	/**
	 * PHP5 Constructor
	 *
	 * @param array   $config
	 */
	function __construct( $config = array() ) {
		$config = array_merge( array(
				'authorization_key' => '',
				'alias' => '',
				'consumerkey' => '',
				'consumersecret' => '',
				'zone_id' => 0
			), $config );
		$split_keys = explode( '+', $config['authorization_key'] );
		if ( sizeof( $split_keys )==3 )
			list( $config['alias'], $config['consumerkey'], $config['consumersecret'] ) = $split_keys;
		parent::__construct( $config );
	}

	/**
	 * Purges remote files
	 *
	 * @param array   $files
	 * @param array   $results
	 * @return boolean
	 */
	function purge( $files, &$results ) {
		if ( empty( $this->_config['authorization_key'] ) ) {
			$results = $this->_get_results( $files, W3TC_CDN_RESULT_HALT, __( 'Empty Authorization Key.', 'w3-total-cache' ) );

			return false;
		}

		if ( empty( $this->_config['alias'] ) || empty( $this->_config['consumerkey'] ) || empty( $this->_config['consumersecret'] ) ) {
			$results = $this->_get_results( $files, W3TC_CDN_RESULT_HALT, __( 'Malformed Authorization Key.', 'w3-total-cache' ) );

			return false;
		}

		if ( !class_exists( 'NetDNA' ) ) {
			require_once W3TC_LIB_NETDNA_DIR . '/NetDNA.php';
		}

		$api = new \NetDNA( $this->_config['alias'],
			$this->_config['consumerkey'], $this->_config['consumersecret'] );
		$results = array();

		try {
			if ( $this->_config['zone_id'] != 0 )
				$zone_id = $this->_config['zone_id'];
			else {
				$zone_id = $api->get_zone_id( get_home_url() );
			}

			if ( $zone_id == 0 ) {
				$zone_id = $api->get_zone_id( Util_Environment::home_domain_root_url() );
			}

			if ( $zone_id == 0 ) {
				$zone_id = $api->get_zone_id( str_replace( '://', '://www.', Util_Environment::home_domain_root_url() ) );
			}

			if ( $zone_id == 0 || is_null( $zone_id ) ) {
				if ( Util_Environment::home_domain_root_url() == get_home_url() )
					$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_ERROR, sprintf( __( 'No zones match site: %s.', 'w3-total-cache' ), trim( get_home_url(), '/' ) ) );
				else
					$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_ERROR, sprintf( __( 'No zones match site: %s or %s.', 'w3-total-cache' ), trim( get_home_url(), '/' ), trim( Util_Environment::home_domain_root_url(), '/' ) ) );
				return !$this->_is_error( $results );
			}


			$files_to_pass = array();
			foreach ( $files as $file )
				$files_to_pass[] = '/' . $file['remote_path'];
			$params = array( 'files' => $files_to_pass );
			$file_purge = json_decode( $api->delete(
					'/zones/pull.json/' . $zone_id . '/cache',
					$params ) );

			if ( preg_match( "(200|201)", $file_purge->code ) ) {
				$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_OK, 'OK' );
			} else {
				if ( preg_match( "(401|500)", $file_purge->code ) ) {
					$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_ERROR, sprintf( __( 'Failed with error code %s Please check your alias, consumer key, and private key.', 'w3-total-cache' ), $file_purge->code ) );
				} else {
					$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_ERROR, __( 'Failed with error code ', 'w3-total-cache' ) . $file_purge->code );
				}
			}
		} catch ( W3tcWpHttpException $e ) {
			$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_HALT, __( 'Failure to pull zone: ', 'w3-total-cache' ) . $e->getMessage() );
		}

		return !$this->_is_error( $results );
	}

	/**
	 * Purge CDN completely
	 *
	 * @param unknown $results
	 * @return bool
	 */
	function purge_all( &$results ) {
		if ( empty( $this->_config['authorization_key'] ) ) {
			$results = $this->_get_results( array(), W3TC_CDN_RESULT_HALT,  __( 'Empty Authorization Key.', 'w3-total-cache' ) );

			return false;
		}

		if ( empty( $this->_config['alias'] ) || empty( $this->_config['consumerkey'] ) || empty( $this->_config['consumersecret'] ) ) {
			$results = $this->_get_results( array(), W3TC_CDN_RESULT_HALT,  __( 'Malformed Authorization Key.', 'w3-total-cache' ) );

			return false;
		}

		if ( !class_exists( 'NetDNA' ) ) {
			require_once W3TC_LIB_NETDNA_DIR . '/NetDNA.php';
		}

		$api = new \NetDNA( $this->_config['alias'], $this->_config['consumerkey'], $this->_config['consumersecret'] );

		$results = array();

		try {
			if ( $this->_config['zone_id'] != 0 )
				$zone_id = $this->_config['zone_id'];
			else {
				$zone_id = $api->get_zone_id( get_home_url() );
			}

			if ( $zone_id == 0 ) {
				$zone_id = $api->get_zone_id( Util_Environment::home_domain_root_url() );
			}


			if ( $zone_id == 0 ) {
				$zone_id = $api->get_zone_id( str_replace( '://', '://www.', Util_Environment::home_domain_root_url() ) );
			}

			if ( $zone_id == 0 || is_null( $zone_id ) ) {
				if ( Util_Environment::home_domain_root_url() == get_home_url() )
					$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_ERROR, sprintf( __( 'No zones match site: %s.', 'w3-total-cache' ), trim( get_home_url(), '/' ) ) );
				else
					$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_ERROR, sprintf( __( 'No zones match site: %s or %s.', 'w3-total-cache' ), trim( get_home_url(), '/' ), trim( Util_Environment::home_domain_root_url(), '/' ) ) );
				return !$this->_is_error( $results );
			}

			$file_purge = json_decode( $api->delete( '/zones/pull.json/' . $zone_id . '/cache' ) );

			if ( preg_match( "(200|201)", $file_purge->code ) ) {
				$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_OK, __( 'OK', 'w3-total-cache' ) );
			} else {
				if ( preg_match( "(401|500)", $file_purge->code ) ) {
					$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_ERROR, sprintf( __( 'Failed with error code %s. Please check your alias, consumer key, and private key.', 'w3-total-cache' ), $file_purge->code ) );
				} else {
					$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_ERROR, __( 'Failed with error code ', 'w3-total-cache' ) . $file_purge->code );
				}
			}

		} catch ( W3tcWpHttpException $e ) {
			$results[] = $this->_get_result( '', '', W3TC_CDN_RESULT_HALT, __( 'Failure to pull zone: ', 'w3-total-cache' ) . $e->getMessage() );
		}

		return !$this->_is_error( $results );
	}
}
