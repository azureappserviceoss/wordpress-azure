<?php

/*  Copyright 2013 Sutherland Boswell  (email : sutherland.boswell@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Require thumbnail provider class
require_once( VIDEO_THUMBNAILS_PATH . '/php/providers/class-video-thumbnails-providers.php' );

class Kaltura_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'Kaltura';
	const service_name = 'Kaltura';
	// Slug for the video provider
	public $service_slug = 'kaltura';
	const service_slug = 'kaltura';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
	    '#http://cdnapi\.kaltura\.com/p/[0-9]+/sp/[0-9]+/embedIframeJs/uiconf_id/[0-9]+/partner_id/[0-9]+\?entry_id=([a-z0-9_]+)#' // Hosted
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		$request = "http://www.kaltura.com/api_v3/?service=thumbAsset&action=getbyentryid&entryId=$id";
		$response = wp_remote_get( $request, array( 'sslverify' => false ) );
		if( is_wp_error( $response ) ) {
			$result = new WP_Error( 'kaltura_info_retrieval', __( 'Error retrieving video information from the URL <a href="' . $request . '">' . $request . '</a> using <code>wp_remote_get()</code><br />If opening that URL in your web browser returns anything else than an error page, the problem may be related to your web server and might be something your host administrator can solve.<br />Details: ' . $response->get_error_message() ) );
		} else {
			$xml = new SimpleXMLElement( $response['body'] );
			$result = (string) $xml->result->item->id;
			$request = "http://www.kaltura.com/api_v3/?service=thumbAsset&action=geturl&id=$result";
			$response = wp_remote_get( $request, array( 'sslverify' => false ) );
			if( is_wp_error( $response ) ) {
				$result = new WP_Error( 'kaltura_info_retrieval', __( 'Error retrieving video information from the URL <a href="' . $request . '">' . $request . '</a> using <code>wp_remote_get()</code><br />If opening that URL in your web browser returns anything else than an error page, the problem may be related to your web server and might be something your host administrator can solve.<br />Details: ' . $response->get_error_message() ) );
			} else {
				$xml = new SimpleXMLElement( $response['body'] );
				$result = (string) $xml->result;
			}
		}
		return $result;
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<script type="text/javascript" src="http://cdnapi.kaltura.com/p/1374841/sp/137484100/embedIframeJs/uiconf_id/12680902/partner_id/1374841?entry_id=1_y7xzqsxw&playerId=kaltura_player_1363589321&cache_st=1363589321&autoembed=true&width=400&height=333&"></script>',
			'expected' => 'http://example.com/thumbnail.jpg',
			'name' => 'Auto embed'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'Kaltura_Thumbnails', 'register_provider' ) );

?>