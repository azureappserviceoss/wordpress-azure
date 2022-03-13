<?php
/**
 * Settings meta box.
 *
 * @package WP_Smush
 *
 * @var array $basic_features    Basic features list.
 * @var bool  $cdn_enabled       CDN status.
 * @var array $grouped_settings  Grouped settings that can be skipped.
 * @var array $settings          Settings values.
 */

use Smush\Core\Settings;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<?php if ( WP_Smush::is_pro() && $cdn_enabled && Settings::can_access( 'bulk' ) ) : ?>
	<div class="sui-notice sui-notice-info">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
				<p><?php esc_html_e( 'Your images are currently being served via the WPMU DEV CDN. Bulk smush will continue to operate as per your settings below and is treated completely separately in case you ever want to disable the CDN.', 'wp-smushit' ); ?></p>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php
foreach ( $grouped_settings as $name ) {
	// If not bulk settings - skip.
	if ( ! in_array( $name, $grouped_settings, true ) ) {
		continue;
	}

	// Skip premium features if not a member.
	if ( ! in_array( $name, $basic_features, true ) && ! WP_Smush::is_pro() ) {
		continue;
	}

	$value = empty( $settings[ $name ] ) ? false : $settings[ $name ];

	// Show settings option.
	do_action( 'wp_smush_render_setting_row', $name, $value );
}

// Hook after general settings.
do_action( 'wp_smush_after_basic_settings' );
?>
