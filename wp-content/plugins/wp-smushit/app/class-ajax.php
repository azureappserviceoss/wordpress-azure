<?php
/**
 * Smush class for storing all Ajax related functionality: Ajax class
 *
 * @package Smush\App
 * @since 2.9.0
 *
 * @copyright (c) 2018, Incsub (http://incsub.com)
 */

namespace Smush\App;

use Smush\Core\Core;
use Smush\Core\Helper;
use Smush\Core\Configs;
use Smush\Core\Modules\CDN;
use Smush\Core\Modules\Helpers\Parser;
use Smush\Core\Modules\Smush;
use Smush\Core\Settings;
use WP_Smush;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class Ajax for storing all Ajax related functionality.
 *
 * @since 2.9.0
 */
class Ajax {

	/**
	 * Settings instance.
	 *
	 * @since 3.3.0
	 * @var Settings
	 */
	private $settings;

	/**
	 * Ajax constructor.
	 */
	public function __construct() {
		$this->settings = Settings::get_instance();

		/**
		 * QUICK SETUP
		 */
		// Handle skip quick setup action.
		add_action( 'wp_ajax_skip_smush_setup', array( $this, 'skip_smush_setup' ) );
		// Ajax request for quick setup.
		add_action( 'wp_ajax_smush_setup', array( $this, 'smush_setup' ) );

		// Hide tutorials.
		add_action( 'wp_ajax_smush_hide_tutorials', array( $this, 'hide_tutorials' ) );

		/**
		 * NOTICES
		 */
		// Handle the smush pro dismiss features notice ajax.
		add_action( 'wp_ajax_dismiss_upgrade_notice', array( $this, 'dismiss_upgrade_notice' ) );
		// Handle the smush pro dismiss features notice ajax.
		add_action( 'wp_ajax_dismiss_welcome_notice', array( $this, 'dismiss_welcome_notice' ) );
		// Handle the smush pro dismiss features notice ajax.
		add_action( 'wp_ajax_dismiss_update_info', array( $this, 'dismiss_update_info' ) );
		// Handle ajax request to dismiss the s3 warning.
		add_action( 'wp_ajax_dismiss_s3support_alert', array( $this, 'dismiss_s3support_alert' ) );
		// Hide PageSpeed suggestion.
		add_action( 'wp_ajax_hide_pagespeed_suggestion', array( $this, 'hide_pagespeed_suggestion' ) );
		// Hide API message.
		add_action( 'wp_ajax_hide_api_message', array( $this, 'hide_api_message' ) );
		add_action( 'wp_ajax_smush_show_warning', array( $this, 'show_warning_ajax' ) );
		// Detect conflicting plugins.
		add_action( 'wp_ajax_dismiss_check_for_conflicts', array( $this, 'dismiss_check_for_conflicts' ) );

		/**
		 * SMUSH
		 */
		// Handle Smush Single Ajax.
		add_action( 'wp_ajax_wp_smushit_manual', array( $this, 'smush_manual' ) );
		// Handle resmush operation.
		add_action( 'wp_ajax_smush_resmush_image', array( $this, 'resmush_image' ) );
		// Scan images as per the latest settings.
		add_action( 'wp_ajax_scan_for_resmush', array( $this, 'scan_images' ) );
		// Delete ReSmush list.
		add_action( 'wp_ajax_delete_resmush_list', array( $this, 'delete_resmush_list' ), '', 2 );
		// Send smush stats.
		add_action( 'wp_ajax_get_stats', array( $this, 'get_stats' ) );

		/**
		 * BULK SMUSH
		 */
		// Ignore image from bulk Smush.
		add_action( 'wp_ajax_ignore_bulk_image', array( $this, 'ignore_bulk_image' ) );
		// Handle Smush Bulk Ajax.
		add_action( 'wp_ajax_wp_smushit_bulk', array( $this, 'process_smush_request' ) );
		// Remove from skip list.
		add_action( 'wp_ajax_remove_from_skip_list', array( $this, 'remove_from_skip_list' ) );

		/**
		 * DIRECTORY SMUSH
		 */
		// Handle Ajax request for directory smush stats (stats meta box).
		add_action( 'wp_ajax_get_dir_smush_stats', array( $this, 'get_dir_smush_stats' ) );

		/**
		 * CDN
		 */
		// Toggle CDN.
		add_action( 'wp_ajax_smush_toggle_cdn', array( $this, 'toggle_cdn' ) );
		// Update stats box and CDN status.
		add_action( 'wp_ajax_get_cdn_stats', array( new CDN( new Parser() ), 'update_stats' ) );

		/**
		 * WebP
		 */
		// Toggle WebP.
		add_action( 'wp_ajax_smush_webp_toggle', array( $this, 'webp_toggle' ) );
		// Check server configuration status for WebP.
		add_action( 'wp_ajax_smush_webp_get_status', array( $this, 'webp_get_status' ) );
		// Apply apache rules for WebP support into .htaccess file.
		add_action( 'wp_ajax_smush_webp_apply_htaccess_rules', array( $this, 'webp_apply_htaccess_rules' ) );
		// Delete all webp images for all attachments.
		add_action( 'wp_ajax_smush_webp_delete_all', array( $this, 'webp_delete_all' ) );
		// Hide the webp wizard.
		add_action( 'wp_ajax_smush_toggle_webp_wizard', array( $this, 'webp_toggle_wizard' ) );

		/**
		 * LAZY LOADING
		 */
		add_action( 'wp_ajax_smush_toggle_lazy_load', array( $this, 'smush_toggle_lazy_load' ) );
		add_action( 'wp_ajax_smush_remove_icon', array( $this, 'remove_icon' ) );

		/**
		 * Configs
		 */
		add_action( 'wp_ajax_smush_upload_config', array( $this, 'upload_config' ) );
		add_action( 'wp_ajax_smush_save_config', array( $this, 'save_config' ) );
		add_action( 'wp_ajax_smush_apply_config', array( $this, 'apply_config' ) );

		/**
		 * SETTINGS
		 */
		add_action( 'wp_ajax_recheck_api_status', array( $this, 'recheck_api_status' ) );

		/**
		 * MODALS
		 */
		// Hide the new features modal.
		add_action( 'wp_ajax_hide_new_features', array( $this, 'hide_new_features_modal' ) );
	}

