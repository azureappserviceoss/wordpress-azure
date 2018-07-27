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

class Funnyordie_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'Funny or Die';
	const service_name = 'Funny or Die';
	// Slug for the video provider
	public $service_slug = 'funnyordie';
	const service_slug = 'funnyordie';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
		'#http://www\.funnyordie\.com/embed/([A-Za-z0-9]+)#', // Iframe src
		'#id="ordie_player_([A-Za-z0-9]+)"#' // Flash object
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		return 'http://assets0.ordienetworks.com/tmbs/' . $id . '/large_11.jpg';
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<iframe src="http://www.funnyordie.com/embed/5325b03b52" width="640" height="400" frameborder="0"></iframe>',
			'expected' => 'http://assets0.ordienetworks.com/tmbs/5325b03b52/large_11.jpg',
			'name' => 'iFrame player'
		),
		array(
			'markup' => '<object width="640" height="400" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="ordie_player_5325b03b52"><param name="movie" value="http://player.ordienetworks.com/flash/fodplayer.swf" /><param name="flashvars" value="key=5325b03b52" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always"><embed width="640" height="400" flashvars="key=5325b03b52" allowfullscreen="true" allowscriptaccess="always" quality="high" src="http://player.ordienetworks.com/flash/fodplayer.swf" name="ordie_player_5325b03b52" type="application/x-shockwave-flash"></embed></object>',
			'expected' => 'http://assets0.ordienetworks.com/tmbs/5325b03b52/large_11.jpg',
			'name' => 'Flash player'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'Funnyordie_Thumbnails', 'register_provider' ) );

?>