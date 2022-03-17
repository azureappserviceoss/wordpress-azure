<?php
/**
 * Meta box layout.
 *
 * @package WP_Smush
 *
 * @var Abstract_Page $this
 *
 * @var array  $args             Array of arguments.
 * @var array  $callback         Callback for meta box content.
 * @var array  $callback_footer  Callback for meta box footer.
 * @var array  $callback_header  Callback for meta box header.
 * @var string $id               Meta box DOM ID.
 * @var string $orig_id          Meta box code ID.
 * @var string $title            Meta box title.
 */

use Smush\App\Abstract_Page;

if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div id="smush-box-<?php echo esc_attr( $id ); ?>" class="sui-<?php echo esc_attr( $id ); ?> <?php echo esc_attr( $args['box_class'] ); ?>">
	<?php if ( is_callable( $callback_header ) ) : ?>
		<div class="<?php echo esc_attr( $args['box_header_class'] ); ?>">
			<?php call_user_func( $callback_header ); ?>
		</div>
	<?php elseif ( $this->view_exists( $orig_id . '/meta-box-header' ) ) : ?>
		<div class="<?php echo esc_attr( $args['box_header_class'] ); ?>">
			<?php $this->view( $orig_id . '/meta-box-header', compact( 'title' ) ); ?>
		</div>
	<?php elseif ( $title ) : ?>
		<div class="<?php echo esc_attr( $args['box_header_class'] ); ?>">
			<h3 class="sui-box-title"><?php echo esc_html( $title ); ?></h3>
		</div>
	<?php endif; ?>

	<?php if ( $args['box_content_class'] ) : ?>
		<div class="<?php echo esc_attr( $args['box_content_class'] ); ?>">
			<?php if ( is_callable( $callback ) ) : ?>
				<?php call_user_func( $callback ); ?>
			<?php else : ?>
				<?php $this->view( $orig_id . '-meta-box' ); ?>
			<?php endif; ?>
		</div>
	<?php elseif ( is_callable( $callback ) ) : ?>
		<?php call_user_func( $callback ); ?>
	<?php else : ?>
		<?php $this->view( $orig_id . '-meta-box' ); ?>
	<?php endif; ?>

	<?php if ( is_callable( $callback_footer ) ) : ?>
		<div class="<?php echo esc_attr( $args['box_footer_class'] ); ?>">
			<?php call_user_func( $callback_footer ); ?>
		</div>
	<?php elseif ( $this->view_exists( $orig_id . '/meta-box-footer' ) ) : ?>
		<div class="<?php echo esc_attr( $args['box_footer_class'] ); ?>">
			<?php $this->view( $orig_id . '/meta-box-footer' ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! WP_Smush::is_pro() && $this->view_exists( $orig_id . '/meta-box-upsell' ) ) : ?>
		<?php $this->view( $orig_id . '/meta-box-upsell' ); ?>
	<?php endif; ?>

	<?php // Allows you to output any content within the stats box at the end. ?>
	<?php do_action( 'wp_smush_after_stats' ); ?>
</div><!-- end box-<?php echo esc_attr( $id ); ?> -->
