<?php
/**
 * Settings row layout.
 *
 * @package WP_Smush
 *
 * @var \Smush\App\Abstract_Page $this
 *
 * @var string $name     Setting name.
 * @var bool   $value    Setting value.
 * @var bool   $disable  Disabled status.
 * @var bool   $upsell   Upsell status.
 */

use Smush\Core\Settings;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-box-settings-row <?php echo $upsell || $disable ? 'sui-disabled' : ''; ?>">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label <?php echo 'gutenberg' === $name ? 'sui-settings-label-with-tag' : ''; ?>">
			<?php echo esc_html( Settings::get_setting_data( $name, 'short-label' ) ); ?>
			<?php do_action( 'smush_setting_column_tag', $name ); ?>
		</span>
		<span class="sui-description">
			<?php echo wp_kses_post( Settings::get_setting_data( $name, 'desc' ) ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2" id="column-<?php echo esc_attr( $name ); ?>">
		<div class="sui-form-field">
			<label for="<?php echo esc_attr( $name ); ?>" class="sui-toggle">
				<?php if ( 'bulk' !== $name ) : ?>
					<input
						type="checkbox"
						id="<?php echo esc_attr( $name ); ?>"
						name="<?php echo esc_attr( $name ); ?>"
						aria-labelledby="<?php echo esc_attr( $name . '-label' ); ?>"
						aria-describedby="<?php echo esc_attr( $name . '-desc' ); ?>"
						<?php checked( $value, 1, true ); ?>
						<?php disabled( $disable ); ?>
					/>
					<span class="sui-toggle-slider" aria-hidden="true"></span>
					<span id="<?php echo esc_attr( $name . '-label' ); ?>" class="sui-toggle-label">
						<?php echo esc_html( Settings::get_setting_data( $name, 'label' ) ); ?>
					</span>
				<?php endif; ?>
				<!-- Print/Perform action in right setting column -->
				<?php do_action( 'smush_setting_column_right_inside', $name ); ?>
			</label>
			<?php do_action( 'smush_setting_column_right_additional', $name ); ?>
		</div>
		<!-- Print/Perform action in right setting column -->
		<?php do_action( 'smush_setting_column_right_outside', $name ); ?>
	</div>
</div>
