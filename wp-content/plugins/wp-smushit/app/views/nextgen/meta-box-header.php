<?php
/**
 * NextGen header meta box.
 *
 * @package WP_Smush
 *
 * @var string $title
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<h3 class="sui-box-title">
	<?php echo esc_html( $title ); ?>
</h3>

<div class="sui-actions-right">
	<small>
		<?php
		printf(
			/* translators: %1$s - a href opening tag, %2$s - a href closing tag */
			esc_html__( 'Smush individual images via your %1$sManage Galleries%2$s section', 'wp-smushit' ),
			'<a href="' . esc_url( admin_url() . 'admin.php?page=nggallery-manage-gallery' ) . '" title="' . esc_html__( 'Manage Galleries', 'wp-smushit' ) . '">',
			'</a>'
		);
		?>
	</small>
</div>
