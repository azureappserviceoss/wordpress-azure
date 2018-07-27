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

class Blip_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'Blip';
	const service_name = 'Blip';
	// Slug for the video provider
	public $service_slug = 'blip';
	const service_slug = 'blip';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
		'#(https?\:\/\/blip\.tv\/[^\r\n\'\"]+)#' // Blip URL
	);

	// Thumbnail URL
	public function get_thumbnail_url( $url ) {
		$request = "http://blip.tv/oembed?url=$url";
		$response = wp_remote_get( $request, array( 'sslverify' => false ) );
		if( is_wp_error( $response ) ) {
			$result = new WP_Error( 'blip_info_retrieval', __( 'Error retrieving video information from the URL <a href="' . $request . '">' . $request . '</a> using <code>wp_remote_get()</code><br />If opening that URL in your web browser returns anything else than an error page, the problem may be related to your web server and might be something your host administrator can solve.<br />Details: ' . $response->get_error_message() ) );
		} else {
			$json = json_decode( $response['body'] );
			if ( isset( $json->error ) ) {
				$result = new WP_Error( 'blip_invalid_url', __( 'Error retrieving video information for <a href="' . $url . '">' . $url . '</a>. Check to be sure this is a valid Blip video URL.' ) );
			} else {
				$result = $json->thumbnail_url;
			}
		}
		return $result;
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => 'http://blip.tv/cranetv/illustrator-katie-scott-6617917',
			'expected' => 'http://a.images.blip.tv/CraneTV-IllustratorKatieScott610.jpg',
			'name' => 'Video URL'
		),
		array(
			'markup' => '<iframe src="http://blip.tv/play/AYL1uFkC.html?p=1" width="780" height="438" frameborder="0" allowfullscreen></iframe><embed type="application/x-shockwave-flash" src="http://a.blip.tv/api.swf#AYL1uFkC" style="display:none"></embed>',
			'expected' => 'http://a.images.blip.tv/ReelScience-TheScientificMethodOfOz139.jpg',
			'name' => 'iFrame player'
		),
		array(
			'markup' => '<iframe src="http://blip.tv/play/AYLz%2BEsC.html?p=1" width="780" height="438" frameborder="0" allowfullscreen></iframe><embed type="application/x-shockwave-flash" src="http://a.blip.tv/api.swf#AYLz+EsC" style="display:none"></embed>',
			'expected' => 'http://a.images.blip.tv/GeekCrashCourse-TheAvengersMarvelMovieCatchUpGeekCrashCourse331.png',
			'name' => 'iFrame player (special characters in ID)'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'Blip_Thumbnails', 'register_provider' ) );

?>