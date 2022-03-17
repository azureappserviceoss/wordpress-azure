<?php
/**
 * Onboarding modal.
 *
 * @since 3.1
 * @package WP_Smush
 */

use Smush\Core\Helper;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<script type="text/template" id="smush-onboarding" data-type="<?php echo WP_Smush::is_pro() ? 'pro' : 'free'; ?>" data-tracking="<?php echo ! is_multisite() ? 'true' : 'false'; ?>">
	<div class="sui-box-header sui-flatten sui-content-center sui-spacing-sides--90">
		<?php if ( ! apply_filters( 'wpmudev_branding_hide_branding', false ) ) : ?>
		<figure class="sui-box-banner" aria-hidden="true">
			<img src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/onboarding/graphic-onboarding-' ); ?>{{{ data.slide }}}.png"
				srcset="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/onboarding/graphic-onboarding-' ); ?>{{{ data.slide }}}.png 1x, <?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/onboarding/graphic-onboarding-' ); ?>{{{ data.slide }}}@2x.png 2x"
				alt="<?php esc_attr_e( 'Smush Onboarding Modal', 'wp-smushit' ); ?>" class="sui-image sui-image-center"
			>
		</figure>
		<?php endif; ?>

		<h3 class="sui-box-title sui-lg" id="smush-title-onboarding-dialog">
			<# if ( 'start' === data.slide ) { #>
			<?php

			/* translators: %s: current user name */
			printf( esc_html__( 'Hey, %s!', 'wp-smushit' ), esc_html( Helper::get_user_name() ) );
			?>
			<# } else if ( 'auto' === data.slide ) { #>
			<?php esc_html_e( 'Automatic Compression', 'wp-smushit' ); ?>
			<# } else if ( 'lossy' === data.slide ) { #>
			<?php esc_html_e( 'Advanced Compression', 'wp-smushit' ); ?>
			<# } else if ( 'strip_exif' === data.slide ) { #>
			<?php esc_html_e( 'EXIF Metadata', 'wp-smushit' ); ?>
			<# } else if ( 'original' === data.slide ) { #>
			<?php esc_html_e( 'Full Size Images', 'wp-smushit' ); ?>
			<# } else if ( 'lazy_load' === data.slide ) { #>
			<?php esc_html_e( 'Lazy Load', 'wp-smushit' ); ?>
			<# } else if ( 'usage' === data.slide ) { #>
			<?php esc_html_e( 'Usage Data', 'wp-smushit' ); ?>
			<# } #>
		</h3>

		<p class="sui-description" id="smush-description-onboarding-dialog">
			<# if ( 'start' === data.slide ) { #>
			<?php esc_html_e( 'Nice work installing Smush! Let’s get started by choosing how you want this plugin to work, and then let Smush do all the heavy lifting for you.', 'wp-smushit' ); ?>
			<# } else if ( 'auto' === data.slide ) { #>
			<?php esc_html_e( 'When you upload images to your site, Smush can automatically optimize and compress them for you saving you having to do this manually.', 'wp-smushit' ); ?>
			<# } else if ( 'lossy' === data.slide ) { #>
			<?php esc_html_e( 'Optimize images up to 2x more than regular smush with our multi-pass lossy compression.', 'wp-smushit' ); ?>
			<# } else if ( 'strip_exif' === data.slide ) { #>
			<?php esc_html_e( 'Photos often store camera settings in the file, i.e., focal length, date, time and location. Removing EXIF data reduces the file size. Note: it does not strip SEO metadata.', 'wp-smushit' ); ?>
			<# } else if ( 'original' === data.slide ) { #>
			<?php esc_html_e( 'You can also have Smush compress your original images - this is helpful if your theme serves full size images.', 'wp-smushit' ); ?>
			<# } else if ( 'lazy_load' === data.slide ) { #>
			<?php esc_html_e( 'This feature stops offscreen images from loading until a visitor scrolls to them. Make your page load faster, use less bandwidth and fix the “defer offscreen images” recommendation from a Google PageSpeed test.', 'wp-smushit' ); ?>
			<# } else if ( 'usage' === data.slide ) { #>
			<?php esc_html_e( 'Help us improve Smush by letting our product designers gain insight into what features need improvement. We don’t track any personalized data, it’s all basic stuff.', 'wp-smushit' ); ?>
			<# } #>
		</p>

	</div>

	<div class="sui-box-body sui-content-center sui-spacing-sides--0">
		<# if ( 'start' === data.slide ) { #>
		<a class="sui-button sui-button-blue sui-button-icon-right next" onclick="WP_Smush.onboarding.next(this)">
			<?php esc_html_e( 'Begin setup', 'wp-smushit' ); ?>
			<i class="sui-icon-chevron-right" aria-hidden="true"> </i>
		</a>
		<# } else { #>
		<div class="sui-box-selectors">
			<label for="{{{ data.slide }}}" class="sui-toggle">
				<input type="checkbox" id="{{{ data.slide }}}" aria-labelledby="{{{ data.slide }}}-label" <# if ( data.value ) { #>checked<# } #>>
				<span class="sui-toggle-slider" aria-hidden="true"> </span>
				<span id="{{{ data.slide }}}-label" class="sui-toggle-label">
					<# if ( 'auto' === data.slide ) { #>
					<?php esc_html_e( 'Automatically optimize new uploads', 'wp-smushit' ); ?>
					<# } else if ( 'lossy' === data.slide ) { #>
					<?php esc_html_e( 'Enable enhanced multi-pass lossy compression', 'wp-smushit' ); ?>
					<# } else if ( 'strip_exif' === data.slide ) { #>
					<?php esc_html_e( 'Strip my image metadata', 'wp-smushit' ); ?>
					<# } else if ( 'original' === data.slide ) { #>
					<?php esc_html_e( 'Compress my full size images', 'wp-smushit' ); ?>
					<# } else if ( 'lazy_load' === data.slide ) { #>
					<?php esc_html_e( 'Enable Lazy Loading', 'wp-smushit' ); ?>
					<# } else if ( 'usage' === data.slide ) { #>
					<?php esc_html_e( 'Allow usage data tracking', 'wp-smushit' ); ?>
					<# } #>
				</span>
			</label>
		</div>
		<# } #>

		<# if ( 'original' === data.slide ) { #>
		<p class="sui-description" style="padding: 0 90px">
			<?php esc_html_e( 'Note: By default we will store a copy of your original uploads just in case you want to revert in the future - you can turn this off at any time.', 'wp-smushit' ); ?>
		</p>
		<# } else if ( data.last ) { #>
		<button type="submit" class="sui-button sui-button-blue sui-button-icon-left" data-modal-close="">
			<i class="sui-icon-check" aria-hidden="true"> </i>
			<?php esc_html_e( 'Finish setup wizard', 'wp-smushit' ); ?>
		</button>
		<# } #>

		<# if ( 'start' !== data.slide && ! data.last ) { #>
		<a class="sui-button sui-button-gray next" onclick="WP_Smush.onboarding.next(this)">
			<?php esc_html_e( 'Next', 'wp-smushit' ); ?>
		</a>
		<# } #>

		<div class="smush-onboarding-arrows">
			<a href="#" class="previous <# if ( data.first ) { #>sui-hidden<# } #>" onclick="WP_Smush.onboarding.next(this)">
				<i class="sui-icon-chevron-left" aria-hidden="true"> </i>
			</a>
			<a href="#" class="next <# if ( data.last ) { #>sui-hidden<# } #>" onclick="WP_Smush.onboarding.next(this)">
				<i class="sui-icon-chevron-right" aria-hidden="true"> </i>
			</a>
		</div>
	</div>

	<div class="sui-box-footer sui-flatten sui-content-center">
		<div class="sui-box-steps sui-sm">
			<button onclick="WP_Smush.onboarding.goTo('start')" class="<# if ( 'start' === data.slide ) { #>sui-current<# } #>" <# if ( 'start' === data.slide ) { #>disabled<# } #>>
				<?php esc_html_e( 'First step', 'wp-smushit' ); ?>
			</button>
			<button onclick="WP_Smush.onboarding.goTo('auto')" class="<# if ( 'auto' === data.slide ) { #>sui-current<# } #>" <# if ( 'auto' === data.slide ) { #>disabled<# } #>>
				<?php esc_html_e( 'Automatic Compression', 'wp-smushit' ); ?>
			</button>
			<?php if ( WP_Smush::is_pro() ) : ?>
				<button onclick="WP_Smush.onboarding.goTo('lossy')" class="<# if ( 'lossy' === data.slide ) { #>sui-current<# } #>" <# if ( 'lossy' === data.slide ) { #>disabled<# } #>>
					<?php esc_html_e( 'Advanced Compression', 'wp-smushit' ); ?>
				</button>
			<?php endif; ?>
			<button onclick="WP_Smush.onboarding.goTo('strip_exif')" class="<# if ( 'strip_exif' === data.slide ) { #>sui-current<# } #>" <# if ( 'strip_exif' === data.slide ) { #>disabled<# } #>>
				<?php esc_html_e( 'EXIF Metadata', 'wp-smushit' ); ?>
			</button>
			<?php if ( WP_Smush::is_pro() ) : ?>
				<button onclick="WP_Smush.onboarding.goTo('original')" class="<# if ( 'original' === data.slide ) { #>sui-current<# } #>" <# if ( 'original' === data.slide ) { #>disabled<# } #>>
					<?php esc_html_e( 'Full Size Images', 'wp-smushit' ); ?>
				</button>
			<?php endif; ?>
			<button onclick="WP_Smush.onboarding.goTo('lazy_load')" class="<# if ( 'lazy_load' === data.slide ) { #>sui-current<# } #>" <# if ( 'lazy_load' === data.slide ) { #>disabled<# } #>>
				<?php esc_html_e( 'Lazy Load', 'wp-smushit' ); ?>
			</button>
			<?php if ( ! is_multisite() ) : ?>
				<button onclick="WP_Smush.onboarding.goTo('usage')" class="<# if ( 'usage' === data.slide ) { #>sui-current<# } #>" <# if ( 'usage' === data.slide ) { #>disabled<# } #>>
					<?php esc_html_e( 'Usage Data', 'wp-smushit' ); ?>
				</button>
			<?php endif; ?>
		</div>
	</div>
</script>

<div class="sui-modal sui-modal-md">
	<div
		role="dialog"
		id="smush-onboarding-dialog"
		class="sui-modal-content smush-onboarding-dialog"
		aria-modal="true"
		aria-labelledby="smush-title-onboarding-dialog"
		aria-describedby="smush-description-onboarding-dialog"
	>
		<div class="sui-box">
			<div id="smush-onboarding-content" aria-live="polite"></div>
			<input type="hidden" id="smush_quick_setup_nonce" name="_wpnonce" value="<?php echo wp_create_nonce( 'smush_quick_setup' ); ?>">
		</div>
		<button class="sui-modal-skip smush-onboarding-skip-link">
			<?php esc_html_e( 'Skip this, I’ll set it up later', 'wp-smushit' ); ?>
		</button>
	</div>
</div>
