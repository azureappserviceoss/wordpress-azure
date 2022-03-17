<?php
/**
 * Smush PRO upgrade page.
 *
 * @since 3.2.3
 * @package WP_Smush
 */

$upgrade_url = add_query_arg(
	array(
		'utm_source' => 'smush',
		'utm_medium' => 'plugin',
	),
	'https://wpmudev.com/project/wp-smush-pro/'
);

?>

<div class="sui-upgrade-page">
	<div class="sui-upgrade-page-header">
		<div class="sui-upgrade-page__container">
			<div class="sui-upgrade-page-header__content">
				<h1><?php esc_html_e( 'Upgrade to Smush Pro', 'wp-smushit' ); ?></h1>
				<p><?php esc_html_e( 'Get Smush Pro and bulk optimize every image you’ve ever added to your site with one-click. Save 2x more with lossy Super-Smush. Serve stunning, high-quality images from 45 locations around the globe with our blazing-fast CDN.', 'wp-smushit' ); ?></p>
				<p><?php esc_html_e( 'Automatically compress and resize huge photos without any size limitations. Double your savings and fix your Google PageSpeed with the best image optimizer WordPress has ever known.', 'wp-smushit' ); ?></p>
				<a href="<?php echo esc_url( add_query_arg( 'utm_campaign', 'smush_propage_topbutton', $upgrade_url ) ); ?>" class="sui-button sui-button-lg sui-button-purple" target="_blank">
					<?php esc_html_e( 'Try Smush Pro for Free', 'wp-smushit' ); ?>
				</a>
				<div class="sui-reviews">
					<span class="sui-reviews__stars"></span>
					<div class="sui-reviews__rating"><span class="sui-reviews-rating">-</span> / <?php esc_html_e( '5.0 rating from', 'wp-smushit' ); ?> <span class="sui-reviews-customer-count">-</span> <?php esc_html_e( 'customers', 'wp-smushit' ); ?></div>
					<a class="sui-reviews__link" href="https://www.reviews.io/company-reviews/store/wpmudev-org" target="_blank">
						Reviews.io<i class="sui-icon-arrow-right" aria-hidden="true"></i>
					</a>
				</div>
			</div>

			<div class="sui-upgrade-page-header__image"></div>
		</div>
	</div>
	<div class="smush-stats">
		<div class="smush-stats-item">
			<div><span>65.83</span> <?php esc_html_e( 'Billion', 'wp-smushit' ); ?></div>
			<div class="smush-stats-description"><?php esc_html_e( 'Images Optimized', 'wp-smushit' ); ?></div>
		</div>
		<div class="smush-stats-item">
			<div><span>726,410</span></div>
			<div class="smush-stats-description"><?php esc_html_e( 'Sites Optimized', 'wp-smushit' ); ?></div>
		</div>
		<div class="smush-stats-item">
			<div><span>287,038</span> GB</div>
			<div class="smush-stats-description"><?php esc_html_e( 'Total Savings', 'wp-smushit' ); ?></div>
		</div>
	</div>

	<div class="sui-upgrade-page-features">
		<div class="sui-upgrade-page-features__header" style="margin-top: 70px">
			<h2><?php esc_html_e( 'Optimize unlimited images with Smush Pro', 'wp-smushit' ); ?></h2>
			<p><?php esc_html_e( 'Learn why Smush Pro is the best image optimization plugin.', 'wp-smushit' ); ?></p>
			<div class="thumbnail-container">
				<img src="https://wpmudev.com/wp-content/themes/wpmudev-2015-1/assets/img/projects/Smush-Thumbnail@2x.png?v=2" alt="<?php esc_attr_e( 'Play', 'wp-smushit' ); ?>" id="wistia-play-button" role="button">
			</div>
			<span id="wistia_oegnwrdag1"></span>
			<script>
				document.addEventListener("DOMContentLoaded", function() {
					var trigger = document.getElementById("wistia-play-button");

					window.wistiaSmushEmbed = null;
					window.wistiaInit = function(Wistia) {
						window.wistiaSmushEmbed = Wistia.embed("oegnwrdag1", {
							version: "v2",
							videoWidth: 1280,
							videoHeight: 720,
							playerColor: "14485f",
							videoQuality: "hd-only",
							popover: true,
							popoverPreventScroll: true,
							popoverContent: 'html'
						});
					};

					if (trigger) {
						trigger.addEventListener("click", function(e) {
							e.preventDefault();
							if (window.wistiaSmushEmbed) {
								window.wistiaSmushEmbed.play();
							}
						});
					}
				});
			</script>
		</div>
	</div>

	<div class="sui-upgrade-page-features">
		<div class="sui-upgrade-page-features__header">
			<h2><?php esc_html_e( 'Pro Features', 'wp-smushit' ); ?></h2>
			<p><?php esc_html_e( 'Upgrading to Pro will get you the following benefits.', 'wp-smushit' ); ?></p>
		</div>
	</div>

	<div class="sui-upgrade-page__container">
		<div class="sui-upgrade-page-features__items">
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-unlock" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'No limits, no restrictions', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'Need a one-click bulk optimization solution for compressing your entire existing image library fast and easy? Pro unlocks unrestricted bulk smushing, and lifts the image size limit from 5MB to completely unlimited.', 'wp-smushit' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-graph-line" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'More than double your savings with Super-Smush', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'Smush Pro users get unlimited access to Super-Smush advanced multi-pass lossy compression increasing savings by more than 2x on average without any visible loss in quality.', 'wp-smushit' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-web-globe-world" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'Streamline your images with Smush CDN', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'Use the blazing-fast Smush image CDN to automatically resize your files to the perfect size and serve WebP files 25% smaller than PNG and JPG compression from 45 locations around the globe.', 'wp-smushit' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-wand-magic" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'Auto-convert PNGs to JPEGs (lossy)', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( "Smush looks for additional savings and automatically converts PNG files to JPEG if it will further reduce the size without a visible drop in quality. Now that's smart image compression.", 'wp-smushit' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-photo-picture" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'Serve next-gen WebP images (without Smush CDN)', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( "Prefer not to use Smush CDN? Our standalone WebP feature allows you to serve next-gen images that are around 26% smaller than JPG and PNG formats. All without sacrificing image quality. You can also gracefully fallback to the older image formats for browsers that aren't compatible.", 'wp-smushit' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-gdpr" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'Premium WordPress plugins', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'You’ll get our full suite of premium WordPress plugins, making sure from Security to Backups to Marketing and SEO you’ve got all the WordPress solutions you can possible need. You get unlimited usage on unlimited sites, and can join the millions using our plugins.', 'wp-smushit' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-graph-bar" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'White label automated reporting', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'Customize, style, schedule and send white label client and developer reports in just a few clicks with embedded performance, security, SEO, and analytics data.', 'wp-smushit' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-hub" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'The Hub - Manage unlimited WordPress sites', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'You can manage unlimited WordPress sites with automated updates, backups, security, and performance! – checks, all in one place. All of this can be white labeled for your clients, and you even get our 24/7 live WordPress support.', 'wp-smushit' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-help-support" aria-hidden="true"></i>
				<h3><?php esc_html_e( '24/7 live WordPress support', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'We can’t stress this enough: our outstanding WordPress support is available with live chat 24/7, and we’ll help you with absolutely any WordPress issue – not just our products. It’s an expert WordPress team on call for you, whenever you need them.', 'wp-smushit' ); ?></p>
			</div>
			<div class="sui-upgrade-page-features__item">
				<i class="sui-icon-wpmudev-logo" aria-hidden="true"></i>
				<h3><?php esc_html_e( 'The WPMU DEV Guarantee', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( "You'll be delighted with Smush Pro. You've got a no risk free trial to test the WPMU DEV Membership, and if you continue but change your mind, you can cancel any time.", 'wp-smushit' ); ?></p>
			</div>
		</div>
	</div>
	<div class="sui-upgrade-page-cta">
		<div class="sui-upgrade-page-cta__inner">
			<h2><?php esc_html_e( 'Join 936,586 Happy Members', 'wp-smushit' ); ?></h2>
			<p><?php esc_html_e( "97% of customers are happy with WPMU DEV's service, and it’s a great time to join them: as a Smush user you’ll get a free trial, so you can see what all the fuss is about.", 'wp-smushit' ); ?></p>
			<a href="<?php echo esc_url( add_query_arg( 'utm_campaign', 'smush_propage_bottombutton', $upgrade_url ) ); ?>" class="sui-button sui-button-lg sui-button-purple" target="_blank">
				<?php esc_html_e( 'Get Smush Pro, and get a faster WordPress', 'wp-smushit' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( 'utm_campaign', 'smush_propage_bottombutton', $upgrade_url ) ); ?>" target="_blank">
				<?php esc_html_e( 'Try it for free', 'wp-smushit' ); ?>
			</a>
		</div>
	</div>
</div>

<div class="sui-footer">
	<?php esc_html_e( 'Made with', 'wp-smushit' ); ?> <i class="sui-icon-heart" aria-hidden="true"></i> <?php esc_html_e( 'by WPMU DEV', 'wp-smushit' ); ?>
</div>

<ul class="sui-footer-nav">
	<li><a href="https://profiles.wordpress.org/wpmudev#content-plugins" target="_blank">
			<?php esc_html_e( 'Free Plugins', 'wp-smushit' ); ?>
		</a></li>
	<li><a href="https://wpmudev.com/roadmap/" target="_blank">
			<?php esc_html_e( 'Roadmap', 'wp-smushit' ); ?>
		</a></li>
	<li><a href="https://wordpress.org/support/plugin/wp-smushit" target="_blank">
			<?php esc_html_e( 'Support', 'wp-smushit' ); ?>
		</a></li>
	<li><a href="https://wpmudev.com/docs/" target="_blank">
			<?php esc_html_e( 'Docs', 'wp-smushit' ); ?>
		</a></li>
	<li><a href="https://wpmudev.com/hub-welcome/" target="_blank">
			<?php esc_html_e( 'The Hub', 'wp-smushit' ); ?>
		</a></li>
	<li><a href="https://wpmudev.com/terms-of-service/" target="_blank">
			<?php esc_html_e( 'Terms of Service', 'wp-smushit' ); ?>
		</a></li>
	<li><a href="https://incsub.com/privacy-policy/" target="_blank">
			<?php esc_html_e( 'Privacy Policy', 'wp-smushit' ); ?>
		</a></li>
</ul>
