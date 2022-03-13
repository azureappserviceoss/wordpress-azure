<?php
/**
 * Checking files dialog, shown after the onboarding series.
 *
 * @since 3.1.0
 * @package WP_Smush
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-modal sui-modal-md">
	<div
			role="dialog"
			id="checking-files-dialog"
			class="sui-modal-content checking-files-dialog"
			aria-modal="true"
			aria-labelledby="checking-files-dialog-title"
			aria-describedby="checking-files-dialog-description"
	>
		<div class="sui-box">
			<div class="sui-box-header sui-flatten sui-content-center sui-spacing-sides--80 sui-spacing-top--60">
				<i class="sui-icon-loader sui-loading sui-lg" aria-hidden="true"></i>
				<h3 class="sui-box-title sui-lg" id="checking-files-dialog-title" style="margin-top: 10px">
					<?php esc_attr_e( 'Checking images', 'wp-smushit' ); ?>
				</h3>

				<p class="sui-description" id="checking-files-dialog-description">
					<?php
					esc_html_e(
						'Great! We’re just running a check to see what images need compressing. You can configure more advanced settings once this image check is complete.',
						'wp-smushit'
					);
					?>
				</p>
			</div>

			<?php if ( ! apply_filters( 'wpmudev_branding_hide_branding', false ) ) : ?>
				<div class="sui-box-footer sui-flatten sui-spacing-bottom--0">
					<img class="sui-image sui-image-center"
						src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/onboarding/graphic-onboarding.png' ); ?>"
						srcset="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/onboarding/graphic-onboarding@2x.png' ); ?> 2x"
						alt="<?php esc_attr_e( 'WP Smush', 'wp-smushit' ); ?>">
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

