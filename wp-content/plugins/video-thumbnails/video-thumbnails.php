<?php
/*
Plugin Name: Video Thumbnails
Plugin URI: http://refactored.co/plugins/video-thumbnails
Description: Automatically retrieve video thumbnails for your posts and display them in your theme. Currently supports YouTube, Vimeo, Facebook, Blip.tv, Justin.tv, Dailymotion, Metacafe, Wistia, Youku, Funny or Die, and MPORA.
Author: Sutherland Boswell
Author URI: http://sutherlandboswell.com
Version: 2.0.8
License: GPL2
*/
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

// Define

define( 'VIDEO_THUMBNAILS_PATH', dirname(__FILE__) );
define( 'VIDEO_THUMBNAILS_FIELD', '_video_thumbnail' );
define( 'VIDEO_THUMBNAILS_VERSION', '2.0.8' );

// Providers
require_once( VIDEO_THUMBNAILS_PATH . '/php/providers/class-video-thumbnails-providers.php' );

// Extensions
require_once( VIDEO_THUMBNAILS_PATH . '/php/extensions/class-video-thumbnails-extension.php' );

// Settings
require_once( VIDEO_THUMBNAILS_PATH . '/php/class-video-thumbnails-settings.php' );

// Class

class Video_Thumbnails {

	var $providers = array();
	var $settings;

	function __construct() {

		// Create provider array
		$this->providers = apply_filters( 'video_thumbnail_providers', $this->providers );

		// Settings
		$this->settings = new Video_Thumbnails_Settings();

		// Initialize meta box
		add_action( 'admin_init', array( &$this, 'meta_box_init' ) );

		// Add actions to save video thumbnails when saving
		add_action( 'save_post', array( &$this, 'save_video_thumbnail' ), 100, 1 );

		// Add actions to save video thumbnails when posting from XML-RPC (this action passes the post ID as an argument so 'get_video_thumbnail' is used instead)
		add_action( 'xmlrpc_publish_post', 'get_video_thumbnail', 10, 1 );

		// Add action for Ajax reset script on edit pages
		if ( in_array( basename( $_SERVER['PHP_SELF'] ), apply_filters( 'video_thumbnails_editor_pages', array( 'post-new.php', 'page-new.php', 'post.php', 'page.php' ) ) ) ) {
			add_action( 'admin_head', array( &$this, 'ajax_reset_script' ) );
		}

		// Add action for Ajax reset callback
		add_action( 'wp_ajax_reset_video_thumbnail', array( &$this, 'ajax_reset_callback' ) );

	}

	// Initialize meta box on edit page
	function meta_box_init() {
		if ( is_array( $this->settings->options['post_types'] ) ) {
			foreach ( $this->settings->options['post_types'] as $type ) {
				add_meta_box( 'video_thumbnail', 'Video Thumbnail', array( &$this, 'meta_box' ), $type, 'side', 'low' );
			}
		}
	}

	// Construct the meta box
	function meta_box() {
		global $post;
		$custom = get_post_custom( $post->ID );
		if ( isset( $custom[VIDEO_THUMBNAILS_FIELD][0] ) ) $video_thumbnail = $custom[VIDEO_THUMBNAILS_FIELD][0];

		if ( isset( $video_thumbnail ) && $video_thumbnail != '' ) {
			echo '<p id="video-thumbnails-preview"><img src="' . $video_thumbnail . '" style="max-width:100%;" /></p>';	}

		if ( get_post_status() == 'publish' || get_post_status() == 'private' ) {
			if ( isset( $video_thumbnail ) && $video_thumbnail != '' ) {
				echo '<p><a href="#" id="video-thumbnails-reset" onclick="video_thumbnails_reset(\'' . $post->ID . '\' );return false;">Reset Video Thumbnail</a></p>';
			} else {
				echo '<p id="video-thumbnails-preview">We didn\'t find a video thumbnail for this post.</p>';
				echo '<p><a href="#" id="video-thumbnails-reset" onclick="video_thumbnails_reset(\'' . $post->ID . '\' );return false;">Search Again</a></p>';
			}
		} else {
			if ( isset( $video_thumbnail ) && $video_thumbnail != '' ) {
				echo '<p><a href="#" id="video-thumbnails-reset" onclick="video_thumbnails_reset(\'' . $post->ID . '\' );return false;">Reset Video Thumbnail</a></p>';
			} else {
				echo '<p>A video thumbnail will be found for this post when it is published.</p>';
			}
		}
	}

