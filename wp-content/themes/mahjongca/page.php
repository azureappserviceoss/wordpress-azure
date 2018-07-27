<?php
/**
 * The template for displaying all pages.
 *
 * @package mahjong-org.azurewebsites.net
 * @subpackage mahjong
 * @since Mahjong-ca 1.0
 */

get_header(); ?>

<div class="n_t_box">
  <div class="wrap clearfix">
    <div class="text">
     <span><?php the_title(); ?></span>
	 <small><?php echo the_slug(); ?></small>
    </div>
  </div>
<!--n_t_box end-->
</div>

<div class="n_main_cont clearfix">
      <div class="wrap">
      <?php get_sidebar(); ?>
      
      <div class="right_main">
        <div class="s_menubox">
         <?php if (function_exists('dimox_breadcrumbs')) dimox_breadcrumbs(); ?> 
        <!--s_menubox end-->
        </div>
        <div class="info_container article_detail clearfix">
        
		<?php get_template_part( 'loop', 'page' ); ?>

        <!--info_container end-->
        </div>
      <!--right_main end-->
      </div>
  <!--wrap end-->
  </div>
 <!--n_main_cont end-->
 </div>

<?php get_footer(); ?>
