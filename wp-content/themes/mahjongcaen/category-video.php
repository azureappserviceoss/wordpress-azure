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
        <div class="info_container article_detail clearfix">
        <div class="picsbox">
        <ul class="pictures videosbox">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<!--获取特色图片-->
<?php
global $post;
$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
?>
<li>
<a href="<?php the_permalink() ?>" class="img_a" rel="bookmark"><img src="<?php echo $url ?>" /></a>
<span><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></span><div class="video_playbtn"><a  href="<?php the_permalink() ?>" rel="bookmark">play</a></div>
<small class="r"><?php edit_post_link('(Edit)', '', ''); ?></small>
        </li>
        <?php endwhile; else: ?>
<p style=" text-align: center; padding: 20px 0;"><?php _e('Sorry，Not Found Videos。'); ?></p>
<?php endif; ?>
<div><?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page'), __('Next Page &raquo;')); ?></div>
        </ul>
        </div>
        <!--info_container end-->
        </div>
      <!--right_main end-->
      </div>
      
	<!--wrap end-->
	</div>
<!--n_main_cont end-->
</div>



<?php get_footer(); ?>
