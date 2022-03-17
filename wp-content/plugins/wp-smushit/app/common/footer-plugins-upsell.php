<?php
/**
 * Plugins upsell in the footer.
 *
 * @package WP_Smush
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-row" id="sui-cross-sell-footer">
	<div><span class="sui-icon-plugin-2"></span></div>
	<h3><?php esc_html_e( 'Check out our other free wordpress.org plugins!', 'wp-smushit' ); ?></h3>
</div>
<div class="sui-row sui-cross-sell-modules">
	<div class="sui-col-md-4">
		<div class="sui-cross-1 sui-cross-hummingbird"><span></span></div>
		<div class="sui-box">
			<div class="sui-box-body">
				<h3><?php esc_html_e( 'Hummingbird Page Speed Optimization', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'Performance Tests, File Optimization & Compression, Page, Browser & Gravatar Caching, GZIP Compression, CloudFlare Integration & more.', 'wp-smushit' ); ?></p>
				<a href="<?php echo esc_url( 'https://wordpress.org/plugins/hummingbird-performance/' ); ?>" class="sui-button sui-button-ghost" target="_blank">
					<?php esc_html_e( 'View features', 'wp-smushit' ); ?> <i class="sui-icon-arrow-right"></i>
				</a>
			</div>
		</div>
	</div>
	<div class="sui-col-md-4">
		<div class="sui-cross-2 sui-cross-defender"><span></span></div>
		<div class="sui-box">
			<div class="sui-box-body">
				<h3><?php esc_html_e( 'Defender Security, Monitoring, and Hack Protection', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'Security Tweaks & Recommendations, File & Malware Scanning, Login & 404 Lockout Protection, Two-Factor Authentication & more.', 'wp-smushit' ); ?></p>
				<a href="<?php echo esc_url( 'https://wordpress.org/plugins/defender-security/' ); ?>" class="sui-button sui-button-ghost" target="_blank">
					<?php esc_html_e( 'View features', 'wp-smushit' ); ?> <i class="sui-icon-arrow-right"></i>
				</a>
			</div>
		</div>
	</div>
	<div class="sui-col-md-4">
		<div class="sui-cross-3 sui-cross-smartcrawl"><span></span></div>
		<div class="sui-box">
			<div class="sui-box-body">
				<h3><?php esc_html_e( 'SmartCrawl Search Engine Optimization', 'wp-smushit' ); ?></h3>
				<p><?php esc_html_e( 'Customize Titles & Metadata, OpenGraph, Twitter & Pinterest Support, Auto-Keyword Linking, SEO & Readability Analysis, Sitemaps, URL Crawler & more.', 'wp-smushit' ); ?></p>
				<a href="<?php echo esc_url( 'https://wordpress.org/plugins/smartcrawl-seo/' ); ?>" class="sui-button sui-button-ghost" target="_blank">
					<?php esc_html_e( 'View features', 'wp-smushit' ); ?> <i class="sui-icon-arrow-right"></i>
				</a>
			</div>
		</div>
	</div>
</div>
<div class="sui-cross-sell-bottom">
	<?php
	$site_url = add_query_arg(
		array(
			'utm_source'   => 'smush',
			'utm_medium'   => 'plugin',
			'utm_campaign' => 'smush_footer_upsell_notice',
		),
		esc_url( 'https://wpmudev.com' )
	);
	?>
	<h3><?php esc_html_e( 'Your All-in-One WordPress Platform', 'wp-smushit' ); ?></h3>
	<p><?php esc_html_e( 'Pretty much everything you need for developing and managing WordPress based websites, and then some.', 'wp-smushit' ); ?></p>
	<a class="sui-button sui-button-green" href="<?php echo esc_url( $site_url ); ?>" id="dash-uptime-update-membership" target="_blank">
		<?php esc_html_e( 'Learn more', 'wp-smushit' ); ?>
	</a>
	<img class="sui-image" src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/dev-team.png' ); ?>" srcset="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/dev-team@2x.png' ); ?> 2x" alt="<?php esc_attr_e( 'Try pro features for free!', 'wp-smushit' ); ?>">
</div>