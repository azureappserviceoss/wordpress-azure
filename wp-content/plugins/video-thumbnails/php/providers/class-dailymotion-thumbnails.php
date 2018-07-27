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

class Dailymotion_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'Dailymotion';
	const service_name = 'Dailymotion';
	// Slug for the video provider
	public $service_slug = 'dailymotion';
	const service_slug = 'dailymotion';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
		'#<object[^>]+>.+?http://www\.dailymotion\.com/swf/video/([A-Za-z0-9]+).+?</object>#s', // Dailymotion flash
		'#https?://www\.dailymotion\.com/embed/video/([A-Za-z0-9]+)#', // Dailymotion iframe
		'#(?:https?://)?(?:www\.)?dailymotion\.com/video/([A-Za-z0-9]+)#' // Dailymotion URL
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		$request = "https://api.dailymotion.com/video/$id?fields=thumbnail_url";
		$response = wp_remote_get( $request, array( 'sslverify' => false ) );
		if( is_wp_error( $response ) ) {
			$result = new WP_Error( 'dailymotion_info_retrieval', __( 'Error retrieving video information from the URL <a href="' . $request . '">' . $request . '</a> using <code>wp_remote_get()</code><br />If opening that URL in your web browser returns anything else than an error page, the problem may be related to your web server and might be something your host administrator can solve.<br />Details: ' . $response->get_error_message() ) );
		} else {
			$result = json_decode( $response['body'] );
			$result = $result->thumbnail_url;
		}
		return $result;
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<iframe frameborder="0" width="480" height="270" src="http://www.dailymotion.com/embed/video/xqlhts"></iframe><br /><a href="http://www.dailymotion.com/video/xqlhts_adam-yauch-of-the-beastie-boys-dies-at-47_people" target="_blank">Adam Yauch of the Beastie Boys Dies at 47</a> <i>by <a href="http://www.dailymotion.com/associatedpress" target="_blank">associatedpress</a></i>',
			'expected' => 'http://s1.dmcdn.net/AMjdy.jpg',
			'name' => 'iFrame player'
		),
		array(
			'markup' => '<object width="480" height="270"><param name="movie" value="http://www.dailymotion.com/swf/video/xqlhts"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><param name="wmode" value="transparent"></param><embed type="application/x-shockwave-flash" src="http://www.dailymotion.com/swf/video/xqlhts" width="480" height="270" wmode="transparent" allowfullscreen="true" allowscriptaccess="always"></embed></object><br /><a href="http://www.dailymotion.com/video/xqlhts_adam-yauch-of-the-beastie-boys-dies-at-47_people" target="_blank">Adam Yauch of the Beastie Boys Dies at 47</a> <i>by <a href="http://www.dailymotion.com/associatedpress" target="_blank">associatedpress</a></i>',
			'expected' => 'http://s1.dmcdn.net/AMjdy.jpg',
			'name' => 'Flash player'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'Dailymotion_Thumbnails', 'register_provider' ) );

?>