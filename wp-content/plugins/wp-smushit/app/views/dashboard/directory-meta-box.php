<?php
/**
 * Directory compress meta box.
 *
 * @since 3.8.6
 * @package WP_Smush
 *
 * @var array $images  Array of images with errors.
 * @var int   $errors  Number of errors.
 *
 * @var Smush\App\Abstract_Page $this  Dashboard page.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<p>
	<?php esc_html_e( 'In addition to smushing your media uploads, you may want to smush non WordPress images that are outside of your uploads directory. Get started by adding files and folders you wish to optimize.', 'wp-smushit' ); ?>
</p>

<?php if ( ! empty( $images ) ) : ?>
	<div class="sui-notice sui-notice-warning">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<span class="sui-notice-icon sui-icon-warning-alert sui-md" aria-hidden="true"></span>
				<p>
					<?php
					printf( /* translators: %d - number of failed images */
						esc_html__( "%d images failed to be optimized. This is usually because they no longer exist, or we can't optimize the file format.", 'wp-smushit' ),
						(int) $errors
					);
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="smush-final-log sui-margin-bottom">
		<div class="smush-bulk-errors">
			<?php foreach ( $images as $image ) : ?>
				<div class="smush-bulk-error-row">
					<div class="smush-bulk-image-data">
						<i class="sui-icon-photo-picture" aria-hidden="true"></i>
						<span class="smush-image-name"><?php echo esc_html( $image['path'] ); ?></span>
						<span class="smush-image-error"><?php echo esc_html( $image['error'] ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ( $errors > 20 ) : ?>
			<p class="sui-description">
				<?php
				printf( /* translators: %d: number of images with errors */
					esc_html__( 'Showing 20 of %d failed optimizations. Fix or remove these images and run another Directory Smush.', 'wp-smushit' ),
					absint( $errors )
				);
				?>
			</p>
		<?php endif; ?>
	</div>
	<a href="<?php echo esc_url( $this->get_url( 'smush-directory' ) ); ?>&scan=done" class="sui-button sui-button-ghost">
		<span class="sui-icon-eye" aria-hidden="true"></span>
		<?php esc_html_e( 'View All', 'wp-smushit' ); ?>
	</a>
<?php else : ?>
	<a href="<?php echo esc_url( $this->get_url( 'smush-directory' ) ); ?>&start" class="sui-button sui-button-blue">
		<?php esc_html_e( 'Choose Directory', 'wp-smushit' ); ?>
	</a>
<?php endif; ?>
