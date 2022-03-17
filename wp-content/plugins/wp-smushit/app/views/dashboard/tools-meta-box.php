<?php
/**
 * Tools meta box.
 *
 * @since 3.8.6
 * @package WP_Smush
 *
 * @var bool $is_resize_detection  Image resize detection module status.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<p>
	<?php esc_html_e( 'Use Tools for extra configurations.', 'wp-smushit' ); ?>
</p>

<div class="sui-box-settings-row sui-flushed sui-no-padding">
	<table class="sui-table sui-table-flushed">
		<thead>
		<tr>
			<th><?php esc_html_e( 'Available Tools', 'wp-smushit' ); ?></th>
			<th><?php esc_html_e( 'Status', 'wp-smushit' ); ?></th>
		</tr>
		</thead>

		<tbody>
		<tr>
			<td class="sui-table-item-title">
				<?php esc_html_e( 'Image Resize Detection', 'wp-smushit' ); ?>
			</td>
			<td>
				<?php if ( $is_resize_detection ) : ?>
					<span class="sui-tag sui-tag-green"><?php esc_html_e( 'Active', 'wp-smushit' ); ?></span>
				<?php else : ?>
					<span class="sui-tag"><?php esc_html_e( 'Inactive', 'wp-smushit' ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		</tbody>
	</table>
</div>

<a href="<?php echo esc_url( $this->get_url( 'smush-tools' ) ); ?>" class="sui-button sui-button-ghost">
	<span class="sui-icon-wrench-tool" aria-hidden="true"></span>
	<?php esc_html_e( 'View Tools', 'wp-smushit' ); ?>
</a>
