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

class Video_Thumbnails_Settings {

	public $options;

	var $default_options = array(
		'save_media'   => 1,
		'set_featured' => 1,
		'post_types'   => array( 'post' ),
		'custom_field' => ''
	);

	function __construct() {
		// Activation and deactivation hooks
		register_activation_hook( VIDEO_THUMBNAILS_PATH . '/video-thumbnails.php', array( &$this, 'plugin_activation' ) );
		register_deactivation_hook( VIDEO_THUMBNAILS_PATH . '/video-thumbnails.php', array( &$this, 'plugin_deactivation' ) );
		// Set current options
		add_action( 'plugins_loaded', array( &$this, 'set_options' ) );
		// Add options page to menu
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		// Initialize options
		add_action( 'admin_init', array( &$this, 'initialize_options' ) );
		// Ajax past search callback
		add_action( 'wp_ajax_past_video_thumbnail', array( &$this, 'ajax_past_callback' ) );
		// Ajax past search callback
		add_action( 'wp_ajax_clear_all_video_thumbnails', array( &$this, 'ajax_clear_all_callback' ) );
		// Ajax test callbacks
		add_action( 'wp_ajax_video_thumbnail_provider_test', array( &$this, 'provider_test_callback' ) ); // Provider test
		add_action( 'wp_ajax_video_thumbnail_saving_media_test', array( &$this, 'saving_media_test_callback' ) ); // Saving media test
		add_action( 'wp_ajax_video_thumbnail_markup_detection_test', array( &$this, 'markup_detection_test_callback' ) ); // Markup input test
		// Settings page actions
		if ( isset ( $_GET['page'] ) && ( $_GET['page'] == 'video_thumbnails' ) ) {
			// Admin scripts
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts' ) );
			// Ajax past posts script
			add_action( 'admin_head', array( &$this, 'ajax_past_script' ) );
		}
	}

	// Activation hook
	function plugin_activation() {
		add_option( 'video_thumbnails', $this->default_options );
	}

	// Deactivation hook
	function plugin_deactivation() {
		delete_option( 'video_thumbnails' );
	}

	// Set options & possibly upgrade
	function set_options() {
		// Get the current options from the database
		$options = get_option( 'video_thumbnails' );
		// If there aren't any options, load the defaults
		if ( ! $options ) $options = $this->default_options;
		// Check if our options need upgrading
		$options = $this->upgrade_options( $options );
		// Set the options class variable
		$this->options = $options;
	}

	function upgrade_options( $options ) {

		// Boolean for if options need updating
		$options_need_updating = false;

		// If there isn't a settings version we need to check for pre 2.0 settings
		if ( ! isset( $options['version'] ) ) {

			// Check for post type setting
			$post_types = get_option( 'video_thumbnails_post_types' );

			// If there is a a post type option we know there should be others
			if ( $post_types !== false ) {

				$options['post_types'] = $post_types;
				delete_option( 'video_thumbnails_post_types' );

				$options['save_media'] = get_option( 'video_thumbnails_save_media' );
				delete_option( 'video_thumbnails_save_media' );

				$options['set_featured'] = get_option( 'video_thumbnails_set_featured' );
				delete_option( 'video_thumbnails_set_featured' );

				$options['custom_field'] = get_option( 'video_thumbnails_custom_field' );
				delete_option( 'video_thumbnails_custom_field' );

			}

			// Updates the options version to 2.0
			$options['version'] = '2.0';
			$options_need_updating = true;

		}

		if ( version_compare( $options['version'], VIDEO_THUMBNAILS_VERSION, '<' ) ) {
			$options['version'] = VIDEO_THUMBNAILS_VERSION;
			$options_need_updating = true;
		}

		// Save options to database if they've been updated
		if ( $options_need_updating ) {
			update_option( 'video_thumbnails', $options );
		}

		return $options;

	}

	function admin_menu() {
		add_options_page(
			'Video Thumbnail Options',
			'Video Thumbnails',
			'manage_options',
			'video_thumbnails',
			array( &$this, 'options_page' )
		);
	}

	function admin_scripts() {
		wp_enqueue_script( 'video_thumbnails_test', plugins_url( 'js/test.js' , VIDEO_THUMBNAILS_PATH . '/video-thumbnails.php' ) );
		wp_enqueue_script( 'video_thumbnails_clear', plugins_url( 'js/clear.js' , VIDEO_THUMBNAILS_PATH . '/video-thumbnails.php' ) );
	}

