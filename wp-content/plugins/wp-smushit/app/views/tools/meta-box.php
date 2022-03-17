<?php
/**
 * Tools meta box.
 *
 * @since 3.2.1
 * @package WP_Smush
 *
 * @var bool  $detection      Detection settings.
 * @var int   $backups_count
 *
 * @var Smush\App\Abstract_Page $this  Page.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

do_action( 'wp_smush_render_setting_row', 'detection', $detection );

?>
<div class="sui-box-settings-row">
	<div class="sui-box-settings-col-1">
		<span class="<?php echo WP_Smush::is_pro() ? 'sui-settings-label' : 'sui-settings-label-with-tag'; ?>">
			<?php esc_html_e( 'Bulk restore', 'wp-smushit' ); ?>
		</span>
		<span class="sui-description">
			<?php
				esc_html_e( 'Made a mistake? Use this feature to restore your image thumbnails to their original state.', 'wp-smushit' );
			?>
		</span>
	</div>

	<div class="sui-box-settings-col-2">
		<button type="button" class="sui-button sui-button-ghost" onclick="WP_Smush.restore.init()" <?php disabled( ! $backups_count ); ?>>
			<i class="sui-icon-undo" aria-hidden="true"></i>
			<?php esc_html_e( 'Restore Thumbnails', 'wp-smushit' ); ?>
		</button>
		<span class="sui-description">
			<?php
			printf( /* translators: %1$s - a tag, %2$s - closing a tag */
				wp_kses( 'This feature regenerates thumbnails using your original uploaded images. If “%1$sCompress Uploaded Images%2$s” is enabled, your thumbnails can still be regenerated, but the quality will be impacted by the compression of your uploaded images.', 'wp-smushit' ),
				'<a href="' . esc_url( $this->get_url( 'smush-bulk' ) ) . '#original-label">',
				'</a>'
			);
			?>
		</span>
		<span class="sui-description">
			<?php
			printf( /* translators: %1$s - a tag, %2$s - closing a tag */
				esc_html__( 'Note: “%1$sBackup Uploaded Images%2$s” must be enabled in order to bulk restore your images. ', 'wp-smushit' ),
				'<a href="' . esc_url( $this->get_url( 'smush-bulk' ) ) . '#backup-label">',
				'</a>'
			)
			?>
		</span>
	</div>
</div>
