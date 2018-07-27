<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package mahjong-org.azurewebsites.net
 * @subpackage mahjong
 * @since Mahjong-ca 1.0
 */
?>

<div class="leftsidebar">
  <div class="topbox">
      <div class="search_c">
        <form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="text" class="field t" name="s" id="s" placeholder="<?php esc_attr_e( 'Search...', 'mahjong' ); ?>" />
		<input type="submit" class="submit b" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'mahjong' ); ?>" />
	</form>
      </div>
  <!--topbox end-->
  </div>
  <div class="infobox">
      
      <?php
	/* When we call the dynamic_sidebar() function, it'll spit out
	 * the widgets for that widget area. If it instead returns false,
	 * then the sidebar simply doesn't exist, so we'll hard-code in
	 * some default sidebar stuff just in case.
	 */
	if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : ?>

			<li id="search" class="widget-container widget_search">
				<?php get_search_form(); ?>
			</li>

			<li id="archives" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Archives', 'mahjong' ); ?></h3>
				<ul>
					<?php wp_get_archives( 'type=monthly' ); ?>
				</ul>
			</li>

			<li id="meta" class="widget-container">
				<h3 class="widget-title"><?php _e( 'Meta', 'mahjong' ); ?></h3>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<?php wp_meta(); ?>
				</ul>
			</li>

		<?php endif; // end primary widget area ?>

      
 
  <!--infobox end-->
  </div>
<!--leftsidebar end-->
</div>