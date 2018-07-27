<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package mahjong-org.azurewebsites.net
 * @subpackage mahjong
 * @since Mahjong-ca 1.0
 */

get_header(); ?>


<div class="n_t_box">
  <div class="wrap clearfix">
    <div class="text">
     <span>
	 <?php single_cat_title(''); ?>
    </span>
	<span style="display:none;"><?php foreach((get_the_category()) as $cat){echo '<small>'.$cat->category_nicename.'</small>';}?></span>
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
        <div class="info_container">
        <ul class="article_lists">
         <?php get_template_part( 'loop', 'category' );?>
        </ul>
        <!--info_container end-->
        </div>
      <!--right_main end-->
      </div>
      
	<!--wrap end-->
	</div>
<!--n_main_cont end-->
</div>



<?php get_footer(); ?>
