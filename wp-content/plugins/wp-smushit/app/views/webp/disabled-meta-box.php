<?php
/**
 * WebP disabled meta box.
 *
 * @since 3.8.0
 * @package WP_Smush
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-message sui-no-padding">
	<?php if ( ! apply_filters( 'wpmudev_branding_hide_branding', false ) ) : ?>
		<img src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/smush-no-media.png' ); ?>" alt="<?php esc_html_e( 'Smush WebP', 'wp-smushit' ); ?>" class="sui-image" />
	<?php endif; ?>
	<div class="sui-message-content">
		<p>
			<?php esc_html_e( 'Fix the "Serve images in next-gen format" Google PageSpeed recommendation by setting up this feature. Locally serve WebP versions of your images to supported browsers, and gracefully fall back on JPEGs and PNGs for browsers that don\'t support WebP.', 'wp-smushit' ); ?>
		</p>

		<button class="sui-button sui-button-blue" id="smush-toggle-webp-button" data-action="enable">
			<span class="sui-loading-text"><?php esc_html_e( 'Get started', 'wp-smushit' ); ?></span>
			<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
		</button>
	</div>
</div>
