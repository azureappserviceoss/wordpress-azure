<?php
/**
 * Lazy load meta box.
 *
 * @since 3.8.6
 * @package WP_Smush
 *
 * @var bool  $is_lazy_load  Is lazy load module active.
 * @var array $media_types   List of supported media types.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<p>
	<?php esc_html_e( 'This feature stops offscreen images from loading until a visitor scrolls to them. Make your page load faster, use less bandwidth and fix the “defer offscreen images” recommendation from a Google PageSpeed test.', 'wp-smushit' ); ?>
</p>

<?php if ( ! $is_lazy_load ) : ?>
	<button class="sui-button sui-button-blue" id="smush-enable-lazyload">
		<span class="sui-loading-text"><?php esc_html_e( 'Activate', 'wp-smushit' ); ?></span>
		<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
	</button>
<?php else : ?>
	<div class="sui-notice sui-notice-success">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<span class="sui-notice-icon sui-icon-check-tick sui-md" aria-hidden="true"></span>
				<p><?php esc_html_e( 'Lazy loading is active.', 'wp-smushit' ); ?></p>
			</div>
		</div>
	</div>

	<div class="sui-box-settings-row sui-flushed">
		<span class="sui-settings-label"><?php esc_html_e( 'Active Media Types', 'wp-smushit' ); ?></span>
		<div>
			<?php foreach ( $media_types as $media => $value ) : ?>
				<?php
				if ( true !== $value ) {
					continue;
				}
				?>
				<span class="smush-filename-extension smush-extension-<?php echo esc_attr( $media ); ?>">
					<?php echo esc_html( $media ); ?>
				</span>
			<?php endforeach; ?>
		</div>
	</div>

	<a href="<?php echo esc_url( $this->get_url( 'smush-lazy-load' ) ); ?>" class="sui-button sui-button-ghost">
		<span class="sui-icon-wrench-tool" aria-hidden="true"></span>
		<?php esc_html_e( 'Configure', 'wp-smushit' ); ?>
	</a>
<?php endif; ?>
