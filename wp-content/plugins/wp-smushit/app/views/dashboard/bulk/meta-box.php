<?php
/**
 * Bulk compress dashboard meta box.
 *
 * @since 3.8.6
 * @package WP_Smush
 *
 * @var int    $uncompressed  Number of uncompressed attachments.
 * @var string $upsell_url    Upsell URL.
 */

use Smush\Core\Core;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<p>
	<?php esc_html_e( 'Bulk Smush detects images that can be optimized and allows you to compress them in bulk.', 'wp-smushit' ); ?>
</p>

<?php if ( 0 === $uncompressed ) : ?>
	<div class="sui-notice sui-notice-success">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<span class="sui-notice-icon sui-icon-check-tick sui-md" aria-hidden="true"></span>
				<p><?php esc_html_e( 'All attachments have been smushed. Awesome!', 'wp-smushit' ); ?></p>
			</div>
		</div>
	</div>
<?php else : ?>
	<div class="sui-notice sui-notice-warning">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<span class="sui-notice-icon sui-icon-warning-alert sui-md" aria-hidden="true"></span>
				<p>
					<?php
					printf( /* translators: %d - number of uncompressed attachments */
						esc_html__( 'You have %d images that needs compressing!', 'wp-smushit' ),
						(int) $uncompressed
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<?php if ( ! WP_Smush::is_pro() && $uncompressed > Core::$max_free_bulk ) : ?>
		<div class="sui-notice sui-notice-upsell">
			<div class="sui-notice-content">
				<div class="sui-notice-message">
					<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
					<p>
						<?php
						printf( /* translators: %1$s - opening <a> tag, %2$s - closing </a> tag, %3$s - number of images */
							esc_html__( '%1$sUpgrade to Pro%2$s to bulk smush all images in one click. Free users can smush %3$s images per batch.', 'wp-smushit' ),
							'<a href="' . esc_url( $upsell_url ) . '" target="_blank" class="smush-upsell-link">',
							'</a>',
							(int) Core::$max_free_bulk
						);
						?>
					</p>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<a href="<?php echo esc_url( $this->get_url( 'smush-bulk' ) ); ?>" class="sui-button sui-button-blue">
		<?php esc_html_e( 'Bulk Smush', 'wp-smushit' ); ?>
	</a>
<?php endif; ?>
