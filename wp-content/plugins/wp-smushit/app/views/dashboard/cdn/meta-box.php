<?php
/**
 * CDN meta box.
 *
 * @since 3.8.6
 * @package WP_Smush
 *
 * @var string $cdn_status  CDN status.
 * @var bool   $is_webp     WebP conversion status.
 * @var string $upsell_url  Upsell URL.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<p>
	<?php esc_html_e( 'Multiply the speed and savings! Upload huge images and the Smush CDN will perfectly resize the files, safely convert to a Next-Gen format (WebP), and delivers them directly to your visitors from our blazing-fast multi-location globe servers.', 'wp-smushit' ); ?>
</p>

<?php if ( ! WP_Smush::is_pro() ) : ?>
	<a href="<?php echo esc_url( $upsell_url ); ?>" target="_blank" class="sui-button sui-button-purple">
		<?php esc_html_e( 'Upgrade to Pro', 'wp-smushit' ); ?>
	</a>
<?php else : ?>
	<?php if ( 'disabled' === $cdn_status ) : ?>
		<button class="sui-button sui-button-blue" id="smush-enable-cdn">
			<span class="sui-loading-text"><?php esc_html_e( 'Activate', 'wp-smushit' ); ?></span>
			<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
		</button>
	<?php else : ?>
		<?php if ( 'overcap' === $cdn_status ) : ?>
			<div class="sui-notice sui-notice-error">
				<div class="sui-notice-content">
					<div class="sui-notice-message">
						<span class="sui-notice-icon sui-icon-warning-alert sui-md" aria-hidden="true"></span>
						<p><?php esc_attr_e( "You've gone through your CDN bandwidth limit, so we’ve stopped serving your images via the CDN. Contact your administrator to upgrade your Smush CDN plan to reactivate this service.", 'wp-smushit' ); ?></p>
					</div>
				</div>
			</div>
		<?php elseif ( 'upgrade' === $cdn_status ) : ?>
			<div class="sui-notice sui-notice-warning">
				<div class="sui-notice-content">
					<div class="sui-notice-message">
						<span class="sui-notice-icon sui-icon-warning-alert sui-md" aria-hidden="true"></span>
						<p><?php esc_attr_e( "You're almost through your CDN bandwidth limit. Please contact your administrator to upgrade your Smush CDN plan to ensure you don't lose this service.", 'wp-smushit' ); ?></p>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="sui-notice sui-notice-success">
				<div class="sui-notice-content">
					<div class="sui-notice-message">
						<span class="sui-notice-icon sui-icon-check-tick sui-md" aria-hidden="true"></span>
						<p><?php esc_attr_e( 'Your media is currently being served from the WPMU DEV CDN.', 'wp-smushit' ); ?></p>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<div class="sui-box-settings-row sui-flushed sui-no-padding">
			<table class="sui-table sui-table-flushed">
				<thead>
				<tr>
					<th><?php esc_html_e( 'Tools', 'wp-smushit' ); ?></th>
					<th colspan="2" width="50%"><?php esc_html_e( 'Status', 'wp-smushit' ); ?></th>
				</tr>
				</thead>

				<tbody>
				<tr>
					<td class="sui-table-item-title">
						<span class="smush-filename-extension smush-extension-webp">webp</span>
						<?php esc_html_e( 'WebP Conversion', 'wp-smushit' ); ?>
					</td>
					<td>
						<?php if ( $is_webp ) : ?>
							<span class="sui-tag sui-tag-green"><?php esc_html_e( 'Active', 'wp-smushit' ); ?></span>
						<?php else : ?>
							<span class="sui-tag"><?php esc_html_e( 'Inactive', 'wp-smushit' ); ?></span>
						<?php endif; ?>
					</td>
					<td>
						<a href="<?php echo esc_url( $this->get_url( 'smush-cdn' ) ); ?>" role="button" class="sui-button-icon">
							<span class="sui-icon-widget-settings-config" aria-hidden="true"></span>
							<span class="sui-screen-reader-text"><?php esc_html_e( 'Configure', 'wp-smushit' ); ?></span>
						</a>
					</td>
				</tr>
				</tbody>
			</table>
		</div>

		<a href="<?php echo esc_url( $this->get_url( 'smush-cdn' ) ); ?>" class="sui-button sui-button-ghost">
			<span class="sui-icon-wrench-tool" aria-hidden="true"></span>
			<?php esc_html_e( 'Configure', 'wp-smushit' ); ?>
		</a>
	<?php endif; ?>
<?php endif; ?>