	function ajax_past_script() {

?><!-- Video Thumbnails Past Post Ajax -->
<script type="text/javascript">
function video_thumbnails_past(id) {

	var data = {
		action: 'past_video_thumbnail',
		post_id: id
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response){

		document.getElementById(id+'_result').innerHTML = response;

	});

};
<?php
$posts = get_posts( array(
	'showposts' => -1,
	'post_type' => $this->options['post_types']
) );

if ( $posts ) {
	foreach ( $posts as $post ) {
		$post_ids[] = $post->ID;
	}
	$ids = implode( ', ', $post_ids );
}
?>

var scanComplete = false;

function scan_video_thumbnails(){

	if(scanComplete==false){
		scanComplete = true;
		var ids = new Array(<?php echo $ids; ?>);
		for (var i = 0; i < ids.length; i++){
			var container = document.getElementById('video-thumbnails-past');
			var new_element = document.createElement('li');
			new_element.setAttribute('id',ids[i]+'_result');
			new_element.innerHTML = 'Waiting...';
			container.insertBefore(new_element, container.firstChild);
		}
		for (var i = 0; i < ids.length; i++){
			document.getElementById(ids[i]+'_result').innerHTML = '<span style="color:yellow">&#8226;</span> Working...';
			video_thumbnails_past(ids[i]);
		}
	} else {
		alert('Scan has already been run, please reload the page before trying again.')
	}

}
</script><?php

	}

	function ajax_past_callback() {
		global $wpdb; // this is how you get access to the database

		$post_id = $_POST['post_id'];

		echo get_the_title( $post_id ) . ' - ';

		$video_thumbnail = get_video_thumbnail( $post_id );

		if ( is_wp_error( $video_thumbnail ) ) {
			echo $video_thumbnail->get_error_message();
		} else if ( $video_thumbnail != null ) {
			echo '<span style="color:green">&#10004;</span> Success!';
		} else {
			echo '<span style="color:red">&#10006;</span> Couldn\'t find a video thumbnail for this post.';
		}

		die();
	}

	function ajax_clear_all_callback() {
		if ( wp_verify_nonce( $_POST['nonce'], 'clear_all_video_thumbnails' ) ) {
			global $wpdb;
			// Clear images from media library
			$media_library_items = get_posts( array(
				'showposts'  => -1,
				'post_type'  => 'attachment',
				'meta_key'   => 'video_thumbnail',
				'meta_value' => '1',
				'fields'     => 'ids'
			) );
			foreach ( $media_library_items as $item ) {
				wp_delete_attachment( $item, true );
			}
			echo '<p><span style="color:green">&#10004;</span> ' . sprintf( _n( '1 attachment deleted', '%s attachments deleted', count( $media_library_items ), 'video-thumbnails' ), count( $media_library_items ) ) . '</p>';
			// Clear custom fields
			$custom_fields_cleared = $wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key='_video_thumbnail'" );
			echo '<p><span style="color:green">&#10004;</span> ' . sprintf( _n( '1 custom field cleared', '%s custom fields cleared', $custom_fields_cleared, 'video-thumbnails' ), $custom_fields_cleared ) . '</p>';
		} else {
			echo '<p><span style="color:red">&#10006;</span> <strong>Error</strong>: Could not verify nonce.</p>';
		}

		die();
	}

	function provider_test_callback() {

		global $video_thumbnails;

		?>
			<table class="widefat">
				<thead>
					<tr>
						<th>Name</th>
						<th>Pass/Fail</th>
						<th>Result</th>
					</tr>
				</thead>
				<tbody>
			<?php
			$passed = 0;
			$failed = 0;
			foreach ( $video_thumbnails->providers as $provider ) {
				foreach ( $provider->test_cases as $test_case ) {
					echo '<tr>';
					echo '<td><strong>' . $provider->service_name . '</strong> - ' . $test_case['name'] . '</td>';
					$result = $provider->scan_for_thumbnail( $test_case['markup'] );
					if ( is_wp_error( $result ) ) {
						$error_string = $result->get_error_message();
						echo '<td style="color:red;">&#10007; Failed</td>';
						echo '<td>';
						echo '<div class="error"><p>' . $error_string . '</p></div>';
						echo '</td>';
						$failed++;
					} else {
						$result = explode( '?', $result );
						$result = $result[0];
						if ( $result == $test_case['expected'] ) {
							echo '<td style="color:green;">&#10004; Passed</td>';
							$passed++;
						} else {
							echo '<td style="color:red;">&#10007; Failed</td>';
							$failed++;
						}
						echo '<td>' . $result . '</td>';
					}
					echo '</tr>';
				}
			} ?>
				<tbody>
				<tfoot>
					<tr>
						<th></th>
						<th><span style="color:green;">&#10004; <?php echo $passed; ?></span> / <span style="color:red;">&#10007; <?php echo $failed; ?></span></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		<?php die();
	} // End provider test callback

	function saving_media_test_callback() {

		// Try saving 'http://img.youtube.com/vi/dMH0bHeiRNg/0.jpg' to media library
		$attachment_id = Video_Thumbnails::save_to_media_library( 'http://img.youtube.com/vi/dMH0bHeiRNg/0.jpg', 1 );
		if ( is_wp_error( $attachment_id ) ) {
			echo '<p><span style="color:red;">&#10006;</span> ' . $attachment_id->get_error_message() . '</p>';
		} else {
			echo '<p><span style="color:green;">&#10004;</span>Attachment created with an ID of ' . $attachment_id . '</p>';
			wp_delete_attachment( $attachment_id, true );
			echo '<p><span style="color:green;">&#10004;</span>Attachment with an ID of ' . $attachment_id . ' deleted</p>';			
		}

		die();
	} // End saving media test callback

	function markup_detection_test_callback() {

		$new_thumbnail = null;

		global $video_thumbnails;

		foreach ( $video_thumbnails->providers as $provider ) {
			$new_thumbnail = $provider->scan_for_thumbnail( stripslashes( $_POST['markup'] ) );
			if ( $new_thumbnail != null ) break;
		}

		if ( $new_thumbnail == null ) {
			echo '<p><span style="color:red;">&#10006;</span> No thumbnail found</p>';
		} elseif ( is_wp_error( $new_thumbnail ) ) {
			echo '<p><span style="color:red;">&#10006;</span> Error: ' . $new_thumbnail->get_error_message() . '</p>';
		} else {
			echo '<p><span style="color:green;">&#10004;</span> Thumbnail found! <a href="' . $new_thumbnail . '" target="_blank">View full size</a></p>';
			echo '<p><img src="' . $new_thumbnail . '" style="max-width: 500px;"></p>';
		}

		die();
	} // End markup detection test callback

	function initialize_options() {
		add_settings_section(  
			'general_settings_section',
			'General Settings',
			array( &$this, 'general_settings_callback' ),
			'video_thumbnails'
		);
		$this->add_checkbox_setting(
			'save_media',
			'Save Thumbnails to Media Library',
			'Checking this option will download video thumbnails to your server'
		);
		$this->add_checkbox_setting(
			'set_featured',
			'Automatically Set Featured Image',
			'Check this option to automatically set video thumbnails as the featured image (requires saving to media library)'
		);
		// Get post types
		$post_types = get_post_types( null, 'names' );
		// Remove certain post types from array
		$post_types = array_diff( $post_types, array( 'attachment', 'revision', 'nav_menu_item' ) );
		$this->add_multicheckbox_setting(
			'post_types',
			'Post Types',
			$post_types
		);
		$this->add_text_setting(
			'custom_field',
			'Custom Field (optional)',
			'Enter the name of the custom field where your embed code or video URL is stored.'
		);
		register_setting( 'video_thumbnails', 'video_thumbnails', array( &$this, 'sanitize_callback' ) );
	}

	function sanitize_callback( $input ) {   
		$current_settings = get_option( 'video_thumbnails' );
		$output = array();
		// General settings
		if ( !isset( $input['provider_options'] ) ) {
			foreach( $current_settings as $key => $value ) {
				if ( $key == 'version' OR $key == 'providers' ) {
					$output[$key] = $current_settings[$key];
				} elseif ( isset( $input[$key] ) ) {
					$output[$key] = $input[$key];
				} else {
					$output[$key] = '';
				}
			}
		}
		// Provider settings
		else {
			$output = $current_settings;
			unset( $output['providers'] );
			$output['providers'] = $input['providers'];
		}
		return $output;
	}  

	function general_settings_callback() {  
		echo '<p>These options configure where the plugin will search for videos and what to do with thumbnails once found.</p>';  
	}

	function add_checkbox_setting( $slug, $name, $description ) {
		add_settings_field(
			$slug,
			$name,
			array( &$this, 'checkbox_callback' ),
			'video_thumbnails',
			'general_settings_section',
			array(
				'slug'        => $slug,
				'description' => $description
			)
		);
	}

	function checkbox_callback( $args ) {
		$html = '<label for="' . $args['slug'] . '"><input type="checkbox" id="' . $args['slug'] . '" name="video_thumbnails[' . $args['slug'] . ']" value="1" ' . checked( 1, $this->options[$args['slug']], false ) . '/> ' . $args['description'] . '</label>';
		echo $html;
	}

	function add_multicheckbox_setting( $slug, $name, $options ) {
		add_settings_field(
			$slug,
			$name,
			array( &$this, 'multicheckbox_callback' ),
			'video_thumbnails',
			'general_settings_section',
			array(
				'slug'    => $slug,
				'options' => $options
			)
		);
	}

	function multicheckbox_callback( $args ) {
		if ( is_array( $this->options[$args['slug']] ) ) {
			$selected_types = $this->options[$args['slug']];
		} else {
			$selected_types = array();
		}
		$html = '';
		foreach ( $args['options'] as $option ) {
			$checked = ( in_array( $option, $selected_types ) ? 'checked="checked"' : '' );
			$html .= '<label for="' . $args['slug'] . '_' . $option . '"><input type="checkbox" id="' . $args['slug'] . '_' . $option . '" name="video_thumbnails[' . $args['slug'] . '][]" value="' . $option . '" ' . $checked . '/> ' . $option . '</label><br>';			
		}
		echo $html;
	}

	function add_text_setting( $slug, $name, $description ) {
		add_settings_field(
			$slug,
			$name,
			array( &$this, 'text_field_callback' ),
			'video_thumbnails',
			'general_settings_section',
			array(
				'slug'          => $slug,
				'description' => $description
			)
		);
	}

	function text_field_callback( $args ) {
		$html = '<input type="text" id="' . $args['slug'] . '" name="video_thumbnails[' . $args['slug'] . ']" value="' . $this->options[$args['slug']] . '"/>';
		$html .= '<label for="' . $args['slug'] . '"> ' . $args['description'] . '</label>';
		echo $html;
	}

	function options_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		?><div class="wrap">

			<div id="icon-options-general" class="icon32"></div><h2>Video Thumbnails Options</h2>

			<?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_settings'; ?> 
			<h2 class="nav-tab-wrapper">
				<a href="?page=video_thumbnails&tab=general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>">General</a>
				<a href="?page=video_thumbnails&tab=provider_settings" class="nav-tab <?php echo $active_tab == 'provider_settings' ? 'nav-tab-active' : ''; ?>">Providers</a>
				<a href="?page=video_thumbnails&tab=mass_actions" class="nav-tab <?php echo $active_tab == 'mass_actions' ? 'nav-tab-active' : ''; ?>">Mass Actions</a>
				<a href="?page=video_thumbnails&tab=debugging" class="nav-tab <?php echo $active_tab == 'debugging' ? 'nav-tab-active' : ''; ?>">Debugging</a>
			</h2>

			<?php
			// Main settings
			if ( $active_tab == 'general_settings' ) {
			?>
			<h3>Getting started</h3>

			<p>If your theme supports post thumbnails, just leave "Save Thumbnails to Media Library" and "Automatically Set Featured Image" enabled, then select what post types you'd like scanned for videos.</p>

			<p>For more detailed instructions, check out the page for <a href="http://wordpress.org/extend/plugins/video-thumbnails/">Video Thumbnails on the official plugin directory</a>.</p>

			<form method="post" action="options.php">  
				<?php settings_fields( 'video_thumbnails' ); ?>  
				<?php do_settings_sections( 'video_thumbnails' ); ?>            
				<?php submit_button(); ?>  
			</form>

			<?php
			// End main settings
			}
			// Provider Settings
			if ( $active_tab == 'provider_settings' ) {
			?>

			<form method="post" action="options.php">
				<input type="hidden" name="video_thumbnails[provider_options]" value="1" />
				<?php settings_fields( 'video_thumbnails' ); ?>  
				<?php do_settings_sections( 'video_thumbnails_providers' ); ?>            
				<?php submit_button(); ?>  
			</form>

			<?php
			// End provider settings
			}
			// Scan all posts
			if ( $active_tab == 'mass_actions' ) {
			?>
			<h3>Scan All Posts</h3>

			<p>Scan all of your past posts for video thumbnails. Be sure to save any settings before running the scan.</p>

			<p><input type="submit" class="button-primary" onclick="scan_video_thumbnails();" value="Scan Past Posts" /></p>

			<ol id="video-thumbnails-past">
			</ol>

			<h3>Clear all Video Thumbnails</h3>

			<p>This will clear the video thumbnail field for all posts and delete any video thumbnail attachments. Note: This only works for attachments added using version 2.0 or later.</p>

			<p><input type="submit" class="button-primary" onclick="clear_all_video_thumbnails('<?php echo wp_create_nonce( 'clear_all_video_thumbnails' ); ?>');" value="Clear Video Thumbnails" /></p>

			<div id="clear-all-video-thumbnails-result"></div>

			<?php
			// End scan all posts
			}
			// Debugging
			if ( $active_tab == 'debugging' ) {
			?>

			<p>Use these tests to help diagnose any problems. Please include results when requesting support.</p>

			<h3>Test Thumbnail Providers</h3>

			<p>This test automatically searches a sample for every type of video supported and compares it to the expected value. Sometimes tests may fail due to API rate limits.</p>

			<div id="provider-test">
				<p><input type="submit" class="button-primary" onclick="test_video_thumbnail('provider');" value="Test Providers" /></p>
			</div>

			<h3>Test Markup for Video</h3>

			<p>Copy and paste an embed code below to see if a video is detected.</p>

			<textarea id="markup-input" cols="50" rows="5"></textarea>

			<p><input type="submit" class="button-primary" onclick="test_video_thumbnail_markup_detection();" value="Scan For Thumbnail" /></p>

			<div id="markup-test-result"></div>

			<h3>Test Saving to Media Library</h3>

			<p>This test checks for issues with the process of saving a remote thumbnail to your local media library.</p>

			<div id="saving_media-test">
				<p><input type="submit" class="button-primary" onclick="test_video_thumbnail('saving_media');" value="Test Image Downloading" /></p>
			</div>

			<h3>Installation Information</h3>
			<table class="widefat">
				<thead>
					<tr>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>WordPress Version</strong></td>
						<td><?php echo get_bloginfo( 'version' ); ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>Video Thumbnails Version</strong></td>
						<td><?php echo VIDEO_THUMBNAILS_VERSION; ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>Video Thumbnails Settings Version</strong></td>
						<td><?php echo $this->options['version']; ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>PHP Version</strong></td>
						<td><?php echo PHP_VERSION; ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>Post Thumbnails</strong></td>
						<td><?php if ( current_theme_supports( 'post-thumbnails' ) ) : ?><span style="color:green">&#10004;</span> Your theme supports post thumbnails.<?php else: ?><span style="color:red">&#10006;</span> Your theme doesn't support post thumbnails, you'll need to make modifications or switch to a different theme. <a href="http://codex.wordpress.org/Post_Thumbnails">More info</a><?php endif; ?></td>
						<td></td>
					</tr>
					<tr>
						<td><strong>Providers</strong></td>
						<td>
							<?php global $video_thumbnails; ?>
								<?php $provider_names = array(); foreach ( $video_thumbnails->providers as $provider ) { $provider_names[] = $provider->service_name; }; ?>
							<strong><?php echo count( $video_thumbnails->providers ); ?></strong>: <?php echo implode( ', ', $provider_names ); ?>
						</td>
						<td></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
			</table>

			<?php
			// End debugging
			}
			?>

			<div style="width: 250px; margin: 20px 0; padding: 0 20px; background: #f5f5f5; border: 1px solid #dfdfdf; text-align: center; -webkit-border-radius: 8px; -moz-border-radius: 8px; border-radius: 8px;">
				<p>All donations are appreciated!<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----"><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></form></p>
			</div>

		</div><?php
	}

}

?>