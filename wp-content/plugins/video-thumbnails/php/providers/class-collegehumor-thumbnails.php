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

class CollegeHumor_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'CollegeHumor';
	const service_name = 'CollegeHumor';
	// Slug for the video provider
	public $service_slug = 'collegehumor';
	const service_slug = 'collegehumor';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
		'#https?://(?:www\.)?collegehumor\.com/(?:video|e)/([0-9]+)#' // URL
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		$request = "http://www.collegehumor.com/oembed.json?url=http%3A%2F%2Fwww.collegehumor.com%2Fvideo%2F$id";
		$response = wp_remote_get( $request, array( 'sslverify' => false ) );
		if( is_wp_error( $response ) ) {
			$result = new WP_Error( 'collegehumor_info_retrieval', __( 'Error retrieving video information from the URL <a href="' . $request . '">' . $request . '</a> using <code>wp_remote_get()</code><br />If opening that URL in your web browser returns anything else than an error page, the problem may be related to your web server and might be something your host administrator can solve.<br />Details: ' . $response->get_error_message() ) );
		} else {
			$result = json_decode( $response['body'] );
			$result = $result->thumbnail_url;
		}
		return $result;
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<iframe src="http://www.collegehumor.com/e/6830834" width="600" height="338" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe><div style="padding:5px 0; text-align:center; width:600px;"><p><a href="http://www.collegehumor.com/videos/most-viewed/this-year">CollegeHumor\'s Favorite Funny Videos</a></p></div>',
			'expected' => 'http://2.media.collegehumor.cvcdn.com/62/99/20502ca0d5b2172421002b52f437dcf8-mitt-romney-style-gangnam-style-parody.jpg',
			'name' => 'Embed'
		),
		array(
			'markup' => 'http://www.collegehumor.com/video/6830834/mitt-romney-style-gangnam-style-parody',
			'expected' => 'http://2.media.collegehumor.cvcdn.com/62/99/20502ca0d5b2172421002b52f437dcf8-mitt-romney-style-gangnam-style-parody.jpg',
			'name' => 'URL'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'CollegeHumor_Thumbnails', 'register_provider' ) );

?>