	/***************************************
	 *
	 * QUICK SETUP
	 */

	/**
	 * Process ajax action for skipping Smush setup.
	 */
	public function skip_smush_setup() {
		check_ajax_referer( 'smush_quick_setup' );
		update_option( 'skip-smush-setup', true );
		wp_send_json_success();
	}

	/**
	 * Ajax action to save settings from quick setup.
	 */
	public function smush_setup() {
		check_ajax_referer( 'smush_quick_setup', '_wpnonce' );

		$quick_settings = array();
		// Get the settings from $_POST.
		if ( ! empty( $_POST['smush_settings'] ) ) {
			$quick_settings = json_decode( wp_unslash( $_POST['smush_settings'] ) );
		}

		// Check the last settings stored in db.
		$settings = $this->settings->get();

		// Available settings for free/pro version.
		$available = array( 'auto', 'lossy', 'strip_exif', 'original', 'lazy_load', 'usage' );

		foreach ( $settings as $name => $values ) {
			// Update only specified settings.
			if ( ! in_array( $name, $available, true ) ) {
				continue;
			}

			// Skip premium features if not a member.
			if ( ! in_array( $name, Settings::$basic_features, true ) && 'usage' !== $name && ! WP_Smush::is_pro() ) {
				continue;
			}

			// Update value in settings.
			$settings[ $name ] = (bool) $quick_settings->{$name};

			// If Smush originals is selected, enable backups.
			if ( 'original' === $name && $settings[ $name ] && WP_Smush::is_pro() ) {
				$settings['backup'] = true;
			}

			// If lazy load enabled - init defaults.
			if ( 'lazy_load' === $name && (bool) $quick_settings->{$name} ) {
				$this->settings->init_lazy_load_defaults();
			}
		}

		// Update the resize sizes.
		$this->settings->set_setting( 'wp-smush-settings', $settings );

		update_option( 'skip-smush-setup', true );

		wp_send_json_success();
	}

	/**
	 * Hide tutorials.
	 *
	 * @sinde 3.8.6
	 */
	public function hide_tutorials() {
		check_ajax_referer( 'wp-smush-ajax' );

		update_option( 'wp-smush-hide-tutorials', true, false );

		wp_send_json_success();
	}

	/***************************************
	 *
	 * NOTICES
	 */

	/**
	 * Store a key/value to hide the smush features on bulk page
	 *
	 * @param bool $ajax  Does this come from an AJAX request.
	 */
	public function dismiss_upgrade_notice( $ajax = true ) {
		update_site_option( 'wp-smush-hide_upgrade_notice', true );
		// No Need to send json response for other requests.
		if ( $ajax ) {
			wp_send_json_success();
		}
	}

	/**
	 * Store a key/value to hide the smush features on bulk page
	 */
	public function dismiss_welcome_notice() {
		update_site_option( 'wp-smush-hide_smush_welcome', true );
		wp_send_json_success();
	}

	/**
	 * Remove the Update info
	 *
	 * @param bool $remove_notice  Remove notice.
	 */
	public function dismiss_update_info( $remove_notice = false ) {
		WP_Smush::get_instance()->core()->mod->smush->dismiss_update_info( $remove_notice );
	}

	/**
	 * Hide S3 support alert by setting a flag.
	 */
	public function dismiss_s3support_alert() {
		// Just set a flag.
		update_site_option( 'wp-smush-hide_s3support_alert', 1 );
		wp_send_json_success();
	}

	/**
	 * Store user preference for PageSpeed suggestions.
	 */
	public function hide_pagespeed_suggestion() {
		update_site_option( 'wp-smush-hide_pagespeed_suggestion', true );
		wp_send_json_success();
	}

	/**
	 * Hide API Message
	 */
	public function hide_api_message() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$api_message = get_site_option( 'wp-smush-api_message', array() );
		if ( ! empty( $api_message ) && is_array( $api_message ) ) {
			$api_message[ key( $api_message ) ]['status'] = 'hide';
			update_site_option( 'wp-smush-api_message', $api_message );
		}

