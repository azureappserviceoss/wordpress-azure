<?php
/**
 * Accessibility settings meta box.
 *
 * @since 3.0
 * @package WP_Smush
 *
 * @var bool $accessible_colors  High contrast mode status.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-box-settings-row">
	<p>
		<?php esc_html_e( 'Enable support for any accessibility enhancements available in the plugin interface.', 'wp-smushit' ); ?>
	</p>
</div>

<?php do_action( 'wp_smush_render_setting_row', 'accessible_colors', $accessible_colors ); ?>