	// The main event
	function get_video_thumbnail( $post_id = null ) {

		// Get the post ID if none is provided
		if ( $post_id == null OR $post_id == '' ) $post_id = get_the_ID();

		// Check to see if thumbnail has already been found
		if( ( $thumbnail_meta = get_post_meta( $post_id, VIDEO_THUMBNAILS_FIELD, true ) ) != '' ) {
			return $thumbnail_meta;
		}
		// If the thumbnail isn't stored in custom meta, fetch a thumbnail
		else {

			$new_thumbnail = null;
			// Filter for extensions to set thumbnail
			$new_thumbnail = apply_filters( 'new_video_thumbnail_url', $new_thumbnail, $post_id );

			if ( $new_thumbnail == null ) {
				// Get the post or custom field to search
				if ( $this->settings->options['custom_field'] ) {
					$markup = get_post_meta( $post_id, $this->settings->options['custom_field'], true );
				} else {
					$post_array = get_post( $post_id );
					$markup = $post_array->post_content;
					$markup = apply_filters( 'the_content', $markup );
				}

				// Filter for extensions to modify what markup is scanned
				$markup = apply_filters( 'video_thumbnail_markup', $markup, $post_id );

				// Filter to modify providers immediately before scanning
				$providers = apply_filters( 'video_thumbnail_providers_pre_scan', $this->providers );

				foreach ( $providers as $key => $provider ) {
					$new_thumbnail = $provider->scan_for_thumbnail( $markup );
					if ( $new_thumbnail != null ) break;
				}
			}

			// Return the new thumbnail variable and update meta if one is found
			if ( $new_thumbnail != null ) {

				// Save as Attachment if enabled
				if ( $this->settings->options['save_media'] == 1 ) {
					$attachment_id = $this->save_to_media_library( $new_thumbnail, $post_id );
					$new_thumbnail = wp_get_attachment_image_src( $attachment_id, 'full' );
					$new_thumbnail = $new_thumbnail[0];
				}

				// Add hidden custom field with thumbnail URL
				if ( !update_post_meta( $post_id, VIDEO_THUMBNAILS_FIELD, $new_thumbnail ) ) add_post_meta( $post_id, VIDEO_THUMBNAILS_FIELD, $new_thumbnail, true );

				// Set attachment as featured image if enabled
				if ( $this->settings->options['set_featured'] == 1 && $this->settings->options['save_media'] == 1 ) {
					// Make sure there isn't already a post thumbnail
					if ( !ctype_digit( get_post_thumbnail_id( $post_id ) ) ) {
						set_post_thumbnail( $post_id, $attachment_id );
					}
				}
			}
			return $new_thumbnail;

		}
	}

	/**
	 * Gets a video thumbnail when a published post is saved
	 * @param  int $post_id The post ID
	 */
	function save_video_thumbnail( $post_id ) {
		// Don't save video thumbnails during autosave or for unpublished posts
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return null;
		if ( get_post_status( $post_id ) != 'publish' ) return null;
		// Check that Video Thumbnails are enabled for current post type
		$post_type = get_post_type( $post_id );
		if ( in_array( $post_type, (array) $this->settings->options['post_types'] ) || $post_type == $this->settings->options['post_types'] ) {
			$this->get_video_thumbnail( $post_id );
		} else {
			return null;
		}
	}

