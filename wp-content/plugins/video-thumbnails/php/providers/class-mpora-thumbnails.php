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

class Mpora_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'MPORA';
	const service_name = 'MPORA';
	// Slug for the video provider
	public $service_slug = 'mpora';
	const service_slug = 'mpora';

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	// Regex strings
	public $regexes = array(
		'#http://(?:video\.|www\.)?mpora\.com/(?:ep|videos)/([A-Za-z0-9]+)#', // Flash or iFrame src
		'#mporaplayer_([A-Za-z0-9]+)_#' // Object ID
	);

	// Thumbnail URL
	public function get_thumbnail_url( $id ) {
		return 'http://ugc4.mporatrons.com/thumbs/' . $id . '_640x360_0000.jpg';
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<object width="480" height="270" id="mporaplayer_wEr2CBooV_N" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" type="application/x-shockwave-flash" ><param name="movie" value="http://video.mpora.com/ep/wEr2CBooV/"></param><param name="wmode" value="transparent"></param><param name="allowScriptAccess" value="always"></param><param name="allowFullScreen" value="true"></param><embed src="http://video.mpora.com/ep/wEr2CBooV/" width="480" height="270" wmode="transparent" allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash"></embed></object>',
			'expected' => 'http://ugc4.mporatrons.com/thumbs/wEr2CBooV_640x360_0000.jpg',
			'name' => 'Flash player'
		),
		array(
			'markup' => '<iframe width="640" height="360" src="http://mpora.com/videos/AAdfegovdop0/embed" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
			'expected' => 'http://ugc4.mporatrons.com/thumbs/AAdfegovdop0_640x360_0000.jpg',
			'name' => 'iFrame player'
		),
	);
}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'Mpora_Thumbnails', 'register_provider' ) );

?>