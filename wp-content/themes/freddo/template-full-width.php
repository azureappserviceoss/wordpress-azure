<?php
/**
 *
 * Template Name: Full width for page builders
 *
 * The template used if you are using a page builder plugin
 *
 * @package freddo
 */
 
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<div class="entry-content">
				<?php if(have_posts()) : while(have_posts()) : the_post(); the_content(); endwhile; endif; ?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_sidebar('push');
get_footer();