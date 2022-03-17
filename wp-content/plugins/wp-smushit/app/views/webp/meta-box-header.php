<?php
/**
 * WebP meta box header.
 *
 * @package WP_Smush
 *
 * @var boolean $is_disabled   Whether the WebP module is disabled.
 * @var boolean $is_configured Whether WebP images are being served.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<h3 class="sui-box-title">
	<?php esc_html_e( 'Local WebP', 'wp-smushit' ); ?>
</h3>

<?php if ( ! WP_Smush::is_pro() ) : ?>
	<div class="sui-actions-left">
		<span class="sui-tag sui-tag-pro sui-tooltip sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( 'Join WPMU DEV to use this feature', 'wp-smushit' ); ?>">
			<?php esc_html_e( 'Pro', 'wp-smushit' ); ?>
		</span>
	</div>
<?php endif; ?>

<?php if ( ! $is_disabled ) : ?>
	<div class="sui-actions-right">
		<span class="sui-field-prefix"><?php esc_html_e( 'Made changes?', 'wp-smushit' ); ?></span>
		<button type="button" id="smush-webp-recheck" class="sui-button sui-button-ghost" data-is-configured="<?php echo $is_configured ? '1' : '0'; ?>">
			<span class="sui-loading-text"><i class="sui-icon-update"></i><?php esc_html_e( 'Re-check status', 'wp-smushit' ); ?></span>
			<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
		</button>
	</div>
<?php endif; ?>
