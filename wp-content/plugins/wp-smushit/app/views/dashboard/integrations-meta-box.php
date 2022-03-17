<?php
/**
 * Integrations meta box.
 *
 * @since 3.8.6
 * @package WP_Smush
 *
 * @var array  $basic_features  Basic features.
 * @var array  $fields          Available integration settings.
 * @var bool   $is_pro          Pro status.
 * @var array  $settings        Settings array.
 * @var string $upsell_url      Upsell link.
 */

use Smush\Core\Settings;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<p>
	<?php esc_html_e( 'Integrate with powerful third-party providers and make compression even easier.', 'wp-smushit' ); ?>
</p>

<div class="sui-box-settings-row sui-flushed sui-no-padding">
	<table class="sui-table sui-table-flushed">
		<thead>
		<tr>
			<th><?php esc_html_e( 'Available Integrations', 'wp-smushit' ); ?></th>
			<th><?php esc_html_e( 'Status', 'wp-smushit' ); ?></th>
		</tr>
		</thead>

		<tbody>
		<?php foreach ( $fields as $name ) : ?>
			<?php
			$disable = apply_filters( 'wp_smush_integration_status_' . $name, false ); // Disable setting.
			$upsell  = ! in_array( $name, $basic_features, true ) && ! $is_pro; // Gray out row, disable setting.
			$value   = ! ( $upsell || empty( $settings[ $name ] ) || $disable ) && $settings[ $name ];
			?>
			<tr class="<?php echo $upsell ? 'smush-disabled-table-row' : ''; ?>">
				<td class="sui-table-item-title">
					<?php echo esc_html( Settings::get_setting_data( $name, 'short-label' ) ); ?>
				</td>
				<td>
					<?php if ( $upsell ) : ?>
						<span class="sui-tag sui-tag-purple sui-tag-sm"><?php esc_html_e( 'PRO', 'wp-smushit' ); ?></span>
					<?php elseif ( $value ) : ?>
						<span class="sui-tag sui-tag-green"><?php esc_html_e( 'Active', 'wp-smushit' ); ?></span>
					<?php else : ?>
						<span class="sui-tag"><?php esc_html_e( 'Inactive', 'wp-smushit' ); ?></span>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php if ( ! $is_pro ) : ?>
	<p><?php esc_html_e( 'Smush Pro supports hosting images on Amazon S3 and optimizing NextGen Gallery images directly through NextGen Gallery settings.', 'wp-smushit' ); ?></p>
	<p>
		<?php
		printf( /* translators: %1$s - opening <a>, %2$s - closing </a> */
			esc_html__( '%1$sTry it free%2$s with a WPMU DEV membership today!', 'wp-smushit' ),
			'<a href="' . esc_url( $upsell_url ) . '" target="_blank" class="smush-upsell-link">',
			'</a>'
		);
		?>
	</p>
<?php endif; ?>

<a href="<?php echo esc_url( $this->get_url( 'smush-integrations' ) ); ?>" class="sui-button sui-button-ghost">
	<span class="sui-icon-wrench-tool" aria-hidden="true"></span>
	<?php esc_html_e( 'Configure', 'wp-smushit' ); ?>
</a>
