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

// Require thumbnail extension class
require_once( VIDEO_THUMBNAILS_PATH . '/php/extensions/class-video-thumbnails-extension.php' );

class Simple_Video_Embedder_Thumbnails extends Video_Thumbnails_Extension {

	public static function markup( $markup, $post_id ) {
		if ( function_exists( 'p75HasVideo' ) ) {
			if ( p75HasVideo( $post_id ) ) {
				$markup .= ' ' . p75GetVideo( $post_id );
			}
		}
		return $markup;
	}

}

// Add filter to modify markup
add_filter( 'video_thumbnail_markup', array( 'Simple_Video_Embedder_Thumbnails', 'markup' ), 10, 2 );

?>