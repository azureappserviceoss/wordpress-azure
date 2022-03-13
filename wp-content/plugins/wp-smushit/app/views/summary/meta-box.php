<?php
/**
 * Summary meta box on dashboard page.
 *
 * @package WP_Smush
 *
 * @var string     $human_format
 * @var string     $human_size
 * @var int        $remaining
 * @var int        $resize_count
 * @var bool       $resize_enabled
 * @var int        $resize_savings
 * @var string|int $stats_percent
 * @var int        $total_optimized
 *
 * @var Smush\App\Abstract_Page $this  Page.
 */

use Smush\Core\Settings;

if ( ! defined( 'WPINC' ) ) {
	die;
}

$tooltip = sprintf(
	/* translators: %d - number of images */
	_n( 'You have %d image that needs smushing', 'You have %d images that need smushing', $remaining, 'wp-smushit' ),
	absint( $remaining )
);

$tooltip_singular = sprintf(
	/* translators: %s - count placeholder */
	__( 'You have %s image that needs smushing', 'wp-smushit' ),
	'{count}'
);

$tooltip_plural = sprintf(
	/* translators: %s - count placeholder */
	__( 'You have %s images that need smushing', 'wp-smushit' ),
	'{count}'
);

$branded_image = apply_filters( 'wpmudev_branding_hero_image', '' );
?>

<?php if ( $branded_image ) : ?>
	<div class="sui-summary-image-space" aria-hidden="true" style="background-image: url('<?php echo esc_url( $branded_image ); ?>')"></div>
<?php else : ?>
	<div class="sui-summary-image-space" aria-hidden="true"></div>
<?php endif; ?>
<div class="sui-summary-segment">
	<div class="sui-summary-details">
		<span class="sui-summary-large wp-smush-stats-human">
			<?php echo esc_html( $human_size ); ?>
		</span>
		<span
			class="sui-tooltip"
			data-tooltip="<?php echo esc_html( $tooltip ); ?>"
			data-singular="<?php echo esc_html( $tooltip_singular ); ?>"
			data-plural="<?php echo esc_html( $tooltip_plural ); ?>"
		>
			<i class="sui-icon-info sui-warning smush-stats-icon <?php echo $remaining > 0 ? '' : 'sui-hidden'; ?>" aria-hidden="true"></i>
		</span>
		<span class="sui-summary-detail wp-smush-savings">
			<span class="wp-smush-stats-human"><?php echo esc_html( $human_format ); ?></span> /
			<span class="wp-smush-stats-percent"><?php echo esc_html( $stats_percent ); ?></span>%
		</span>
		<span class="sui-summary-sub">
			<?php esc_html_e( 'Total Savings', 'wp-smushit' ); ?>
		</span>
		<span class="smushed-items-count">
			<span class="wp-smush-count-total">
				<span class="sui-summary-detail wp-smush-total-optimised">
					<?php echo esc_html( $total_optimized ); ?>
				</span>
				<span class="sui-summary-sub">
					<?php esc_html_e( 'Images Smushed', 'wp-smushit' ); ?>
				</span>
			</span>
			<?php if ( $resize_count > 0 ) : ?>
				<span class="wp-smush-count-resize-total">
					<span class="sui-summary-detail wp-smush-total-optimised">
						<?php echo esc_html( $resize_count ); ?>
					</span>
					<span class="sui-summary-sub">
						<?php esc_html_e( 'Images Resized', 'wp-smushit' ); ?>
					</span>
				</span>
			<?php endif; ?>
		</span>
	</div>
</div>

<div class="sui-summary-segment">
	<ul class="sui-list smush-stats-list">
		<li class="smush-resize-savings">
			<span class="sui-list-label">
				<?php esc_html_e( 'Image Resize Savings', 'wp-smushit' ); ?>
				<?php if ( ! $resize_enabled && $resize_savings <= 0 ) : ?>
					<p class="wp-smush-stats-label-message sui-hidden-sm sui-hidden-md sui-hidden-lg">
						<?php
						$settings_link = '#';
						$link_class    = 'wp-smush-resize-enable';

						if ( Settings::can_access( 'bulk' ) && 'smush-bulk' !== $this->get_slug() ) {
							$settings_link = $this->get_url( 'smush-bulk' ) . '#enable-resize';
							$link_class    = '';
						}

						printf(
							/* translators: %1$1s - opening <a> tag, %2$2s - closing <a> tag */
							esc_html__( 'Save a ton of space by not storing over-sized images on your server. %1$1sEnable image resizing%2$2s', 'wp-smushit' ),
							'<a role="button" class="' . esc_attr( $link_class ) . '" href="' . esc_url( $settings_link ) . '">',
							'</a>'
						);
						?>
					</p>
				<?php endif; ?>
			</span>
			<span class="sui-list-detail wp-smush-stats">
				<?php if ( $resize_enabled || $resize_savings > 0 ) : ?>
					<?php echo $resize_savings > 0 ? esc_html( $resize_savings ) : esc_html__( 'No resize savings', 'wp-smushit' ); ?>
				<?php else : ?>
					<a role="button" class="sui-hidden-xs <?php echo esc_attr( $link_class ); ?>" href="<?php echo esc_url( $settings_link ); ?>">
						<?php esc_html_e( 'Resize images', 'wp-smushit' ); ?>
					</a>
				<?php endif; ?>
			</span>
		</li>
		<?php
		/**
		 * Allows to output Directory Smush stats
		 */
		do_action( 'stats_ui_after_resize_savings' );
		?>
	</ul>
</div>
