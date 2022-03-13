<?php
/**
 * Meta box layout.
 *
 * @package WP_Smush
 * @since 3.7.2
 *
 * @var boolean $all_done Whether all images were already smushed.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="sui-notice sui-notice-success wp-smush-all-done<?php echo $all_done ? '' : ' sui-hidden'; ?>">
	<div class="sui-notice-content">
		<div class="sui-notice-message">
			<i class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></i>
			<p><?php esc_html_e( 'All attachments have been smushed. Awesome!', 'wp-smushit' ); ?></p>
		</div>
	</div>
</div>
