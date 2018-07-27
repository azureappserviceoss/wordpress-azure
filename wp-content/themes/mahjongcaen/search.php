<?php
/**
 * The template for displaying Search Results pages.
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
	 Search
    </span>
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
        <div class="info_container searchpage">
        
        
        <?php if ( have_posts() ) : ?>
				<h1 style="display: none;" class="page-title"><?php printf( __( 'Search Results for: %s', 'mahjong' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'search' );
				?>
<?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h2 class="entry-title"><?php _e( 'Nothing Found', 'mahjong' ); ?></h2>
					<div class="entry-content">
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'mahjong' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->
        <?php endif; ?>

        
        
        <!--info_container end-->
        </div>
      <!--right_main end-->
      </div>
      
	<!--wrap end-->
	</div>
<!--n_main_cont end-->
</div>


<?php get_footer(); ?>
