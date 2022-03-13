<?php
/**
 * Delete all webp files modal.
 *
 * @since 3.8.0
 * @package WP_Smush
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-modal sui-modal-sm">
	<div
		role="dialog"
		id="wp-smush-wp-delete-all-dialog"
		class="sui-modal-content smush-dawif sui-content-fade-in"
		aria-modal="true"
		aria-labelledby="smush-dawif-title"
		aria-describedby="smush-dawif-description"
	>
		<div class="sui-box">
			<div id="smush-dawif-content">
				<div class="sui-box-header sui-flatten sui-content-center sui-spacing-top--60">
					<button type="button" class="sui-button-icon sui-button-float--right" data-modal-close>
						<i class="sui-icon-close sui-md" aria-hidden="true"></i>
						<span class="sui-screen-reader-text"><?php esc_html_e( 'Close this modal', 'wp-smushit' ); ?></span>
					</button>

					<h3 class="sui-box-title sui-lg" id="smush-dawif-title">
						<?php esc_html_e( 'Delete WebP files', 'wp-smushit' ); ?>
					</h3>
				</div>
				<div class="sui-box-body sui-flatten sui-content-center sui-spacing-top--20">
					<p class="sui-description" id="smush-dawif-description" style="margin-bottom:15px;">
						<?php esc_html_e( 'Are you sure you want to delete all WebP files?', 'wp-smushit' ); ?>
					</p>
					<div
						id="wp-smush-webp-delete-all-error-notice"
						class="sui-notice sui-notice-error"
						style="margin-bottom:15px;"
						role="alert"
						aria-live="assertive"
					></div>
					<div class="sui-block-content-center" style="padding-top:15px;">
						<button type="button" class="sui-button sui-button-ghost" data-modal-close="">
							<?php esc_html_e( 'Cancel', 'wp-smushit' ); ?>
						</button>
						<button
							type="button"
							id="wp-smush-webp-delete-all"
							class="sui-button sui-button-red"
						>
							<span class="sui-loading-text"><?php esc_html_e( 'Delete', 'wp-smushit' ); ?></span>
							<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
						</button>
					</div>
				</div>
				<?php if ( ! apply_filters( 'wpmudev_branding_hide_branding', false ) ) : ?>
					<div class="sui-box-footer sui-flatten sui-spacing-bottom--0">
						<img class="sui-image sui-image-center" src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/onboarding/graphic-onboarding.png' ); ?>" srcset="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/onboarding/graphic-onboarding@2x.png' ); ?> 2x" alt="<?php esc_attr_e( 'WP Smush', 'wp-smushit' ); ?>">
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
