<?php
/**
 * Bulk Smush meta box.
 *
 * @since 2.9.0
 * @package WP_Smush
 *
 * @var Smush\Core\Core $core                  Instance of Smush\Core\Core
 * @var bool            $hide_pagespeed        Check whether to show PageSpeed recommendation or not.
 * @var bool            $is_pro                Check if PRO user or not.
 * @var integer         $unsmushed_count       Count of the images that need smushing.
 * @var integer         $resmush_count         Count of the images that need re-smushing.
 * @var integer         $total_images_to_smush Total count of all images to smush. Unsmushed images + images to re-smush.
 * @var string          $upgrade_url           Upgrade to PRO link.
 * @var string          $bulk_upgrade_url      Bulk Smush upgrade to PRO url.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<?php if ( 0 !== absint( $core->total_count ) ) : ?>
<p><?php esc_html_e( 'Bulk smush detects images that can be optimized and allows you to compress them in bulk.', 'wp-smushit' ); ?></p>
<?php endif; ?>

<?php
// If there are no images in media library.
if ( 0 === absint( $core->total_count ) ) {
	?>
	<div class="sui-message">
		<?php if ( ! apply_filters( 'wpmudev_branding_hide_branding', false ) ) : ?>
			<img src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/smush-no-media.png' ); ?>"
				alt="<?php esc_attr_e( 'No attachments found - Upload some images', 'wp-smushit' ); ?>"
				class="sui-image"
			>
		<?php endif; ?>

		<div class="sui-message-content">
			<p>
				<?php esc_html_e( 'We haven’t found any images in your media library yet so there’s no smushing to be done!', 'wp-smushit' ); ?><br>
				<?php esc_html_e( 'Once you upload images, reload this page and start playing!', 'wp-smushit' ); ?>
			</p>

			<a class="sui-button sui-button-blue" href="<?php echo esc_url( admin_url( 'media-new.php' ) ); ?>">
				<?php esc_html_e( 'UPLOAD IMAGES', 'wp-smushit' ); ?>
			</a>
		</div>
	</div>
	<?php
	return;
}
?>

<?php $this->view( 'progress-bar', array( 'count' => $total_images_to_smush ), 'common' ); ?>

<div class="smush-final-log sui-hidden">
	<div class="smush-bulk-errors"></div>
	<div class="smush-bulk-errors-actions sui-hidden">
		<a href="<?php echo esc_url( admin_url( 'upload.php' ) ); ?>" class="sui-button sui-button-icon sui-button-ghost">
			<i class="sui-icon-photo-picture" aria-hidden="true"></i>
			<?php esc_html_e( 'View all', 'wp-smushit' ); ?>
		</a>
	</div>
</div>

<?php
// This notice goes above the pagespeed recommendations in Pro.
if ( $is_pro ) {
	$this->view( 'all-images-smushed-notice', array( 'all_done' => empty( $total_images_to_smush ) ), 'common' );
}
?>

<?php if ( ! $hide_pagespeed ) : ?>
	<div class="wp-smush-pagespeed-recommendation sui-border-frame <?php echo empty( $total_images_to_smush ) ? '' : 'sui-hidden'; ?>">

		<p class="dismiss-recommendation sui-hidden-sm sui-hidden-md sui-hidden-lg" style="text-align: right;">
			<span class="sui-icon-close sui-sm" aria-hidden="true"></span>
			<?php esc_html_e( 'Dismiss', 'wp-smushit' ); ?>
		</p>
		<span class="smush-recommendation-title">
			<?php esc_html_e( 'Still having trouble with PageSpeed tests? Give these a go…', 'wp-smushit' ); ?>
		</span>

		<span class="dismiss-recommendation sui-actions-right sui-hidden-xs">
			<span class="sui-icon-close sui-sm" aria-hidden="true"></span>
			<?php esc_html_e( 'Dismiss', 'wp-smushit' ); ?>
		</span>

		<ol class="smush-recommendation-list">
			<?php if ( ! $is_pro ) : ?>
				<li class="smush-recommendation-lossy">
					<?php
					printf(
						/* translators: %1$s: opening a tag, %2$s: closing a tag */
						esc_html__( 'Upgrade to Smush Pro for advanced lossy compression. %1$sTry pro free%2$s.', 'wp-smushit' ),
						'<a href="' . esc_url( $upgrade_url ) . '" target="_blank" style="color: #8D00B1;">',
						'</a>'
					);
					?>
				</li>
			<?php elseif ( ! $this->settings->get( 'lossy' ) ) : ?>
				<li class="smush-recommendation-lossy">
					<?php
					printf(
						/* translators: %1$s: opening a tag, %2$s: closing a tag */
						esc_html__( 'Enable %1$sSuper-Smush%2$s for advanced lossy compression to optimize images further with almost no visible drop in quality.', 'wp-smushit' ),
						'<a href="#" class="wp-smush-lossy-enable">',
						'</a>'
					);
					?>
				</li>
			<?php endif; ?>
			<li class="smush-recommendation-resize">
				<?php
				if ( ! $is_pro ) :
					printf(
						/* translators: %1$s: opening a tag, %2$s: closing a tag */
						esc_html__( ' %1$sRead article %2$s “How To Ace Google’s Image PageSpeed Recommendations With Smush”.', 'wp-smushit' ),
						'<a href="' . esc_url( 'https://goo.gl/kCqWxS' ) . '" target="_blank">',
						'</a>'
					);
				else :
					printf(
						/* translators: %1$s: opening a tag, %2$s: closing a tag */
						esc_html__( 'Make sure your images are the right size for your theme. %1$sLearn more%2$s.', 'wp-smushit' ),
						'<a href="https://wpmudev.com/blog/smush-pagespeed-image-compression/" target="_blank">',
						'</a>'
					);
				endif;
				?>
			</li>
			<?php if ( ! $this->settings->get( 'resize' ) ) : ?>
				<li class="smush-recommendation-resize-original">
					<?php
					printf(
						/* translators: %1$s: opening a tag, %2$s: closing a tag */
						esc_html__( 'Enable %1$sResize Full Size Images%2$s to scale big images down to a reasonable size and save a ton of space.', 'wp-smushit' ),
						'<a href="#" class="wp-smush-resize-enable">',
						'</a>'
					);
					?>
				</li>
			<?php endif; ?>
		</ol>
	</div>
