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

class Wistia_Thumbnails extends Video_Thumbnails_Providers {

	// Human-readable name of the video provider
	public $service_name = 'Wistia';
	const service_name = 'Wistia';
	// Slug for the video provider
	public $service_slug = 'wistia';
	const service_slug = 'wistia';

	// public $options_section = array(
	// 	'description' => '<p><strong>Optional</strong>: Only required if you have a CNAME record set up to use a custom domain.</p>',
	// 	'fields' => array(
	// 		'domain' => array(
	// 			'name' => 'Custom Wistia Domain',
	// 			'type' => 'text',
	// 			'description' => 'Enter the domain corresponding to your CNAME record for Wistia. Ex: videos.example.com'
	// 		)
	// 	)
	// );

	public static function register_provider( $providers ) {
		$providers[self::service_slug] = new self;
		return $providers;
	}

	public function scan_for_thumbnail( $markup ) {
		// Find thumbnail URL if embedded in player
		$thumb_regex = '#https://wistia\.sslcs\.cdngc\.net/deliveries/[0-9a-zA-Z]+\.jpg#';
		if ( preg_match( $thumb_regex, urldecode( $markup ), $matches ) ) {
			return $matches[0];
		}
		$oembed_regex = '#https?://(.+)?(wistia\.com|wistia\.net|wi\.st)/(medias|embed)/(?:[\+~%\/\.\w\-]*)#';
		if ( preg_match( $oembed_regex, urldecode( $markup ), $matches ) ) {
			return $this->get_thumbnail_url( $matches[0] );
		}
		// Run regex for oEmbed API
		foreach ( $this->regexes as $regex ) {
			if ( preg_match( $regex, $markup, $matches ) ) {
				return $this->get_thumbnail_url( 'http://fast.wistia.net/embed/iframe/' . $matches[1] );
			}
		}
	}

	// Regex strings
	public $regexes = array(
		'#Wistia\.embed\("([0-9a-zA-Z]+)"#', // JavaScript API embedding
	);

	// Thumbnail URL
	public function get_thumbnail_url( $url ) {
		$url = urlencode( $url );
		$request = "http://fast.wistia.com/oembed?url=$url";
		$response = wp_remote_get( $request, array( 'sslverify' => false ) );
		if( is_wp_error( $response ) ) {
			$result = new WP_Error( 'wistia_info_retrieval', __( 'Error retrieving video information from the URL <a href="' . $request . '">' . $request . '</a> using <code>wp_remote_get()</code><br />If opening that URL in your web browser returns anything else than an error page, the problem may be related to your web server and might be something your host administrator can solve.<br />Details: ' . $response->get_error_message() ) );
		} else {
			$result = json_decode( $response['body'] );
			$result = $result->thumbnail_url;
		}
		return $result;
	}

	// Test cases
	public $test_cases = array(
		array(
			'markup' => '<iframe src="http://fast.wistia.net/embed/iframe/po4utu3zde?controlsVisibleOnLoad=true&version=v1&videoHeight=360&videoWidth=640&volumeControl=true" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" width="640" height="360"></iframe>',
			'expected' => 'http://embed-0.wistia.com/deliveries/6928fcba8355e38de4d95863a659e1de23cb2071.jpg',
			'name' => 'Inline player'
		),
		array(
			'markup' => '<div class=\'wistia_embed\' data-video-height=\'312\' data-video-width=\'499\' id=\'wistia_j1qd2lvys1\'></div> <script charset=\'ISO-8859-1\' src=\'http://fast.wistia.com/static/concat/E-v1.js\'></script> <script> var platform = ( Modernizr.touch ) ? "html5" : "flash"; wistiaEmbed = Wistia.embed("j1qd2lvys1", { version: "v1", videoWidth: 499, videoHeight: 312, playButton: Modernizr.touch, smallPlayButton: Modernizr.touch, playbar: Modernizr.touch, platformPreference: platform, chromeless: Modernizr.touch ? false : true, fullscreenButton: false, autoPlay: !Modernizr.touch, videoFoam: true }); </script>',
			'expected' => 'http://embed.wistia.com/deliveries/a086707fe096e7f3fbefef1d1dcba1488d23a3e9.jpg',
			'name' => 'JavaScript API embedding'
		),
	);

}

// Add to provider array
add_filter( 'video_thumbnail_providers', array( 'Wistia_Thumbnails', 'register_provider' ) );

?>