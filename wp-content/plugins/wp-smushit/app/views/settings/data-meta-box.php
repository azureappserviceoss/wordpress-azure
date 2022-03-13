<?php
/**
 * Data settings meta box.
 *
 * @since 3.0
 * @package WP_Smush
 *
 * @var bool $keep_data  Keep data during uninstall.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-box-settings-row">
	<p>
		<?php esc_html_e( 'Control what to do with your settings and data. Settings are each module’s configuration options. Data includes the compression savings, statistics and other pieces of information stored over time.', 'wp-smushit' ); ?>
	</p>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'Data', 'wp-smushit' ); ?></span>
		<span class="sui-description">
			<?php esc_html_e( 'Choose how you want us to handle your plugin data.', 'wp-smushit' ); ?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">
		<?php if ( $this->should_render( 'tools' ) ) : ?>
			<strong><?php esc_html_e( 'Restore Images', 'wp-smushit' ); ?></strong>
			<span class="sui-description">
				<?php esc_html_e( 'Made a mistake? No worries. We have a built-in bulk restore tool that will restore your image thumbnails to their original state.', 'wp-smushit' ); ?>
			</span>
			<span class="sui-description sui-margin-bottom">
				<?php
				printf( /* translators: %1$s - <a> link, %2$s - </a> */
					esc_html__( 'Navigate to %1$sTools%2$s to begin the process.', 'wp-smushit' ),
					'<a href="' . esc_url( esc_url( network_admin_url( 'admin.php?page=smush-tools' ) ) ) . '">',
					'</a>'
				);
				?>
			</span>
		<?php endif; ?>

		<strong><?php esc_html_e( 'Uninstallation', 'wp-smushit' ); ?></strong>
		<span class="sui-description">
			<?php esc_html_e( 'When you uninstall the plugin, what do you want to do with your settings? You can save them for next time, or wipe them back to factory settings.', 'wp-smushit' ); ?>
		</span>

		<div class="sui-side-tabs">
			<div class="sui-tabs-menu">
				<label for="keep_data-true" class="sui-tab-item <?php echo $keep_data ? 'active' : ''; ?>">
					<input type="radio" name="keep_data" value="1" id="keep_data-true" <?php checked( $keep_data ); ?>>
					<?php esc_html_e( 'Keep', 'wp-smushit' ); ?>
				</label>

				<label for="keep_data-false" class="sui-tab-item <?php echo $keep_data ? '' : 'active'; ?>">
					<input type="radio" name="keep_data" value="0" id="keep_data-false" <?php checked( ! $keep_data ); ?>>
					<?php esc_html_e( 'Delete', 'wp-smushit' ); ?>
				</label>
			</div>
		</div>

		<strong><?php esc_html_e( 'Reset Factory Settings', 'wp-smushit' ); ?></strong>
		<span class="sui-description">
			<?php esc_html_e( 'Need to revert back to the default settings? This button will instantly reset your settings to the defaults.', 'wp-smushit' ); ?>
		</span>

		<button class="sui-button sui-button-ghost" data-modal-open="reset-settings-dialog" data-modal-open-focus="reset-setting-confirm" data-modal-mask="true">
			<i class="sui-icon-undo" aria-hidden="true"></i>
			<?php esc_html_e( 'Reset Settings', 'wp-smushit' ); ?>
		</button>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label"><?php esc_html_e( 'API Status', 'wp-smushit' ); ?></span>
		<span class="sui-description">
			<?php esc_html_e( "If you're having issues with enabling pro features you can force the API to update your membership status here.", 'wp-smushit' ); ?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">
		<button type="button" class="sui-button sui-button-ghost" id="update-api-status">
			<span class="sui-loading-text">
				<i class="sui-icon-undo" aria-hidden="true"></i>
				<?php esc_html_e( 'Update API status', 'wp-smushit' ); ?>
			</span>
			<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
		</button>
	</div>
</div>
