<?php
/**
 * The Template for displaying all single posts.
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
	 <?php
		$category = get_the_category();
		$cat_parent = get_cat_name($category[1]->category_parent);
	if (!empty($cat_parent)) {
		echo $cat_parent;
	} else {
		echo $category[0]->cat_name;
	}
    ?>
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
        <div class="info_container article_detail clearfix">
        
		<?php get_template_part( 'loop', 'single' ); ?>

        <!--info_container end-->
        </div>
      <!--right_main end-->
      </div>
 <!--wrap end-->
 </div>
 <!--n_main_cont end-->
 </div>

<?php get_footer(); ?>
