<?php
/**
 * CDN meta box header.
 *
 * @package WP_Smush
 *
 * @var string $title  Title.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<h3 class="sui-box-title">
	<?php esc_html_e( 'CDN', 'wp-smushit' ); ?>
</h3>

<?php if ( ! WP_Smush::is_pro() ) : ?>
	<div class="sui-actions-left">
		<span class="sui-tag sui-tag-pro sui-tooltip sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( 'Join WPMU DEV to host your images on our blazing fast global CDN', 'wp-smushit' ); ?>">
			<?php esc_html_e( 'Pro', 'wp-smushit' ); ?>
		</span>
	</div>
<?php endif; ?>
