<?php
/**
 * Upsell meta box.
 *
 * @since 3.8.6
 * @package WP_Smush
 *
 * @var string $upsell_url  Upsell URL.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<p>
	<?php esc_html_e( 'Get our full WordPress image optimization suite with Smush Pro and additional benefits of WPMU DEV membership.', 'wp-smushit' ); ?>
</p>

<ol class="sui-upsell-list">
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'Fix Google PageSpeed image recommendations', 'wp-smushit' ); ?>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( '10 GB Smush CDN', 'wp-smushit' ); ?>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( '2x better compression', 'wp-smushit' ); ?>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'Serve a next-gen format with WebP conversion', 'wp-smushit' ); ?>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( '24/7 live WordPress support', 'wp-smushit' ); ?>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'The WPMU DEV Guarantee', 'wp-smushit' ); ?>
	</li>
</ol>

<a href="<?php echo esc_url( $upsell_url ); ?>" target="_blank" class="sui-button sui-button-purple sui-margin-top">
	<?php esc_html_e( 'Try Pro for FREE today!', 'wp-smushit' ); ?>
</a>
