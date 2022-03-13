<?php
/**
 * Integrations meta box
 *
 * @package WP_Smush
 *
 * @var array  $basic_features    Basic features array.
 * @var bool   $is_pro            Is PRO user or not.
 * @var array  $integration_group Integration group.
 * @var array  $settings          Settings array.
 *
 * @var Abstract_Page $this
 */

use Smush\App\Abstract_Page;
use Smush\Core\Helper;

if ( ! defined( 'WPINC' ) ) {
	die;
}

foreach ( $integration_group as $name ) {
	$disable = apply_filters( 'wp_smush_integration_status_' . $name, false ); // Disable setting.
	$upsell  = ! in_array( $name, $basic_features, true ) && ! $is_pro; // Gray out row, disable setting.
	$value   = $upsell || empty( $settings[ $name ] ) || $disable ? false : $settings[ $name ];
	do_action( 'wp_smush_render_setting_row', $name, $value, $disable, $upsell );
}
?>

<?php if ( ! $is_pro ) : ?>
	<div class="sui-upsell-notice sui-padding sui-padding-bottom__desktop--hidden">
		<div class="sui-upsell-notice__image" aria-hidden="true">
			<img
				class="sui-image"
				src="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/smush-graphic-integrations-upsell.png' ); ?>"
				srcset="<?php echo esc_url( WP_SMUSH_URL . 'app/assets/images/smush-graphic-integrations-upsell@2x.png' ); ?> 2x"
				alt="<?php esc_attr_e( 'Upgrade to Pro', 'wp-smushit' ); ?>"
			/>
		</div>

		<div class="sui-upsell-notice__content">
			<div class="sui-notice sui-notice-purple">
				<div class="sui-notice-content">
					<div class="sui-notice-message">
						<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
						<p>
							<?php
							printf( /* translators: %1$s - a href tag, %2$s - a href closing tag */
								esc_html__( 'Smush Pro supports hosting images on Amazon S3 and optimizing NextGen Gallery images directly through NextGen Gallery settings. %1$sTry it free%2$s with a WPMU DEV membership today!', 'wp-smushit' ),
								'<a href="' . esc_url( Helper::get_url( 'smush-nextgen-settings-upsell' ) ) . '" target="_blank" title="' . esc_html__( 'Try Smush Pro for FREE', 'wp-smushit' ) . '">',
								'</a>'
							);
							?>
						</p>
						<p>
							<a href="<?php echo esc_url( Helper::get_url( 'smush-nextgen-settings-upsell' ) ); ?>" target="_blank" class="sui-button sui-button-purple">
								<?php esc_html_e( 'Try Smush Pro for Free', 'wp-smushit' ); ?>
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
