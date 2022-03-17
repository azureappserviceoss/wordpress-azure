<?php
/**
 * Permissions settings meta box.
 *
 * @since 3.0
 * @package WP_Smush
 *
 * @var mixed  $networkwide  Network wide settings.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Subsite Controls', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'By default, subsites will inherit your network settings. Choose which modules you want to allow subsite admins to override.', 'wp-smushit' ); ?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">
		<div class="sui-side-tabs sui-tabs">
			<div data-tabs>
				<?php $selected = is_array( $networkwide ) ? 'custom' : $networkwide; ?>
				<label for="access-none" class="sui-tab-item <?php echo ! $networkwide ? 'active' : ''; ?>">
					<input type="radio" name="wp-smush-subsite-access" value="0" id="access-none" <?php checked( $selected, false ); ?>>
					<?php esc_html_e( 'None', 'wp-smushit' ); ?>
				</label>
				<label for="access-all" class="sui-tab-item <?php echo '1' === $networkwide ? 'active' : ''; ?>">
					<input type="radio" name="wp-smush-subsite-access" value="1" id="access-all" <?php checked( $selected, '1' ); ?>>
					<?php esc_html_e( 'All', 'wp-smushit' ); ?>
				</label>
				<label for="access-custom" class="sui-tab-item <?php echo is_array( $networkwide ) ? 'active' : ''; ?>">
					<input type="radio" name="wp-smush-subsite-access" value="custom" id="access-custom" <?php checked( $selected, 'custom' ); ?>>
					<?php esc_html_e( 'Custom', 'wp-smushit' ); ?>
				</label>
			</div>

			<div data-panes>
				<div class="sui-notice sui-notice-info <?php echo ! $networkwide ? 'active' : ''; ?>">
					<div class="sui-notice-content">
						<div class="sui-notice-message">
							<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
							<p><?php esc_html_e( "Subsite admins can't override any module settings and will always inherit your network settings.", 'wp-smushit' ); ?></p>
						</div>
					</div>
				</div>
				<div class="sui-notice sui-notice-info <?php echo '1' === $networkwide ? 'active' : ''; ?>">
					<div class="sui-notice-content">
						<div class="sui-notice-message">
							<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
							<p><?php esc_html_e( 'Subsite admins can override all module settings.', 'wp-smushit' ); ?></p>
						</div>
					</div>
				</div>
				<div class="sui-tab-boxed <?php echo is_array( $networkwide ) ? 'active' : ''; ?>">
					<p class="sui-description">
						<?php esc_html_e( 'Choose which modules settings subsite admins have access to.', 'wp-smushit' ); ?>
					</p>

					<label class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm">
						<input type="checkbox" id="module-bulk" name="wp-smush-access[]" value="bulk" <?php checked( ! is_array( $networkwide ) || in_array( 'bulk', $networkwide, true ) ); ?>>
						<span aria-hidden="true">&nbsp;</span>
						<span><?php esc_html_e( 'Bulk Smush', 'wp-smushit' ); ?></span>
					</label>
					<label class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm">
						<input type="checkbox" id="module-integrations" name="wp-smush-access[]" value="integrations" <?php checked( ! is_array( $networkwide ) || in_array( 'integrations', $networkwide, true ) ); ?>>
						<span aria-hidden="true">&nbsp;</span>
						<span><?php esc_html_e( 'Integrations', 'wp-smushit' ); ?></span>
					</label>
					<label class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm">
						<input type="checkbox" id="module-lazy_load" name="wp-smush-access[]" value="lazy_load" <?php checked( ! is_array( $networkwide ) || in_array( 'lazy_load', $networkwide, true ) ); ?>>
						<span aria-hidden="true">&nbsp;</span>
						<span><?php esc_html_e( 'Lazy Load', 'wp-smushit' ); ?></span>
					</label>
					<label class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm">
						<input type="checkbox" id="module-cdn" name="wp-smush-access[]" value="cdn" <?php checked( ! is_array( $networkwide ) || in_array( 'cdn', $networkwide, true ) ); ?>>
						<span aria-hidden="true">&nbsp;</span>
						<span><?php esc_html_e( 'CDN', 'wp-smushit' ); ?></span>
					</label>
					<label class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm">
						<input type="checkbox" id="module-tools" name="wp-smush-access[]" value="tools" <?php checked( ! is_array( $networkwide ) || in_array( 'tools', $networkwide, true ) ); ?>>
						<span aria-hidden="true">&nbsp;</span>
						<span><?php esc_html_e( 'Tools', 'wp-smushit' ); ?></span>
					</label>
					<?php // Don't display if Dashboard's whitelabel is hiding documentation. ?>
					<?php if ( ! apply_filters( 'wpmudev_branding_hide_doc_link', false ) ) : ?>
						<label class="sui-checkbox sui-checkbox-stacked sui-checkbox-sm">
							<input type="checkbox" id="module-tutorials" name="wp-smush-access[]" value="tutorials" <?php checked( ! is_array( $networkwide ) || in_array( 'tutorials', $networkwide, true ) ); ?>>
							<span aria-hidden="true">&nbsp;</span>
							<span><?php esc_html_e( 'Tutorials', 'wp-smushit' ); ?></span>
						</label>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>