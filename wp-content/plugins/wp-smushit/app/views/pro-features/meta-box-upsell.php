<?php
/**
 * Pro features meta box upsell.
 *
 * @package WP_Smush
 */

use Smush\Core\Helper;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-upsell-notice sui-padding sui-padding-bottom__desktop--hidden">
	<div class="sui-upsell-notice__image" aria-hidden="true">
		<img class="sui-image" src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/smush-promo.png' ); ?>" alt="<?php esc_attr_e( 'Upgrade to Pro', 'wp-smushit' ); ?>" />
	</div>

	<div class="sui-upsell-notice__content">
		<div class="sui-notice sui-notice-purple">
			<div class="sui-notice-content">
				<div class="sui-notice-message">
					<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
					<p>
						<?php esc_html_e( 'Smush Pro gives you all these extra settings and absolutely no limits on smushing your images. Did we mention Smush Pro also gives you up to 2x better compression too? Try it all free with a WPMU DEV membership today!', 'wp-smushit' ); ?>
					</p>
					<p>
						<a href="<?php echo esc_url( Helper::get_url( 'smush-advanced-settings-upsell' ) ); ?>" target="_blank" class="sui-button sui-button-purple">
							<?php esc_html_e( 'Learn More', 'wp-smushit' ); ?>
						</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
