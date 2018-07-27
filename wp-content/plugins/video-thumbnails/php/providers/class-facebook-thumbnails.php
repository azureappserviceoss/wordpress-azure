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

class Facebook_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'Facebook';
	const service_name = 'Facebook';
	// Slug for the video provider
	public $service_slug = 'facebook';
	const service_slug = 'facebook';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
		'#"http://www\.facebook\.com/v/([0-9]+)"#', // Flash Embed
		'#"https?://www\.facebook\.com/video/embed\?video_id=([0-9]+)"#' // iFrame Embed
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		return 'https://graph.facebook.com/' . $id . '/picture';
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<object width=420 height=180><param name=allowfullscreen value=true></param><param name=allowscriptaccess value=always></param><param name=movie value="http://www.facebook.com/v/2560032632599"></param><embed src="http://www.facebook.com/v/2560032632599" type="application/x-shockwave-flash" allowscriptaccess=always allowfullscreen=true width=420 height=180></embed></object>',
			'expected' => 'https://graph.facebook.com/2560032632599/picture',
			'name' => 'Flash Embed'
		),
		array(
			'markup' => '<iframe src="https://www.facebook.com/video/embed?video_id=2560032632599" width="960" height="720" frameborder="0"></iframe>',
			'expected' => 'https://graph.facebook.com/2560032632599/picture',
			'name' => 'iFrame Embed'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'Facebook_Thumbnails', 'register_provider' ) );

?>