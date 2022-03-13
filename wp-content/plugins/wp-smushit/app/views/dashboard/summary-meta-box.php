<?php
/**
 * Summary meta box on dashboard page.
 *
 * @since 3.8.3
 * @package WP_Smush
 *
 * @var string $cdn_status       CDN status.
 * @var bool   $is_cdn           CDN module status.
 * @var bool   $is_lazy_load     Lazy load status.
 * @var bool   $is_local_webp    Local WebP status.
 * @var int    $remaining        Remaining images.
 * @var int    $resize_count     Number of resizes images.
 * @var string $upsell_url_cdn   CDN upsell URL.
 * @var string $upsell_url_webp  Local WebP upsell URL.
 * @var bool   $webp_configured  WebP set up configured.
 *
 * @var Abstract_Page $this
 */

use Smush\App\Abstract_Page;

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
		<span class="sui-summary-large wp-smush-stats-human">
			<?php echo esc_html( $remaining ); ?>
		</span>
		<span class="sui-summary-sub">
			<?php esc_html_e( 'Images to Smush', 'wp-smushit' ); ?>
		</span>
		<span class="smushed-items-count">
			<span class="wp-smush-count-resize-total">
				<span class="sui-summary-detail wp-smush-total-optimised">
					<?php echo esc_html( $resize_count ); ?>
				</span>
				<span class="sui-summary-sub">
					<?php esc_html_e( 'Images Resized', 'wp-smushit' ); ?>
				</span>
			</span>
		</span>
	</div>
</div>

<div class="sui-summary-segment" style="overflow: visible">
	<ul class="sui-list">
		<li>
			<span class="sui-list-label">
				<?php esc_html_e( 'CDN', 'wp-smushit' ); ?>
			</span>
			<span class="sui-list-detail">
				<?php if ( ! WP_Smush::is_pro() ) : ?>
					<a href="<?php echo esc_url( $upsell_url_cdn ); ?>" target="_blank" class="smush-upgrade-text">
						<?php esc_html_e( 'Upgrade', 'wp-smushit' ); ?>
					</a>
					<span class="sui-tooltip sui-tooltip-constrained sui-tooltip-top-right" style="--tooltip-width: 360px;" data-tooltip="<?php esc_attr_e( 'Multiply the speed and savings! Serve your images from our CDN from 45 blazing fast servers around the world.', 'wp-smushit' ); ?>">
						<span class="sui-tag sui-tag-sm sui-tag-purple"><?php esc_html_e( 'Pro', 'wp-smushit' ); ?></span>
					</span>
				<?php elseif ( $is_cdn ) : ?>
					<?php if ( 'overcap' === $cdn_status ) : ?>
						<span class="sui-tooltip sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( "You're almost through your CDN bandwidth limit. Please contact your administrator to upgrade your Smush CDN plan to ensure you don't lose this service.", 'wp-smushit' ); ?>">
							<span class="sui-icon-warning-alert sui-md sui-warning" aria-hidden="true"></span>
						</span>
						<span><?php esc_html_e( 'Overcap', 'wp-smushit' ); ?></span>
					<?php elseif ( 'upgrade' === $cdn_status ) : ?>
						<span class="sui-tooltip sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( "You've gone through your CDN bandwidth limit, so we’ve stopped serving your images via the CDN. Contact your administrator to upgrade your Smush CDN plan to reactivate this service.", 'wp-smushit' ); ?>">
							<span class="sui-icon-warning-alert sui-md sui-error" aria-hidden="true"></span>
						</span>
						<span><?php esc_html_e( 'Overcap', 'wp-smushit' ); ?></span>
					<?php else : ?>
						<a href="<?php echo esc_url( $this->get_url( 'smush-cdn' ) ); ?>">
							<span class="sui-tag sui-tag-green"><?php esc_html_e( 'Active', 'wp-smushit' ); ?></span>
						</a>
					<?php endif; ?>
				<?php else : ?>
					<a href="<?php echo esc_url( $this->get_url( 'smush-cdn' ) ); ?>">
						<span class="sui-tag"><?php esc_html_e( 'Inactive', 'wp-smushit' ); ?></span>
					</a>
				<?php endif; ?>
			</span>
		</li>
		<?php if ( ! is_multisite() ) : ?>
			<li>
				<span class="sui-list-label">
					<?php esc_html_e( 'Local WebP', 'wp-smushit' ); ?>
				</span>
				<span class="sui-list-detail">
					<?php if ( ! WP_Smush::is_pro() ) : ?>
						<a href="<?php echo esc_url( $upsell_url_webp ); ?>" target="_blank" class="smush-upgrade-text">
							<?php esc_html_e( 'Upgrade', 'wp-smushit' ); ?>
						</a>
						<span class="sui-tooltip sui-tooltip-constrained sui-tooltip-top-right" style="--tooltip-width: 360px;" data-tooltip="<?php esc_attr_e( 'Fix the “Serve images in next-gen format” Google PageSpeed recommendation by setting up this feature. Locally serve WebP versions of your images to supported browsers, and gracefully fall back to JPEGs and PNGs for browsers that don’t support WebP.', 'wp-smushit' ); ?>">
							<span class="sui-tag sui-tag-sm sui-tag-purple"><?php esc_html_e( 'Pro', 'wp-smushit' ); ?></span>
						</span>
					<?php elseif ( $is_local_webp && $webp_configured ) : ?>
						<a href="<?php echo esc_url( $this->get_url( 'smush-webp' ) ); ?>">
							<span class="sui-tag sui-tag-green"><?php esc_html_e( 'Active', 'wp-smushit' ); ?></span>
						</a>
					<?php elseif ( $is_local_webp && ! $webp_configured ) : ?>
						<span class="sui-tooltip sui-tooltip-constrained" data-tooltip="<?php esc_attr_e( 'The setup for Local WebP feature is inactive. Complete the setup, to activate the feature.', 'wp-smushit' ); ?>">
							<span class="sui-icon-warning-alert sui-md sui-warning" aria-hidden="true"></span>
						</span>
						<span><?php esc_html_e( 'Incomplete setup', 'wp-smushit' ); ?></span>
					<?php else : ?>
						<a href="<?php echo esc_url( $this->get_url( 'smush-webp' ) ); ?>">
							<span class="sui-tag"><?php esc_html_e( 'Inactive', 'wp-smushit' ); ?></span>
						</a>
					<?php endif; ?>
				</span>
			</li>
		<?php endif; ?>
		<li>
			<span class="sui-list-label">
				<?php esc_html_e( 'Lazy Load', 'wp-smushit' ); ?>
			</span>
			<span class="sui-list-detail">
				<?php if ( $is_lazy_load ) : ?>
					<a href="<?php echo esc_url( $this->get_url( 'smush-lazy-load' ) ); ?>">
						<span class="sui-tag sui-tag-green"><?php esc_html_e( 'Active', 'wp-smushit' ); ?></span>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( $this->get_url( 'smush-lazy-load' ) ); ?>">
						<span class="sui-tag"><?php esc_html_e( 'Inactive', 'wp-smushit' ); ?></span>
					</a>
				<?php endif; ?>
			</span>
		</li>
	</ul>
</div>
