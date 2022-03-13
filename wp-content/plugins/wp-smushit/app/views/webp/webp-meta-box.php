<?php
/**
 * WebP meta box.
 *
 * @since 3.8.0
 * @package WP_Smush
 *
 * @var Smush\App\Abstract_Page $this  Page.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

$is_configured = WP_Smush::get_instance()->core()->mod->webp->get_is_configured_with_error_message();
?>

<p>
	<?php esc_html_e( "Locally serve WebP versions of your images to supported browsers, and gracefully fall back on JPEGs and PNGs for browsers that don't support WebP.", 'wp-smushit' ); ?>
</p>

<span class="sui-settings-label" style="font-size:13px;color:#333333;font-weight: bold;">
	<?php esc_html_e( 'Status', 'wp-smushit' ); ?>
</span>

<?php if ( true === $is_configured ) : ?>
	<div class="sui-notice sui-notice-success">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
				<p>
					<?php
					esc_html_e( 'WebP conversion is active and working well.', 'wp-smushit' );

					if ( isset( $_SERVER['WPMUDEV_HOSTED'] ) ) :
						if ( ! apply_filters( 'wpmudev_branding_hide_doc_link', false ) ) :
							esc_html_e( " Since your site is hosted with WPMU DEV, we've automatically preconfigured the conversion for you.", 'wp-smushit' );
						else :
							esc_html_e( ' Your hosting provider has preconfigured the conversion for you.', 'wp-smushit' );
						endif;
					endif;
					?>
				</p>
				<p>
					<?php
					printf(
						/* translators: 1. opening 'b' tag, 2. closing 'b' tag, 3. opening 'a' tag, 4. closing 'a' tag */
						esc_html__( '%1$sNote:%2$s You need to use the %3$sBulk Smush%4$s tool to convert all your images as WebP format. ', 'wp-smushit' ),
						'<b>',
						'</b>',
						! is_multisite() ? '<a href="' . esc_url( $this->get_url( 'smush-bulk' ) ) . '">' : '',
						( ! is_multisite() ? '</a>' : '' )
					);

					if ( ! is_multisite() ) :
						if ( ! $this->settings->get( 'auto' ) ) {
							printf(
								/* translators: %1$s - opening link tag, %2$s - </a> */
								esc_html__( 'You can also enable %3$sAutomatic Compression%2$s to convert newly uploaded image files automatically going forward.', 'wp-smushit' ),
								'<a href="' . esc_url( network_admin_url( 'admin.php?page=smush' ) ) . '">',
								'</a>',
								'<a href="' . esc_url( $this->get_url( 'smush-bulk' ) ) . '#column-auto">'
							);
						} else {
							esc_html_e( 'Newly uploaded images will be automatically converted to WebP format.', 'wp-smushit' );
						}
					endif;
					?>
				</p>
				<?php if ( $this->settings->get( 's3' ) && ! WP_Smush::get_instance()->core()->s3->setting_status() ) : ?>
					<p>
						<?php
						printf(
							/* translators: 1. opening 'b' tag, 2. closing 'b' tag */
							esc_html__( '%1$sNote:%2$s We noticed the Amazon S3 Integration is enabled. Offloaded images will not be served in WebP format, but Smush will still create local WebP copies of all images. If this is undesirable, please deactivate the WebP module below.', 'wp-smushit' ),
							'<b>',
							'</b>'
						);
						?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php else : ?>
	<div class="sui-notice sui-notice-warning">
		<div class="sui-notice-content">
			<div class="sui-notice-message">
				<i class="sui-notice-icon sui-icon-warning-alert sui-md" aria-hidden="true"></i>
				<p><?php echo esc_html( $is_configured ); ?></p>

				<?php if ( $this->settings->get( 's3' ) && ! WP_Smush::get_instance()->core()->s3->setting_status() ) : ?>
					<p>
						<?php
						printf(
							/* translators: 1. opening 'b' tag, 2. closing 'b' tag */
							esc_html__( '%1$sNote:%2$s We noticed the Amazon S3 Integration is enabled. Offloaded images will not be served in WebP format, but Smush will still create local WebP copies of all images. If this is undesirable, please deactivate the WebP module below.', 'wp-smushit' ),
							'<b>',
							'</b>'
						);
						?>
					</p>
				<?php endif; ?>

				<button id="smush-webp-toggle-wizard" type="button" class="sui-button" style="padding-left: 14px; margin-left: 26px;">
					<span class="sui-loading-text">
						<?php esc_html_e( 'Configure', 'wp-smushit' ); ?>
					</span>

					<span class="sui-icon-loader sui-loading" aria-hidden="true"></span>
				</button>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Supported Media Types', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'Here\'s a list of the media types that will be converted to WebP format.', 'wp-smushit' ); ?>
		</span>
	</div>
	<div class="sui-box-settings-col-2">
		<span class="smush-filename-extension smush-extension-jpg">
			<?php esc_html_e( 'jpg', 'wp-smushit' ); ?>
		</span>
		<span class="smush-filename-extension smush-extension-png">
			<?php esc_html_e( 'png', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php
			printf(
				/* translators: 1. opening 'a' tag to docs, 2. closing 'a' tag. */
				esc_html__( 'To verify if the JPG and PNG images are being served correctly as WebP files, please refer to our %1$sDocumentation%2$s.', 'wp-smushit' ),
				'<a href="https://wpmudev.com/docs/wpmu-dev-plugins/smush/#verifying-webp-output" target="_blank">',
				'</a>'
			);
			?>
		</span>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Revert WebP Conversion', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php esc_html_e( 'If your server storage space is full, use this feature to revert the WebP conversions by deleting all generated files. The files will fall back to normal PNGs or JPEGs once you delete them.', 'wp-smushit' ); ?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">
		<button
			type="button"
			class="sui-button sui-button-ghost"
			id="wp-smush-webp-delete-all-modal-open"
			data-modal-open="wp-smush-wp-delete-all-dialog"
			data-modal-close-focus="wp-smush-webp-delete-all-modal-open"
		>
			<span class="sui-loading-text">
				<i class="sui-icon-trash" aria-hidden="true"></i>
				<?php esc_html_e( 'Delete WebP Files', 'wp-smushit' ); ?>
			</span>
			<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
		</button>

		<span class="sui-description">
			<?php
			esc_html_e( 'This feature won’t delete the WebP files converted via CDN, only the files generated via the local WebP feature.', 'wp-smushit' );
			?>
		</span>
	</div>
</div>

<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="sui-settings-label">
			<?php esc_html_e( 'Deactivate', 'wp-smushit' ); ?>
		</span>

		<span class="sui-description">
			<?php esc_html_e( 'If you no longer require your images to be served in WebP format, you can disable this feature.', 'wp-smushit' ); ?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">

		<button class="sui-button sui-button-ghost" id="smush-toggle-webp-button" data-action="disable">
			<span class="sui-loading-text">
				<i class="sui-icon-power-on-off" aria-hidden="true"></i><?php esc_html_e( 'Deactivate', 'wp-smushit' ); ?>
			</span>
			<i class="sui-icon-loader sui-loading" aria-hidden="true"></i>
		</button>

		<span class="sui-description">
			<?php esc_html_e( 'Deactivation won’t delete existing WebP images.', 'wp-smushit' ); ?>
		</span>
	</div>
</div>