		wp_send_json_success();
	}

	/**
	 * Send JSON response whether to show or not the warning
	 */
	public function show_warning_ajax() {
		$show = WP_Smush::get_instance()->core()->mod->smush->show_warning();
		wp_send_json( (int) $show );
	}

	/**
	 * Dismiss the plugin conflicts notice.
	 *
	 * @since 3.6.0
	 */
	public function dismiss_check_for_conflicts() {
		update_option( 'wp-smush-hide-conflict-notice', true );
		wp_send_json_success();
	}

	/***************************************
	 *
	 * SMUSH
	 */

	/**
	 * Handle the Ajax request for smushing single image
	 *
	 * @uses smush_single()
	 */
	public function smush_manual() {
		// Turn off errors for ajax result.
		@error_reporting( 0 );

		if ( ! current_user_can( 'upload_files' ) ) {
			wp_send_json_error(
				array(
					'error_msg' => __( "You don't have permission to work with uploaded files.", 'wp-smushit' ),
				)
			);
		}

		if ( ! isset( $_GET['attachment_id'] ) ) {
			wp_send_json_error(
				array(
					'error_msg' => __( 'No attachment ID was provided.', 'wp-smushit' ),
				)
			);
		}

		$attachment_id = (int) $_GET['attachment_id'];

		/**
		 * Filter: wp_smush_image.
		 *
		 * Whether to smush the given attachment ID or not.
		 *
		 * @param bool $status         Smush all attachments by default.
		 * @param int  $attachment_id  Attachment ID.
		 */
		if ( ! apply_filters( 'wp_smush_image', true, $attachment_id ) ) {
			$error = Helper::filter_error( esc_html__( 'Attachment Skipped - Check `wp_smush_image` filter.', 'wp-smushit' ), $attachment_id );
			wp_send_json_error(
				array(
					'error_msg'    => sprintf( '<p class="wp-smush-error-message">%s</p>', $error ),
					'show_warning' => (int) WP_Smush::get_instance()->core()->mod->smush->show_warning(),
				)
			);
		}

		// Pass on the attachment id to smush single function.
		WP_Smush::get_instance()->core()->mod->smush->smush_single( $attachment_id );
	}

	/**
	 * Resmush the image
	 *
	 * @uses smush_single()
	 */
	public function resmush_image() {
		// Check empty fields.
		if ( empty( $_POST['attachment_id'] ) || empty( $_POST['_nonce'] ) ) {
			wp_send_json_error(
				array(
					'error_msg' => '<div class="wp-smush-error">' . esc_html__( 'Image not smushed, fields empty.', 'wp-smushit' ) . '</div>',
				)
			);
		}

		// Check nonce.
		if ( ! wp_verify_nonce( $_POST['_nonce'], 'wp-smush-resmush-' . $_POST['attachment_id'] ) ) {
			wp_send_json_error(
				array(
					'error_msg' => '<div class="wp-smush-error">' . esc_html__( "Image couldn't be smushed as the nonce verification failed, try reloading the page.", 'wp-smushit' ) . '</div>',
				)
			);
		}

		$image_id = (int) $_POST['attachment_id'];

		WP_Smush::get_instance()->core()->mod->smush->smush_single( $image_id );
	}

	/**
	 * Scans all the smushed attachments to check if they need to be resmushed as per the
	 * current settings, as user might have changed one of the configurations "Lossy", "Keep Original", "Preserve Exif"
	 *
	 * @todo: Needs some refactoring big time
	 */
	public function scan_images() {
		check_ajax_referer( 'save_wp_smush_options', 'wp_smush_options_nonce' );

		$resmush_list = array();

		// Scanning for NextGen or Media Library.
		$type = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';

		$core = WP_Smush::get_instance()->core();

		// Save settings only if networkwide settings are disabled.
		if ( Settings::can_access() && ( ! isset( $_REQUEST['process_settings'] ) || 'false' !== $_REQUEST['process_settings'] ) ) {
			// Fetch the new settings.
			$this->settings->init();
		}

		// If there aren't any images in the library, return the notice.
		if ( 0 === count( $core->get_media_attachments() ) && 'nextgen' !== $type ) {
			wp_send_json_success(
				array(
					'notice'      => esc_html__( 'We haven’t found any images in your media library yet so there’s no smushing to be done! Once you upload images, reload this page and start playing!', 'wp-smushit' ),
					'super_smush' => WP_Smush::is_pro() && $this->settings->get( 'lossy' ),
				)
			);
		}

		/**
		 * Logic: If none of the required settings is on, don't need to resmush any of the images
		 * We need at least one of these settings to be on, to check if any of the image needs resmush.
		 */

		// Initialize Media Library Stats.
		if ( 'nextgen' !== $type && empty( $core->remaining_count ) ) {
			// Force update to clear caches.
			$core->setup_global_stats( true );
		}

		// Initialize NextGen Stats.
		if ( 'nextgen' === $type && is_object( $core->nextgen->ng_admin ) && empty( $core->nextgen->ng_admin->remaining_count ) ) {
			$core->nextgen->ng_admin->setup_image_counts();
		}

		$key = 'nextgen' === $type ? 'wp-smush-nextgen-resmush-list' : 'wp-smush-resmush-list';

		$remaining_count = 'nextgen' === $type ? $core->nextgen->ng_admin->remaining_count : $core->remaining_count;

		if (
			0 === (int) $remaining_count &&
			( ! WP_Smush::is_pro() || ! $this->settings->get( 'lossy' ) ) &&
			( ! $this->settings->get( 'original' ) || ! WP_Smush::is_pro() ) &&
			( ! $this->settings->get( 'webp_mod' ) || ! WP_Smush::is_pro() ) &&
			! $this->settings->get( 'strip_exif' )
		) {
			delete_option( $key );
			// Default Notice, to be displayed at the top of page. Show a message, at the top.
			wp_send_json_success(
				array(
					'notice' => esc_html__( 'Yay! All images are optimized as per your current settings.', 'wp-smushit' ),
				)
			);
		}

		// Set to empty by default.
		$content = '';

		// Get Smushed Attachments.
		if ( 'nextgen' !== $type ) {
			// Get list of Smushed images.
			$attachments = ! empty( $core->smushed_attachments ) ? $core->smushed_attachments : $core->get_smushed_attachments();
		} else {
			// Get smushed attachments list from nextgen class, We get the meta as well.
			$attachments = $core->nextgen->ng_stats->get_ngg_images();
		}

		$stats = array(
			'size_before'        => 0,
			'size_after'         => 0,
			'savings_resize'     => 0,
			'savings_conversion' => 0,
		);

		$image_count = $super_smushed_count = $smushed_count = $resized_count = 0;
		// Check if any of the smushed image needs to be resmushed.
		if ( ! empty( $attachments ) && is_array( $attachments ) ) {
			// Initialize resize class.
			$core->mod->resize->initialize();

			foreach ( $attachments as $attachment_k => $attachment ) {
				// Skip if already in resmush list.
				if ( ! empty( $core->resmush_ids ) && in_array( $attachment, $core->resmush_ids ) || ( ! empty( $core->skipped_attachments ) && in_array( $attachment, $core->skipped_attachments ) ) ) {
					continue;
				}
				$should_resmush = false;

				// For NextGen we get the metadata in the attachment data itself.
				if ( is_array( $attachment ) && ! empty( $attachment['wp_smush'] ) ) {
					$smush_data = $attachment['wp_smush'];
				} else {
					// Check the current settings, and smush data for the image.
					$smush_data = get_post_meta( $attachment, Smush::$smushed_meta_key, true );
				}

				// If the image is already smushed.
				if ( is_array( $smush_data ) && ! empty( $smush_data['stats'] ) ) {
					// If we need to optmise losslessly, add to resmush list.
					$smush_lossy = WP_Smush::is_pro() && $this->settings->get( 'lossy' ) && ! $smush_data['stats']['lossy'];

					// If we need to strip exif, put it in resmush list.
					$strip_exif = $this->settings->get( 'strip_exif' ) && isset( $smush_data['stats']['keep_exif'] ) && ( 1 == $smush_data['stats']['keep_exif'] );

					// If Original image needs to be smushed.
					$smush_original = $this->settings->get( 'original' ) && WP_Smush::is_pro() && empty( $smush_data['sizes']['full'] );

					if ( $smush_lossy || $strip_exif || $smush_original ) {
						$should_resmush = true;
					}

					// Check if new sizes have been selected.
					$image_sizes = $this->settings->get_setting( 'wp-smush-image_sizes' );

					// Empty means we need to smush all images. So get all sizes of current site.
					if ( empty( $image_sizes ) ) {
						$image_sizes = array_keys( WP_Smush::get_instance()->core()->image_dimensions() );
					}

					/**
					 * This is a too complicated way to check if the attachment needs a resmush.
					 * Basically, smaller images might not have all the image sizes. And if, let's say, image does not
					 * have a large attachment size, but user selects large to be compressed - do not trigger the
					 * $show_resmush action for such an image.
					 *
					 * 1. Check if the selected image size is not already compressed.
					 * 2. Check if the image has the defined size so it can be compressed.
					 *
					 * @since 3.2.1
					 */
					if ( is_array( $image_sizes ) && count( $image_sizes ) > count( $smush_data['sizes'] ) && ! has_filter( 'wp_image_editors', 'photon_subsizes_override_image_editors' ) ) {
						// Move this inside an if statement.
						$attachment_data = wp_get_attachment_metadata( $attachment );
						if ( isset( $attachment_data['sizes'] ) && count( $attachment_data['sizes'] ) !== count( $smush_data['sizes'] ) ) {
							foreach ( $image_sizes as $image_size ) {
								// Already compressed.
								if ( isset( $smush_data['sizes'][ $image_size ] ) ) {
									continue;
								}

								// If image has the size that can be compressed.
								if ( isset( $attachment_data['sizes'][ $image_size ] ) ) {
									$should_resmush = true;
									break;
								}
							}
						}
					}

					// If Image needs to be resized.
					if ( ! $should_resmush ) {
						$should_resmush = $core->mod->resize->should_resize( $attachment );
					}

					// If image can be converted.
					if ( ! $should_resmush ) {
						$should_resmush = $core->mod->png2jpg->can_be_converted( $attachment );
					}

					// If image needs to be converted to webp.
					if ( ! $should_resmush ) {
						$should_resmush = WP_Smush::get_instance()->core()->mod->webp->should_be_converted( $attachment );
					}

					// If the image needs to be resmushed add it to the list.
					if ( $should_resmush ) {
						$resmush_list[] = 'nextgen' === $type ? $attachment_k : $attachment;
					}

					/**
					 * Calculate stats during re-check images action.
					 */
					if ( 'nextgen' !== $type ) {
						$resize_savings     = get_post_meta( $attachment, 'wp-smush-resize_savings', true );
						$conversion_savings = Helper::get_pngjpg_savings( $attachment );

						// Increase the smushed count.
						$smushed_count ++;
						// Get the resized image count.
						if ( ! empty( $resize_savings ) ) {
							$resized_count ++;
						}

						// Get the image count.
						$image_count += ( ! empty( $smush_data['sizes'] ) && is_array( $smush_data['sizes'] ) ) ? count( $smush_data['sizes'] ) : 0;

						// If the image is in resmush list, and it was super smushed earlier.
						$super_smushed_count += ( $smush_data['stats']['lossy'] ) ? 1 : 0;

						// Add to the stats.
						$stats['size_before'] += ! empty( $smush_data['stats'] ) ? $smush_data['stats']['size_before'] : 0;
						$stats['size_before'] += ! empty( $resize_savings['size_before'] ) ? $resize_savings['size_before'] : 0;
						$stats['size_before'] += ! empty( $conversion_savings['size_before'] ) ? $conversion_savings['size_before'] : 0;

						$stats['size_after'] += ! empty( $smush_data['stats'] ) ? $smush_data['stats']['size_after'] : 0;
						$stats['size_after'] += ! empty( $resize_savings['size_after'] ) ? $resize_savings['size_after'] : 0;
						$stats['size_after'] += ! empty( $conversion_savings['size_after'] ) ? $conversion_savings['size_after'] : 0;

						$stats['savings_resize']     += ! empty( $resize_savings ) && isset( $resize_savings['bytes'] ) ? $resize_savings['bytes'] : 0;
						$stats['savings_conversion'] += ! empty( $conversion_savings ) && isset( $conversion_savings['bytes'] ) ? $conversion_savings['bytes'] : 0;
					}
				}
			}// End of Foreach Loop

			// Store the resmush list in Options table.
			update_option( $key, $resmush_list, false );
		}

		// Delete resmush list if empty.
		if ( empty( $resmush_list ) ) {
			delete_option( $key );
		}

		$unsmushed_ids = array();

		// Get updated stats for NextGen.
		if ( 'nextgen' === $type ) {
			// Reinitialize NextGen stats.
			$core->nextgen->ng_admin->setup_image_counts();
			// Image count, Smushed Count, Super-smushed Count, Savings.
			$stats               = $core->nextgen->ng_stats->get_smush_stats();
			$image_count         = $core->nextgen->ng_admin->image_count;
			$smushed_count       = $core->nextgen->ng_admin->smushed_count;
			$super_smushed_count = $core->nextgen->ng_admin->super_smushed;

			$unsmushed_count = $core->nextgen->ng_admin->remaining_count;

			if ( 0 < $unsmushed_count ) {
				$raw_unsmushed = $core->nextgen->ng_stats->get_ngg_images( 'unsmushed' );
				if ( ! empty( $raw_unsmushed ) && is_array( $raw_unsmushed ) ) {
					$unsmushed_ids = array_keys( $raw_unsmushed );
				}
			}
		} else {
			$unsmushed_count = $core->remaining_count;

			if ( 0 < $unsmushed_count ) {
				$unsmushed_ids = array_values( $core->get_unsmushed_attachments() );
			}
		}

		$resmush_count = count( $resmush_list );
		$count         = $unsmushed_count + $resmush_count;

		// If a user manually runs smush check.
		// Return the Remsmush list and UI to be appended to Bulk Smush UI.
		$return_ui = isset( $_REQUEST['get_ui'] ) && 'true' == $_REQUEST['get_ui'] ? true : false;

		if ( $return_ui ) {
			if ( 'nextgen' !== $type ) {
				// Set the variables.
				$core->resmush_ids = $resmush_list;
			} else {
				// To avoid the php warning.
				$core->nextgen->ng_admin->resmush_ids = $resmush_list;
			}

			if ( $count ) {
				ob_start();
				WP_Smush::get_instance()->admin()->print_pending_bulk_smush_content( $count, $resmush_count, $unsmushed_count );
				$content = ob_get_clean();
			}
		}

		// Directory Smush Stats
		// Include directory smush stats if not requested for NextGen.
		if ( 'nextgen' !== $type ) {
			// Append the directory smush stats.
			$dir_smush_stats = get_option( 'dir_smush_stats' );
			if ( ! empty( $dir_smush_stats ) && is_array( $dir_smush_stats ) ) {
				if ( ! empty( $dir_smush_stats['dir_smush'] ) && ! empty( $dir_smush_stats['optimised'] ) ) {
					$dir_smush_stats = $dir_smush_stats['dir_smush'];
					$image_count    += $dir_smush_stats['optimised'];
				}

				// Add directory smush stats if not empty.
				if ( ! empty( $dir_smush_stats['image_size'] ) && ! empty( $dir_smush_stats['orig_size'] ) ) {
					$stats['size_before'] += $dir_smush_stats['orig_size'];
					$stats['size_after']  += $dir_smush_stats['image_size'];
				}
			}
		}

		$return = array(
			'resmush_ids'        => $resmush_list,
			'unsmushed'          => $unsmushed_ids,
			'count_image'        => $image_count,
			'count_supersmushed' => $super_smushed_count,
			'count_smushed'      => $smushed_count,
			'count_resize'       => $resized_count,
			'size_before'        => ! empty( $stats['size_before'] ) ? $stats['size_before'] : 0,
			'size_after'         => ! empty( $stats['size_after'] ) ? $stats['size_after'] : 0,
			'savings_resize'     => ! empty( $stats['savings_resize'] ) ? $stats['savings_resize'] : 0,
			'savings_conversion' => ! empty( $stats['savings_conversion'] ) ? $stats['savings_conversion'] : 0,
		);

		if ( ! empty( $content ) ) {
			$return['content'] = $content;
		}

		// Include the count.
		if ( ! empty( $count ) && $count ) {
			$return['count'] = $count;
		}

		if ( ! empty( $count ) ) {
			$return['noticeType'] = 'warning';
			$return['notice']     = sprintf(
				/* translators: %1$d - number of images, %2$s - opening a tag, %3$s - closing a tag */
				esc_html__( 'Image check complete, you have %1$d images that need smushing. %2$sBulk smush now!%3$s', 'wp-smushit' ),
				$count,
				'<a href="#" class="wp-smush-trigger-bulk" data-type="' . $type . '">',
				'</a>'
			);
		}
		$return['super_smush'] = WP_Smush::is_pro() && $this->settings->get( 'lossy' );
		if ( WP_Smush::is_pro() && $this->settings->get( 'lossy' ) && 'nextgen' === $type ) {
			$ss_count                    = $core->nextgen->ng_stats->nextgen_super_smushed_count( $core->nextgen->ng_stats->get_ngg_images( 'smushed' ) );
			$return['super_smush_stats'] = sprintf( '<strong><span class="smushed-count">%d</span>/%d</strong>', $ss_count, $core->nextgen->ng_admin->total_count );
		}

		wp_send_json_success( $return );
	}

	/**
	 * Delete the resmush list for Nextgen or the Media Library
	 *
	 * Return Stats in ajax response
	 */
	public function delete_resmush_list() {
		$stats = array();

		$key = ! empty( $_POST['type'] ) && 'nextgen' === $_POST['type'] ? 'wp-smush-nextgen-resmush-list' : 'wp-smush-resmush-list';

		// For media Library.
		if ( 'nextgen' !== $_POST['type'] ) {
			$resmush_list = get_option( $key );
			if ( ! empty( $resmush_list ) && is_array( $resmush_list ) ) {
				$stats = WP_Smush::get_instance()->core()->get_stats_for_attachments( $resmush_list );
			}
		} else {
			// For NextGen. Get the stats (get the re-Smush IDs).
			$resmush_ids = get_option( 'wp-smush-nextgen-resmush-list', array() );

			$stats = WP_Smush::get_instance()->core()->nextgen->ng_stats->get_stats_for_ids( $resmush_ids );

			$stats['count_images'] = WP_Smush::get_instance()->core()->nextgen->ng_admin->get_image_count( $resmush_ids, false );
		}

		// Delete the resmush list.
		delete_option( $key );
		wp_send_json_success( array( 'stats' => $stats ) );
	}

	/**
	 * Return Latest stats.
	 */
	public function get_stats() {
		$core = WP_Smush::get_instance()->core();

		if ( empty( $core->stats ) ) {
			$core->setup_global_stats( true );
		}

		$stats = array(
			'count_images'       => ! empty( $core->stats ) && isset( $core->stats['total_images'] ) ? $core->stats['total_images'] : 0,
			'count_resize'       => ! empty( $core->stats ) && isset( $core->stats['resize_count'] ) ? $core->stats['resize_count'] : 0,
			'count_smushed'      => $core->smushed_count,
			'count_supersmushed' => $core->super_smushed,
			'count_total'        => $core->total_count,
			'savings_bytes'      => ! empty( $core->stats ) && isset( $core->stats['bytes'] ) ? $core->stats['bytes'] : 0,
			'savings_conversion' => ! empty( $core->stats ) && isset( $core->stats['conversion_savings'] ) ? $core->stats['conversion_savings'] : 0,
			'savings_resize'     => ! empty( $core->stats ) && isset( $core->stats['resize_savings'] ) ? $core->stats['resize_savings'] : 0,
			'size_before'        => ! empty( $core->stats ) && isset( $core->stats['size_before'] ) ? $core->stats['size_before'] : 0,
			'size_after'         => ! empty( $core->stats ) && isset( $core->stats['size_after'] ) ? $core->stats['size_after'] : 0,
		);

		wp_send_json_success( $stats );
	}

	/***************************************
	 *
	 * BULK SMUSH
	 */

	/**
	 * Ignore image from bulk Smush.
	 *
	 * @since 1.9.0
	 */
	public function ignore_bulk_image() {
		if ( ! isset( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		$id = absint( $_POST['id'] );
		update_post_meta( $id, 'wp-smush-ignore-bulk', 'true' );

		wp_send_json_success(
			array(
				'links' => WP_Smush::get_instance()->library()->get_optimization_links( $id ),
			)
		);
	}

	/**
	 * Bulk Smushing Handler.
	 *
	 * Processes the Smush request and sends back the next id for smushing.
	 */
	public function process_smush_request() {
		// Turn off errors for ajax result.
		@error_reporting( 0 );

		$smush = WP_Smush::get_instance()->core()->mod->smush;

		if ( empty( $_REQUEST['attachment_id'] ) ) {
			wp_send_json_error(
				array(
					'error'         => 'missing_id',
					'error_message' => Helper::filter_error( esc_html__( 'No attachment ID was received.', 'wp-smushit' ) ),
					'file_name'     => 'undefined',
					'show_warning'  => (int) $smush->show_warning(),
				)
			);
		}

		// If the bulk smush needs to be stopped.
		if ( ! WP_Smush::is_pro() && ! Core::check_bulk_limit() ) {
			wp_send_json_error(
				array(
					'error'    => 'limit_exceeded',
					'continue' => false,
				)
			);
		}

		$attachment_id = (int) $_REQUEST['attachment_id'];
		$original_meta = wp_get_attachment_metadata( $attachment_id, true );

		/**
		 * This is often not set when images are imported to the database, without properly adding the meta values.
		 * Causes PHP Warning: Illegal string offset 'file' message.
		 */
		if ( ! isset( $original_meta['file'] ) ) {
			wp_send_json_error(
				array(
					'error'         => 'no_file_meta',
					'error_message' => Helper::filter_error( esc_html__( 'No file data found in image meta.', 'wp-smushit' ) ),
					'file_name'     => sprintf(
						/* translators: %d - attachment ID */
						esc_html__( 'undefined (attachment ID: %d)', 'wp-smushit' ),
						(int) $attachment_id
					),
				)
			);
		}

		// Try to get the file name from path.
		$file_name = explode( '/', $original_meta['file'] );

		if ( is_array( $file_name ) ) {
			$file_name = array_pop( $file_name );
		} else {
			$file_name = $original_meta['file'];
		}

		/**
		 * Filter: wp_smush_image
		 *
		 * Whether to smush the given attachment id or not
		 *
		 * @param bool $skip           Whether to Smush image or not.
		 * @param int  $attachment_id  Attachment ID of the image being processed.
		 */
		if ( ! apply_filters( 'wp_smush_image', true, $attachment_id ) ) {
			wp_send_json_error(
				array(
					'error'         => 'skipped',
					'error_message' => Helper::filter_error( esc_html__( 'Skipped with wp_smush_image filter', 'wp-smushit' ) ),
					'show_warning'  => (int) $smush->show_warning(),
					'file_name'     => Helper::get_image_media_link( $attachment_id, $file_name ),
					'thumbnail'     => wp_get_attachment_image( $attachment_id ),
				)
			);
		}

		// Allow downloading the file from S3 all throughout the process.
		do_action( 'smush_s3_integration_fetch_file' );

		// Get the file path for backup.
		$attachment_file_path = get_attached_file( $attachment_id );

		Helper::check_animated_status( $attachment_file_path, $attachment_id );

		WP_Smush::get_instance()->core()->mod->backup->create_backup( $attachment_file_path, $attachment_id );

		// Proceed only if transient is not set for the given attachment id.
		if ( ! get_option( 'smush-in-progress-' . $attachment_id, false ) ) {
			// Set a transient to avoid multiple request.
			update_option( 'smush-in-progress-' . $attachment_id, true );

			/**
			 * Resize the dimensions of the image.
			 *
			 * Filter whether the existing image should be resized or not
			 *
			 * @since 2.3
			 *
			 * @param bool $should_resize Set to True by default.
			 * @param int  $attachment_id Image Attachment ID.
			 */
			if ( apply_filters( 'wp_smush_resize_media_image', true, $attachment_id ) ) {
				$updated_meta  = $smush->resize_image( $attachment_id, $original_meta );
				$original_meta = ! empty( $updated_meta ) ? $updated_meta : $original_meta;
			}

			$original_meta = WP_Smush::get_instance()->core()->mod->png2jpg->png_to_jpg( $attachment_id, $original_meta );

			WP_Smush::get_instance()->core()->mod->webp->convert_to_webp( $attachment_id, $original_meta );

			$smush_response = $smush->resize_from_meta_data( $attachment_id, $original_meta );
			wp_update_attachment_metadata( $attachment_id, $original_meta );
		}

		// Delete transient.
		delete_option( 'smush-in-progress-' . $attachment_id );

		$smush_data         = get_post_meta( $attachment_id, Smush::$smushed_meta_key, true );
		$resize_savings     = get_post_meta( $attachment_id, 'wp-smush-resize_savings', true );
		$conversion_savings = Helper::get_pngjpg_savings( $attachment_id );

		$stats = array(
			'count'              => ! empty( $smush_data['sizes'] ) ? count( $smush_data['sizes'] ) : 0,
			'size_before'        => ! empty( $smush_data['stats'] ) ? $smush_data['stats']['size_before'] : 0,
			'size_after'         => ! empty( $smush_data['stats'] ) ? $smush_data['stats']['size_after'] : 0,
			'savings_resize'     => $resize_savings > 0 ? $resize_savings : 0,
			'savings_conversion' => $conversion_savings['bytes'] > 0 ? $conversion_savings : 0,
			'is_lossy'           => ! empty( $smush_data ['stats'] ) ? $smush_data['stats']['lossy'] : false,
		);

		if ( isset( $smush_response ) && is_wp_error( $smush_response ) ) {
			$error_message = $smush_response->get_error_message();

			// Check for timeout error and suggest filtering timeout.
			if ( strpos( $error_message, 'timed out' ) ) {
				$error         = 'timeout';
				$error_message = esc_html__( "Timeout error. You can increase the request timeout to make sure Smush has enough time to process larger files. `define('WP_SMUSH_TIMEOUT', 150);`", 'wp-smushit' );
			}

			$error = isset( $error ) ? $error : 'other';

			if ( ! empty( $error_message ) ) {
				// Used internally to modify the error message.
				$error_message = Helper::filter_error( $error_message, $attachment_id );
			}

			wp_send_json_error(
				array(
					'stats'         => $stats,
					'error'         => $error,
					'error_message' => $error_message,
					'show_warning'  => (int) $smush->show_warning(),
					'error_class'   => isset( $error_class ) ? $error_class : '',
					'file_name'     => Helper::get_image_media_link( $attachment_id, $file_name ),
				)
			);
		}

		// Check if a resmush request, update the resmush list.
		if ( ! empty( $_REQUEST['is_bulk_resmush'] ) && 'false' !== $_REQUEST['is_bulk_resmush'] && $_REQUEST['is_bulk_resmush'] ) {
			$smush->update_resmush_list( $attachment_id );
		} else {
			Core::add_to_smushed_list( $attachment_id );
		}

		// Runs after a image is successfully smushed.
		do_action( 'image_smushed', $attachment_id, $stats );

		// Update the bulk Limit count.
		Core::update_smush_count();

		// Send ajax response.
		wp_send_json_success(
			array(
				'stats'        => $stats,
				'show_warning' => (int) $smush->show_warning(),
			)
		);
	}

	/**
	 * Remove the image meta that is making the image skip bulk smush.
	 *
	 * @since 3.0
	 */
	public function remove_from_skip_list() {
		wp_verify_nonce( 'wp-smush-remove-skipped' );

		if ( ! isset( $_POST['id'] ) ) {
			wp_send_json_error();
		}

		delete_post_meta( absint( $_POST['id'] ), 'wp-smush-ignore-bulk' );

		wp_send_json_success(
			array(
				'links' => WP_Smush::get_instance()->library()->get_optimization_links( absint( $_POST['id'] ) ),
			)
		);
	}

	/***************************************
	 *
	 * DIRECTORY SMUSH
	 */

	/**
	 * Returns Directory Smush stats and Cumulative stats
	 */
	public function get_dir_smush_stats() {
		$result = array();

		// Store the Total/Smushed count.
		$stats = WP_Smush::get_instance()->core()->mod->dir->total_stats();

		$result['dir_smush'] = $stats;

		// Cumulative Stats.
		$result['combined_stats'] = WP_Smush::get_instance()->core()->mod->dir->combine_stats( $stats );

		// Store the stats in options table.
		update_option( 'dir_smush_stats', $result, false );

		// Send ajax response.
		wp_send_json_success( $result );
	}

	/***************************************
	 *
	 * CDN
	 *
	 * @since 3.0
	 */

	/**
	 * Toggle CDN.
	 *
	 * Handles "Get Started" button press on the disabled CDN meta box.
	 * Handles "Deactivate" button press on the CDN meta box.
	 * Refreshes page on success.
	 *
	 * @since 3.0
	 */
	public function toggle_cdn() {
		check_ajax_referer( 'save_wp_smush_options' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'User can not modify options', 'wp-smushit' ),
				),
				403
			);
		}

		$enable   = filter_input( INPUT_POST, 'param', FILTER_VALIDATE_BOOLEAN );
		$response = WP_Smush::get_instance()->core()->mod->cdn->toggle_cdn( $enable );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error(
				array( 'message' => $response->get_error_message() )
			);
		}

		wp_send_json_success();
	}

	/***************************************
	 *
	 * WebP
	 *
	 * @since 3.8.0
	 */

	/**
	 * Toggle WebP.
	 *
	 * Handles "Activate" button press on the disabled WebP meta box.
	 * Handles "Deactivate" button press on the WebP meta box.
	 * Refreshes page on success.
	 *
	 * @since 3.8.0
	 */
	public function webp_toggle() {
		check_ajax_referer( 'save_wp_smush_options' );

		$capability = is_multisite() ? 'manage_network' : 'manage_options';
		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error(
				array(
					'message' => __( "You don't have permission to do this.", 'wp-smushit' ),
				),
				403
			);
		}

		$param       = isset( $_POST['param'] ) ? sanitize_text_field( wp_unslash( $_POST['param'] ) ) : '';
		$enable_webp = 'true' === $param;

		WP_Smush::get_instance()->core()->mod->webp->toggle_webp( $enable_webp );

		wp_send_json_success();
	}

	/**
	 * Check server configuration status and other info for WebP.
	 *
	 * Handles "Re-Check Status" button press on the WebP meta box.
	 *
	 * @since 3.8.0
	 */
	public function webp_get_status() {
		if ( ! check_ajax_referer( 'wp-smush-webp-nonce', false, false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( "Either the nonce expired or you can't modify options. Please reload the page and try again.", 'wp-smushit' ) );
		}

		$is_configured = WP_Smush::get_instance()->core()->mod->webp->get_is_configured_with_error_message( true );

		if ( true === $is_configured ) {
			wp_send_json_success();
		}

		// The messages are set in React with dangerouslySetInnerHTML so they must be html-escaped.
		wp_send_json_error( esc_html( $is_configured ) );
	}

	/**
	 * Write apache rules for WebP support from .htaccess file.
	 * Handles the "Apply Rules" button press on the WebP meta box.
	 *
	 * @since 3.8.0
	 */
	public function webp_apply_htaccess_rules() {
		if ( ! check_ajax_referer( 'wp-smush-webp-nonce', false, false ) || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( "Either the nonce expired or you can't modify options. Please reload the page and try again." );
		}

		$was_written = WP_Smush::get_instance()->core()->mod->webp->save_htaccess();

		if ( true === $was_written ) {
			wp_send_json_success();
		}

		wp_send_json_error( wp_kses_post( $was_written ) );
	}

	/**
	 * Delete all webp images.
	 * Triggered by the "Delete WebP images" button in the webp tab.
	 *
	 * @since 3.8.0
	 */
	public function webp_delete_all() {
		check_ajax_referer( 'save_wp_smush_options' );

		$capability = is_multisite() ? 'manage_network' : 'manage_options';

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'This user can not delete all WebP images.', 'wp-smushit' ),
				),
				403
			);
		}

		WP_Smush::get_instance()->core()->mod->webp->delete_all();

		wp_send_json_success();
	}

	/**
	 * Toggles the webp wizard.
	 *
	 * @since 3.8.8
	 */
	public function webp_toggle_wizard() {
		if ( check_ajax_referer( 'wp-smush-webp-nonce', false, false ) && current_user_can( 'manage_options' ) ) {
			$is_hidden = get_site_option( 'wp-smush-webp_hide_wizard' );
			update_site_option( 'wp-smush-webp_hide_wizard', ! $is_hidden );
			wp_send_json_success();
		}
	}

	/***************************************
	 *
	 * LAZY LOADING
	 *
	 * @since 3.2.0
	 */

	/**
	 * Toggle lazy loading module.
	 *
	 * Handles "Activate" button press on the disabled lazy loading meta box.
	 * Handles "Deactivate" button press on the lazy loading meta box.
	 * Refreshes page on success.
	 *
	 * @since 3.2.0
	 */
	public function smush_toggle_lazy_load() {
		check_ajax_referer( 'save_wp_smush_options' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'User can not modify options', 'wp-smushit' ),
				),
				403
			);
		}

		$param = isset( $_POST['param'] ) ? sanitize_text_field( wp_unslash( $_POST['param'] ) ) : false;

		if ( 'true' === $param ) {
			$settings = $this->settings->get_setting( 'wp-smush-lazy_load' );

			// No settings, during init - set defaults.
			if ( ! $settings ) {
				$this->settings->init_lazy_load_defaults();
			}
		}

		$this->settings->set( 'lazy_load', 'true' === $param );

		wp_send_json_success();
	}

	/**
	 * Remove spinner/placeholder icon from lazy-loading.
	 *
	 * @since 3.2.2
	 */
	public function remove_icon() {
		check_ajax_referer( 'save_wp_smush_options' );

		$id   = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT );
		$type = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING );
		if ( $id && $type ) {
			$settings = $this->settings->get_setting( 'wp-smush-lazy_load' );
			if ( false !== ( $key = array_search( $id, $settings['animation'][ $type ]['custom'] ) ) ) {
				unset( $settings['animation'][ $type ]['custom'][ $key ] );
				$this->settings->set_setting( 'wp-smush-lazy_load', $settings );
			}
		}

		wp_send_json_success();
	}

	/***************************************
	 *
	 * CONFIGS
	 *
	 * @since 3.8.5
	 */

	/**
	 * Handles the upload of a config file.
	 *
	 * @since 3.8.5
	 */
	public function upload_config() {
		check_ajax_referer( 'smush_handle_config' );

		$capability = is_multisite() ? 'manage_network' : 'manage_options';
		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( null, 403 );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$file = isset( $_FILES['file'] ) ? wp_unslash( $_FILES['file'] ) : false;

		$configs_handler = new Configs();
		$new_config      = $configs_handler->save_uploaded_config( $file );

		if ( ! is_wp_error( $new_config ) ) {
			wp_send_json_success( $new_config );
		}

		wp_send_json_error(
			array( 'error_msg' => $new_config->get_error_message() )
		);
	}
	/**
	 * Handles the upload of a config file.
	 *
	 * @since 3.8.5
	 */
	public function save_config() {
		check_ajax_referer( 'smush_handle_config' );

		$capability = is_multisite() ? 'manage_network' : 'manage_options';
		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( null, 403 );
		}

		$configs_handler = new Configs();
		wp_send_json_success( $configs_handler->get_config_from_current() );
	}

	/**
	 * Applies the given config.
	 *
	 * @since 3.8.5
	 */
	public function apply_config() {
		check_ajax_referer( 'smush_handle_config' );

		$capability = is_multisite() ? 'manage_network' : 'manage_options';
		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( null, 403 );
		}

		$id = filter_input( INPUT_POST, 'id', FILTER_SANITIZE_STRING );
		if ( ! $id ) {
			// Abort if no config ID was given.
			wp_send_json_error(
				array( 'error_msg' => esc_html__( 'Missing config ID', 'wp-smushit' ) )
			);
		}

		$configs_handler = new Configs();
		$response        = $configs_handler->apply_config_by_id( $id );

		if ( ! is_wp_error( $response ) ) {
			wp_send_json_success();
		}

		wp_send_json_error(
			array( 'error_msg' => esc_html( $response->get_error_message() ) )
		);
	}

	/***************************************
	 *
	 * SETTINGS
	 *
	 * @since 3.2.0.2
	 */

	/**
	 * Re-check API status.
	 *
	 * @since 3.2.0.2
	 */
	public function recheck_api_status() {
		WP_Smush::get_instance()->validate_install( true );
		wp_send_json_success();
	}

	/***************************************
	 *
	 * MODALS
	 *
	 * @since 3.7.0
	 */

	/**
	 * Hide the new features modal
	 *
	 * @since 3.7.0
	 */
	public function hide_new_features_modal() {
		delete_site_option( 'wp-smush-show_upgrade_modal' );
		wp_send_json_success();
	}

}
