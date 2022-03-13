<?php
/**
 * Pro features meta box.
 *
 * @package WP_Smush
 */

use Smush\Core\Helper;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="smush-pro-features-header">
	<div>
		<h2><?php esc_html_e( 'Optimize unlimited images with Smush Pro', 'wp-smushit' ); ?></h2>
		<p class="sui-description"><?php esc_html_e( 'Get Smush Pro and bulk optimize every image you’ve ever added to your site with one-click and fix your Google PageSpeed with the best image optimizer WordPress has ever known. Upgrade to unlock all Pro features today!', 'wp-smushit' ); ?></p>
		<div>
			<a href="<?php echo esc_url( Helper::get_url( 'smush-advanced-settings-video-button' ) ); ?>" target="_blank" class="sui-button sui-button-purple">
				<?php esc_html_e( 'Try Pro for Free', 'wp-smushit' ); ?>
			</a>
			<div>
				<button role="button" class="sui-button sui-button-ghost sui-button-purple" id="wistia-play-button">
					<span class="sui-icon-play" aria-hidden="true"></span> <?php esc_html_e( 'Watch Video', 'wp-smushit' ); ?>
				</button>
				<small><?php esc_html_e( 'less than 2 minutes', 'wp-smushit' ); ?></small>
			</div>
		</div>
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

<p class="sui-description" style="margin-bottom: 20px">
	<?php esc_html_e( 'Upgrading to Pro will get you the following benefits.', 'wp-smushit' ); ?>
</p>

<ol class="sui-upsell-list">
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'Super-smush lossy compression', 'wp-smushit' ); ?>
		<p class="sui-description">
			<?php esc_html_e( 'Optimize images 2x more than regular smushing and with no visible loss in quality using Smush’s intelligent multi-pass lossy compression.', 'wp-smushit' ); ?>
		</p>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'Streamline your images with Smush CDN', 'wp-smushit' ); ?>
		<p class="sui-description">
			<?php esc_html_e( 'Serve your images from our CDN from 45 blazing fast servers around the world. Enable automatic image sizing and WebP support and your website will be absolute flying.', 'wp-smushit' ); ?>
		</p>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'Serve next-gen WebP images (without Smush CDN)', 'wp-smushit' ); ?>
		<p class="sui-description">
			<?php esc_html_e( 'Rather not use Smush CDN? Our standalone WebP feature allows you to serve next-gen images that are around 26% smaller than JPG and PNG formats. All without sacrificing image quality.', 'wp-smushit' ); ?>
		</p>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'No limits, no restrictions', 'wp-smushit' ); ?>
		<p class="sui-description">
			<?php esc_html_e( 'Need a one-click bulk optimization solution for compressing your entire existing image library fast and easy? Pro unlocks unlimited bulk smushing, and lifts the image size limit from 5Mb to 32Mb.', 'wp-smushit' ); ?>
		</p>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'Auto-convert PNGs to JPEGs (lossy)', 'wp-smushit' ); ?>
		<p class="sui-description">
			<?php esc_html_e( 'When you compress a PNG, Smush will check if converting it to JPEG could further reduce its size, and do so if necessary.', 'wp-smushit' ); ?>
		</p>
	</li>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'NextGen Gallery Integration', 'wp-smushit' ); ?>
		<p class="sui-description">
			<?php esc_html_e( 'Allow smushing images directly through NextGen Gallery settings.', 'wp-smushit' ); ?>
		</p>
	</li>
</ol>

<?php if ( class_exists( '\C_NextGEN_Bootstrap' ) ) : ?>
	<li>
		<span class="sui-icon-check sui-md" aria-hidden="true"></span>
		<?php esc_html_e( 'NextGen Gallery Integration', 'wp-smushit' ); ?>
		<p class="sui-description">
			<?php esc_html_e( 'Allow smushing images directly through NextGen Gallery settings.', 'wp-smushit' ); ?>
		</p>
	</li>
<?php endif; ?>
