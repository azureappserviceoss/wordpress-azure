<?php
/**
 * NextGen summary meta box.
 *
 * @package WP_Smuh
 *
 * @var int        $image_count
 * @var bool       $lossy_enabled
 * @var int        $smushed_image_count
 * @var int        $super_smushed_count
 * @var string     $stats_human
 * @var string|int $stats_percent
 * @var int        $total_count
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$branded_image = apply_filters( 'wpmudev_branding_hero_image', '' );
?>

<?php if ( $branded_image ) : ?>
	<div class="sui-summary-image-space" aria-hidden="true" style="background-image: url('<?php echo esc_url( $branded_image ); ?>')"></div>
<?php else : ?>
	<div class="sui-summary-image-space" aria-hidden="true"></div>
<?php endif; ?>
<div class="sui-summary-segment">
	<div class="sui-summary-details">
		<span class="sui-summary-large wp-smush-total-optimised">
			<?php echo absint( $image_count ); ?>
		</span>
		<span class="sui-summary-sub">
			<?php esc_html_e( 'Images smushed', 'wp-smushit' ); ?>
		</span>
	</div>
</div>
<div class="sui-summary-segment">
	<ul class="sui-list smush-stats-list-nextgen">
		<li class="smush-resize-savings">
			<span class="sui-list-label">
				<?php esc_html_e( 'Total savings', 'wp-smushit' ); ?>
			</span>
			<span class="sui-list-detail wp-smush-stats">
				<?php wp_nonce_field( 'save_wp_smush_options', 'wp_smush_options_nonce', '' ); ?>
				<span class="wp-smush-stats-percent">
					<?php echo esc_html( $stats_percent ); ?>
				</span>%
				<span class="wp-smush-stats-sep">/</span>
				<span class="wp-smush-stats-human">
					<?php echo esc_html( $stats_human ); ?>
				</span>
			</span>
		</li>
		<?php if ( apply_filters( 'wp_smush_show_nextgen_lossy_stats', true ) ) : ?>
			<li class="super-smush-attachments">
				<span class="sui-list-label">
					<?php esc_html_e( 'Super-Smushed images', 'wp-smushit' ); ?>
				</span>
				<span class="sui-list-detail wp-smush-stats">
					<?php if ( $lossy_enabled ) : ?>
						<span class="smushed-count"><?php echo count( $super_smushed_count ); ?></span>/<?php echo absint( $total_count ); ?>
					<?php else : ?>
						<span class="sui-tag sui-tag-disabled wp-smush-lossy-disabled">
							<?php esc_html_e( 'Disabled', 'wp-smushit' ); ?>
						</span>
					<?php endif; ?>
				</span>
			</li>
		<?php endif; ?>
	</ul>
</div>
