<?php
/**
 * The push sidebar.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package freddo
 */
 
if ( ! is_active_sidebar( 'sidebar-push' ) ) {
	return;
}
?>
<div class="opacityBox"></div>
<aside id="tertiary" class="widget-area nano">
	<div class="close-hamburger">
		<div class="close-ham-inner"></div>
	</div>
	<div class="nano-content"><?php dynamic_sidebar( 'sidebar-push' ); ?></div>
</aside><!-- #secondary -->