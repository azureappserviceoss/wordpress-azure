<?php
/**
 * CDN meta box header.
 *
 * @since 3.8.6
 * @package WP_Smush
 *
 * @var string $title  Meta box title.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<h3 class="sui-box-title"><?php echo esc_html( $title ); ?></h3>

<?php if ( ! WP_Smush::is_pro() ) : ?>
	<div class="sui-actions-left">
		<span class="sui-tag sui-tag-pro"><?php esc_html_e( 'Pro', 'wp-smushit' ); ?></span>
	</div>
<?php endif; ?>
