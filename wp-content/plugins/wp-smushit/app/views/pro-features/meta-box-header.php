<?php
/**
 * Pro features meta box header.
 *
 * @package WP_Smush
 *
 * @var string $title Title.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<h3 class="sui-box-title">
	<span class="sui-icon-smush" aria-hidden="true"></span>
	<?php echo esc_html( $title ); ?>
	<span class="sui-tag sui-tag-pro">
		<?php esc_html_e( 'Pro', 'wp-smushit' ); ?>
	</span>
</h3>
