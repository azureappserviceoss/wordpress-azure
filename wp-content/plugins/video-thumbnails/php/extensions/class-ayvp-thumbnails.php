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

// Require YouTube provider class
require_once( VIDEO_THUMBNAILS_PATH . '/php/providers/class-youtube-thumbnails.php' );

class AYVP_Thumbnails extends Video_Thumbnails_Extension {

	public static function new_thumbnail( $new_thumbnail, $post_id ) {
		if ( $new_thumbnail == null ) {
			$youtube_id = get_post_meta( $post_id, '_tern_wp_youtube_video', true );
			if ( $youtube_id != '' ) {
				$new_thumbnail = YouTube_Thumbnails::get_thumbnail_url( $youtube_id );
			}
		}
		return $new_thumbnail;
	}

}

// Make sure we can use is_plugin_active()
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// If AYVP is active, add filter
if ( is_plugin_active( 'automatic-youtube-video-posts/tern_wp_youtube.php' ) ) {
	add_filter( 'new_video_thumbnail_url', array( 'AYVP_Thumbnails', 'new_thumbnail' ), 10, 2 );
	remove_filter( 'post_thumbnail_html', 'WP_ayvpp_thumbnail' );
	remove_filter( 'post_thumbnail_size', 'WP_ayvpp_thumbnail_size' );
}

?>