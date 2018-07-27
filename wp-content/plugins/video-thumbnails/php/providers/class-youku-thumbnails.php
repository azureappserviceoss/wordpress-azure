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

class Youku_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'Youku';
	const service_name = 'Youku';
	// Slug for the video provider
	public $service_slug = 'youku';
	const service_slug = 'youku';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
		'#http://player\.youku\.com/player\.php/sid/([A-Za-z0-9]+)/v\.swf#', // Flash
		'#http://v\.youku\.com/v_show/id_([A-Za-z0-9]+)\.html#' // Link
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		$request = "http://v.youku.com/player/getPlayList/VideoIDS/$id/";
		$response = wp_remote_get( $request, array( 'sslverify' => false ) );
		if( is_wp_error( $response ) ) {
			$result = new WP_Error( 'youku_info_retrieval', __( 'Error retrieving video information from the URL <a href="' . $request . '">' . $request . '</a> using <code>wp_remote_get()</code><br />If opening that URL in your web browser returns anything else than an error page, the problem may be related to your web server and might be something your host administrator can solve.<br />Details: ' . $response->get_error_message() ) );
		} else {
			$result = json_decode( $response['body'] );
			$result = $result->data[0]->logo;
		}
		return $result;
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<embed src="http://player.youku.com/player.php/sid/XMzQyMzk5MzQ4/v.swf" quality="high" width="480" height="400" align="middle" allowScriptAccess="sameDomain" allowFullscreen="true" type="application/x-shockwave-flash"></embed>',
			'expected' => 'http://g1.ykimg.com/1100641F464F0FB57407E2053DFCBC802FBBC4-E4C5-7A58-0394-26C366F10493',
			'name' => 'Flash embed'
		),
		array(
			'markup' => 'http://v.youku.com/v_show/id_XMzQyMzk5MzQ4.html',
			'expected' => 'http://g1.ykimg.com/1100641F464F0FB57407E2053DFCBC802FBBC4-E4C5-7A58-0394-26C366F10493',
			'name' => 'Link'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'Youku_Thumbnails', 'register_provider' ) );

?>