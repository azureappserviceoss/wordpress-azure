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

class Justintv_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'Justin.tv';
	const service_name = 'Justin.tv';
	// Slug for the video provider
	public $service_slug = 'justintv';
	const service_slug = 'justintv';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
	    '#archive_id=([0-9]+)#' // Archive ID
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		$request = "http://api.justin.tv/api/clip/show/$id.xml";
		$response = wp_remote_get( $request, array( 'sslverify' => false ) );
		if( is_wp_error( $response ) ) {
			$result = new WP_Error( 'justintv_info_retrieval', __( 'Error retrieving video information from the URL <a href="' . $request . '">' . $request . '</a> using <code>wp_remote_get()</code><br />If opening that URL in your web browser returns anything else than an error page, the problem may be related to your web server and might be something your host administrator can solve.<br />Details: ' . $response->get_error_message() ) );
		} else {
			$xml = new SimpleXMLElement( $response['body'] );
			$result = (string) $xml->object->image_url_large;
		}
		return $result;
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<object type="application/x-shockwave-flash" height="300" width="400" id="clip_embed_player_flash" data="http://www-cdn.justin.tv/widgets/archive_embed_player.swf" bgcolor="#000000"><param name="movie" value="http://www-cdn.justin.tv/widgets/archive_embed_player.swf" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="allowFullScreen" value="true" /><param name="flashvars" value="auto_play=false&start_volume=25&title=Title&channel=scamschoolbrian&archive_id=392481524" /></object>',
			'expected' => 'http://static-cdn.jtvnw.net/jtv.thumbs/archive-392481524-320x240.jpg',
			'name' => 'Embed'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'Justintv_Thumbnails', 'register_provider' ) );

?>