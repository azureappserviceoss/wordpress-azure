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

class Video_Thumbnails_Extension {

	function __construct() {
		if ( method_exists( $this, 'settings_section') ) add_action( 'video_thumbnails_provider_options', array( &$this, 'settings_section' ) );
	}

}

require_once( VIDEO_THUMBNAILS_PATH . '/php/extensions/class-simple-video-embedder-thumbnails.php' );
require_once( VIDEO_THUMBNAILS_PATH . '/php/extensions/class-ayvp-thumbnails.php' );

?>