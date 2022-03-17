<?php
/**
 * Remove plugin settings data.
 *
 * @since 1.7
 * @package Smush
 */

use Smush\Core\Settings;

// If uninstall not called from WordPress exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

if ( ! class_exists( '\\Smush\\Core\\Settings' ) ) {
	/* @noinspection PhpIncludeInspection */
	include_once plugin_dir_path( __FILE__ ) . '/core/class-settings.php';
}
$keep_data = Settings::get_instance()->get( 'keep_data' );

// Check if someone want to keep the stats and settings.
if ( ( defined( 'WP_SMUSH_PRESERVE_STATS' ) && WP_SMUSH_PRESERVE_STATS ) || true === $keep_data ) {
	return;
}

global $wpdb;

$smushit_keys = array(
	'wp-smush-resmush-list',
	'wp-smush-nextgen-resmush-list',
	'wp-smush-resize_sizes',
	'wp-smush-transparent_png',
	'wp-smush-image_sizes',
	'wp-smush-super_smushed',
	'wp-smush-super_smushed_nextgen',
	'wp-smush-settings_updated',
	'wp-smush-hide_smush_welcome',
	'wp-smush-hide_upgrade_notice',
	'wp-smush-hide_update_info',
	'wp-smush-install-type',
	'wp-smush-version',
	'wp-smush-scan',
	'wp-smush-settings',
	'wp-smush-cdn_status',
	'wp-smush-lazy_load',
	'wp-smush-last_run_sync',
	'wp-smush-networkwide',
	'wp-smush-cron_update_running',
	'wp-smush-hide-conflict-notice',
	'wp-smush-show_upgrade_modal',
	'wp-smush-preset_configs',
	'wp-smush-webp_hide_wizard',
	'wp-smush-hide-tutorials',
	'wp-smush-hide_tutorials_from_bulk_smush', // Possible leftover from 3.8.4.
);

$db_keys = array(
	'skip-smush-setup',
	'smush_global_stats',
	'wp_smush_stats_nextgen',
);

// Cache Keys.
$cache_smush_group = array(
	'exceeding_items',
	'wp-smush-resize_count',
	'wp-smush-resize_savings',
	'wp-smush-pngjpg_savings',
	'wp-smush-smushed_ids',
	'media_attachments',
	'skipped_images',
	'images_with_backups',
	'wp-smush-dir_total_stats',
);

$cache_nextgen_group = array(
	'wp_smush_images',
	'wp_smush_images_smushed',
	'wp_smush_images_unsmushed',
	'wp_smush_stats_nextgen',
);

if ( ! is_multisite() ) {
	// Delete Options.
	foreach ( $smushit_keys as $key ) {
		delete_option( $key );
		delete_site_option( $key );
	}

	foreach ( $db_keys as $key ) {
		delete_option( $key );
		delete_site_option( $key );
	}

	// Delete Cache data.
	foreach ( $cache_smush_group as $s_key ) {
		wp_cache_delete( $s_key, 'wp-smush' );
	}

	foreach ( $cache_nextgen_group as $n_key ) {
		wp_cache_delete( $n_key, 'nextgen' );
	}

	wp_cache_delete( 'get_image_sizes', 'smush_image_sizes' );

	delete_transient( 'wp-smush-conflict_check' );
}

// Delete Directory Smush stats.
delete_option( 'dir_smush_stats' );
delete_option( 'wp_smush_scan' );
delete_option( 'wp_smush_api_auth' );
delete_site_option( 'wp_smush_api_auth' );

// Delete Post meta.
$meta_type  = 'post';
$meta_key   = 'wp-smpro-smush-data';
$meta_value = '';
$delete_all = true;

if ( is_multisite() ) {
	$offset = 0;
	$limit  = 100;
	while ( $blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs} LIMIT $offset, $limit", ARRAY_A ) ) {
		if ( $blogs ) {
			foreach ( $blogs as $blog ) {
				switch_to_blog( $blog['blog_id'] );
				delete_metadata( $meta_type, null, $meta_key, $meta_value, $delete_all );
				delete_metadata( $meta_type, null, 'wp-smush-lossy', '', $delete_all );
				delete_metadata( $meta_type, null, 'wp-smush-resize_savings', '', $delete_all );
				delete_metadata( $meta_type, null, 'wp-smush-original_file', '', $delete_all );
				delete_metadata( $meta_type, null, 'wp-smush-pngjpg_savings', '', $delete_all );

				foreach ( $smushit_keys as $key ) {
					delete_option( $key );
					delete_site_option( $key );
				}

				foreach ( $db_keys as $key ) {
					delete_option( $key );
					delete_site_option( $key );
				}

				// Delete Cache data.
				foreach ( $cache_smush_group as $s_key ) {
					wp_cache_delete( $s_key, 'wp-smush' );
				}

				foreach ( $cache_nextgen_group as $n_key ) {
					wp_cache_delete( $n_key, 'nextgen' );
				}

				wp_cache_delete( 'get_image_sizes', 'smush_image_sizes' );
			}
			restore_current_blog();
		}
		$offset += $limit;
	}
} else {
	delete_metadata( $meta_type, null, $meta_key, $meta_value, $delete_all );
	delete_metadata( $meta_type, null, 'wp-smush-lossy', '', $delete_all );
	delete_metadata( $meta_type, null, 'wp-smush-resize_savings', '', $delete_all );
	delete_metadata( $meta_type, null, 'wp-smush-original_file', '', $delete_all );
	delete_metadata( $meta_type, null, 'wp-smush-pngjpg_savings', '', $delete_all );
}
// Delete Directory smush table.
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}smush_dir_images" );

// Delete directory scan data.
delete_option( 'wp-smush-scan-step' );

// Delete all WebP images.
global $wp_filesystem;
if ( is_null( $wp_filesystem ) ) {
	WP_Filesystem();
}

$upload_dir = wp_get_upload_dir();
$webp_dir   = dirname( $upload_dir['basedir'] ) . '/smush-webp';
$wp_filesystem->delete( $webp_dir, true );

// Delete WebP test image.
$webp_img = $upload_dir['basedir'] . '/smush-webp-test.png';
$wp_filesystem->delete( $webp_img );

// TODO: Add procedure to delete backup files
// TODO: Update NextGen Metadata to remove Smush stats on plugin deletion.
