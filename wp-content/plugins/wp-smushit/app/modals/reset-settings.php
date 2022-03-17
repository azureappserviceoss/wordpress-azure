<?php
/**
 * Reset settings modal.
 *
 * @since 3.2.0
 * @package WP_Smush
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-modal sui-modal-sm">
	<div
			role="dialog"
			id="reset-settings-dialog"
			class="sui-modal-content reset-settings-dialog"
			aria-modal="true"
			aria-labelledby="reset-settings-dialog-title"
			aria-describedby="reset-settings-dialog-description"
	>
		<div class="sui-box">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--40">
				<h3 class="sui-box-title sui-lg" id="reset-settings-dialog-title">
					<?php esc_html_e( 'Reset Settings', 'wp-smushit' ); ?>
				</h3>

				<p class="sui-description" id="reset-settings-dialog-description">
					<?php esc_html_e( 'Are you sure you want to reset Smush’s settings back to the factory defaults?', 'wp-smushit' ); ?>
				</p>
			</div>
			<div class="sui-box-body sui-content-center">
				<input type="hidden" id="wp_smush_reset" name="_wpnonce" value="<?php echo esc_attr( wp_create_nonce( 'wp_smush_reset' ) ); ?>">
				<a class="sui-button sui-button-ghost" data-modal-close="">
					<?php esc_html_e( 'Cancel', 'wp-smushit' ); ?>
				</a>
				<a class="sui-button sui-button-ghost sui-button-red sui-button-icon-left" onclick="WP_Smush.helpers.resetSettings()" id="reset-setting-confirm">
					<i class="sui-icon-trash" aria-hidden="true"></i>
					<?php esc_html_e( 'Reset settings', 'wp-smushit' ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
