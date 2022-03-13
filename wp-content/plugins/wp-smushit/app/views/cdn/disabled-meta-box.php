<?php
/**
 * CDN disabled meta box.
 *
 * @since 3.0
 * @package WP_Smush
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-block-content-center">
	<?php if ( ! apply_filters( 'wpmudev_branding_hide_branding', false ) ) : ?>
		<img src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/graphic-smush-cdn-default.png' ); ?>"
			srcset="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/graphic-smush-cdn-default@2x.png' ); ?> 2x"
			alt="<?php esc_html_e( 'Smush CDN', 'wp-smushit' ); ?>">
	<?php endif; ?>

	<p>
		<?php
		esc_html_e(
			'Multiply the speed and savings! Upload huge images and the Smush CDN will perfectly resize the files, safely convert to a Next-Gen format (WebP), and delivers them directly to your visitors from our blazing-fast multi-location globe servers.',
			'wp-smushit'
		);
		?>
	</p>

	<button class="sui-button sui-button-blue" id="smush-enable-cdn">
		<span class="sui-loading-text"><?php esc_html_e( 'GET STARTED', 'wp-smushit' ); ?></span>
		<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
	</button>

</div>
