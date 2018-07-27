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

class YouTube_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'YouTube';
	const service_name = 'YouTube';
	// Slug for the video provider
	public $service_slug = 'youtube';
	const service_slug = 'youtube';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
	    '#<object[^>]+>.+?(?:https?:)?//www\.youtube(?:\-nocookie)?\.com/[ve]/([A-Za-z0-9\-_]+).+?</object>#s', // Old standard YouTube embed
	    '#(?:https?:)?//www\.youtube(?:\-nocookie)?\.com/[ve]/([A-Za-z0-9\-_]+)#', // More comprehensive search for old YouTube embed (probably can be removed)
	    '#(?:https?:)?//www\.youtube(?:\-nocookie)?\.com/embed/([A-Za-z0-9\-_]+)#', // YouTube iframe, the new standard since at least 2011
	    '#(?:https?(?:a|vh?)?://)?(?:www\.)?youtube(?:\-nocookie)?\.com/watch\?.*v=([A-Za-z0-9\-_]+)#', // Any YouTube URL. After http(s) support a or v for Youtube Lyte and v or vh for Smart Youtube plugin
	    '#(?:https?(?:a|vh?)?://)?youtu\.be/([A-Za-z0-9\-_]+)#', // Any shortened youtu.be URL. After http(s) a or v for Youtube Lyte and v or vh for Smart Youtube plugin
	    '#<div class="lyte" id="([A-Za-z0-9\-_]+)"#' // YouTube Lyte
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		$maxres = 'http://img.youtube.com/vi/' . $id . '/maxresdefault.jpg';
		$response = wp_remote_head( $maxres );
		if ( !is_wp_error( $response ) && $response['response']['code'] == '200' ) {
			$result = $maxres;
		} else {
			$result = 'http://img.youtube.com/vi/' . $id . '/0.jpg';
		}
		return $result;
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<iframe width="560" height="315" src="http://www.youtube.com/embed/Fp0U2Vglkjw" frameborder="0" allowfullscreen></iframe>',
			'expected' => 'http://img.youtube.com/vi/Fp0U2Vglkjw/maxresdefault.jpg',
			'name' => 'iFrame HD'
		),
		array(
			'markup' => '<object width="560" height="315"><param name="movie" value="http://www.youtube.com/v/Fp0U2Vglkjw?version=3&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/Fp0U2Vglkjw?version=3&amp;hl=en_US" type="application/x-shockwave-flash" width="560" height="315" allowscriptaccess="always" allowfullscreen="true"></embed></object>',
			'expected' => 'http://img.youtube.com/vi/Fp0U2Vglkjw/maxresdefault.jpg',
			'name' => 'Old embed HD'
		),
		array(
			'markup' => '<iframe width="560" height="315" src="http://www.youtube.com/embed/vv_AitYPjtc" frameborder="0" allowfullscreen></iframe>',
			'expected' => 'http://img.youtube.com/vi/vv_AitYPjtc/0.jpg',
			'name' => 'iFrame SD'
		),
		array(
			'markup' => '<object width="560" height="315"><param name="movie" value="http://www.youtube.com/v/vv_AitYPjtc?version=3&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/vv_AitYPjtc?version=3&amp;hl=en_US" type="application/x-shockwave-flash" width="560" height="315" allowscriptaccess="always" allowfullscreen="true"></embed></object>',
			'expected' => 'http://img.youtube.com/vi/vv_AitYPjtc/0.jpg',
			'name' => 'Old embed SD'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'YouTube_Thumbnails', 'register_provider' ) );

?>