<?php endif; ?>

<?php
// This notice goes below the pagespeed recommendations in Free.
if ( ! $is_pro ) {
	$this->view( 'all-images-smushed-notice', array( 'all_done' => empty( $total_images_to_smush ) ), 'common' );
}
?>

<div class="wp-smush-bulk-wrapper sui-border-frame<?php echo empty( $total_images_to_smush ) ? ' sui-hidden' : ''; ?>">

	<div id="wp-smush-bulk-content">
		<?php WP_Smush::get_instance()->admin()->print_pending_bulk_smush_content( $total_images_to_smush, $resmush_count, $unsmushed_count ); ?>
	</div>

	<div id="wp-smush-all-button-container">
		<button type="button" class="wp-smush-all sui-button sui-button-blue" title="<?php esc_attr_e( 'Click to start Bulk Smushing images in Media Library', 'wp-smushit' ); ?>">
			<?php esc_html_e( 'BULK SMUSH NOW', 'wp-smushit' ); ?>
		</button>
	</div>

	<?php if ( ! $is_pro ) : ?>
		<div id="wp-smush-bulk-smush-upsell-row" class="sui-row">
			<div class="sui-col-sm-7">
				<h3><?php esc_html_e( 'Free Trial + 30% Discount for Smush users!', 'wp-smushit' ); ?></h3>
				<p>
					<?php
					if ( Smush\Core\Core::$max_free_bulk < $total_images_to_smush ) :
						printf(
							/* translators: 1. total count of images to smush, 2. opening 'strong' tag, 3. closing 'strong' tag. */
							esc_html__( 'Bulk smush %2$sall your %1$s images%3$s in one-click and get a %2$s30%% Welcome Discount%3$s just for Smush Free users!', 'wp-smushit' ),
							esc_html( $total_images_to_smush ),
							'<strong>',
							'</strong>'
						);
					else :
						esc_html_e( 'Get Smush Pro and bulk optimize every image you’ve ever added to your site with one click.', 'wp-smushit' );
					endif;
					?>
				</p>

				<a href="<?php echo esc_url( $bulk_upgrade_url ); ?>" class="sui-button sui-button-purple" target="_blank">
					<?php esc_html_e( 'Try pro absolutely free', 'wp-smushit' ); ?>
				</a>
				<p><small><?php esc_html_e( '*Discount applies to all annual plans.', 'wp-smushit' ); ?></small></p>
			</div>
			<div class="sui-col-sm-5">
				<ol class="sui-upsell-list">
					<li>
						<span class="sui-icon-check sui-sm" aria-hidden="true"></span>
						<?php esc_html_e( 'Fix Google PageSpeed image recommendations', 'wp-smushit' ); ?>
					</li>
					<li>
						<span class="sui-icon-check sui-sm" aria-hidden="true"></span>
						<?php esc_html_e( '10 GB Smush CDN', 'wp-smushit' ); ?>
					</li>
					<li>
						<span class="sui-icon-check sui-sm" aria-hidden="true"></span>
						<?php esc_html_e( '2x better compression', 'wp-smushit' ); ?>
					</li>
					<li>
						<span class="sui-icon-check sui-sm" aria-hidden="true"></span>
						<?php esc_html_e( 'Serve a next-gen format with WebP conversion', 'wp-smushit' ); ?>
					</li>
				</ol>
			</div>
		</div>
	<?php endif; ?>
</div>
