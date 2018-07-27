<?php
/**
 * The template for displaying 404 pages (Not Found).
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
	<?php foreach((get_the_category()) as $cat){echo '<small>'.$cat->category_nicename.'</small>';}?>
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
             
             
             <h1 class="entry-title" style="font-weight: bold; margin-bottom: 10px;"><?php _e( 'Not Found', 'mahjong' ); ?></h1>
					<p style="margin-bottom: 10px;"><?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'mahjong' ); ?></p>
					<?php get_search_form(); ?>
             
             
        <!--info_container end-->
        </div>
      <!--right_main end-->
      </div>
      
	<!--wrap end-->
	</div>
<!--n_main_cont end-->
</div>
                    
                    
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>

<?php get_footer(); ?>