	// Saves to media library
	public function save_to_media_library( $image_url, $post_id ) {

		$error = '';
		$response = wp_remote_get( $image_url, array( 'sslverify' => false ) );
		if( is_wp_error( $response ) ) {
			$error = new WP_Error( 'thumbnail_retrieval', __( 'Error retrieving a thumbnail from the URL <a href="' . $image_url . '">' . $image_url . '</a> using <code>wp_remote_get()</code><br />If opening that URL in your web browser returns anything else than an error page, the problem may be related to your web server and might be something your host administrator can solve.<br />Details: ' . $response->get_error_message() ) );
		} else {
			$image_contents = $response['body'];
			$image_type = wp_remote_retrieve_header( $response, 'content-type' );
		}

		if ( $error != '' ) {
			return $error;
		} else {

			// Translate MIME type into an extension
			if ( $image_type == 'image/jpeg' ) $image_extension = '.jpg';
			elseif ( $image_extension == 'image/png' ) $image_extension = '.png';

			// Construct a file name using post slug and extension
			$new_filename = urldecode( basename( get_permalink( $post_id ) ) ) . $image_extension;

			// Save the image bits using the new filename
			$upload = wp_upload_bits( $new_filename, null, $image_contents );

			// Stop for any errors while saving the data or else continue adding the image to the media library
			if ( $upload['error'] ) {
				$error = new WP_Error( 'thumbnail_upload', __( 'Error uploading image data:' ) . ' ' . $upload['error'] );
				return $error;
			} else {

				$image_url = $upload['url'];

				$filename = $upload['file'];

				$wp_filetype = wp_check_filetype( basename( $filename ), null );
				$attachment = array(
					'post_mime_type'	=> $wp_filetype['type'],
					'post_title'		=> get_the_title( $post_id ),
					'post_content'		=> '',
					'post_status'		=> 'inherit'
				);
				$attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
				// you must first include the image.php file
				// for the function wp_generate_attachment_metadata() to work
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
				wp_update_attachment_metadata( $attach_id, $attach_data );

				// Add field to mark image as a video thumbnail
				update_post_meta( $attach_id, 'video_thumbnail', '1' );

			}

		}

		return $attach_id;

	} // End of save to media library function

	// Post editor Ajax reset script
	function ajax_reset_script() {
		echo '<!-- Video Thumbnails Ajax Search -->' . PHP_EOL;
		echo '<script type="text/javascript">' . PHP_EOL;
		echo 'function video_thumbnails_reset(id) {' . PHP_EOL;
		echo '  var data = {' . PHP_EOL;
		echo '    action: "reset_video_thumbnail",' . PHP_EOL;
		echo '    post_id: id' . PHP_EOL;
		echo '  };' . PHP_EOL;
		echo '  document.getElementById(\'video-thumbnails-preview\').innerHTML=\'Working... <img src="' . home_url( 'wp-admin/images/loading.gif' ) . '"/>\';' . PHP_EOL;
		echo '  jQuery.post(ajaxurl, data, function(response){' . PHP_EOL;
		echo '    document.getElementById(\'video-thumbnails-preview\').innerHTML=response;' . PHP_EOL;
		echo '  });' . PHP_EOL;
		echo '};' . PHP_EOL;
		echo '</script>' . PHP_EOL;
	}

	// Ajax reset callback
	function ajax_reset_callback() {
		global $wpdb; // this is how you get access to the database

		$post_id = $_POST['post_id'];

		delete_post_meta( $post_id, VIDEO_THUMBNAILS_FIELD );

		$video_thumbnail = get_video_thumbnail( $post_id );

		if ( is_wp_error( $video_thumbnail ) ) {
			echo $video_thumbnail->get_error_message();
		} else if ( $video_thumbnail != null ) {
			echo '<img src="' . $video_thumbnail . '" style="max-width:100%;" />';
		} else {
			echo 'We didn\'t find a video thumbnail for this post. (be sure you have saved changes first)';
		}

		die();
	}

}

$video_thumbnails = new Video_Thumbnails();

// End class

// Get video thumbnail function
function get_video_thumbnail( $post_id = null ) {
	global $video_thumbnails;
	return $video_thumbnails->get_video_thumbnail( $post_id );
}

// Echo thumbnail
function video_thumbnail( $post_id = null ) {
	if ( ( $video_thumbnail = get_video_thumbnail( $post_id ) ) == null ) { echo plugins_url() . '/video-thumbnails/default.jpg'; }
	else { echo $video_thumbnail; }
}

?>