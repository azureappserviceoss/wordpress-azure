<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package freddo
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php
			if ( have_posts() ) : ?>
			
				<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) : ?>

					<header class="page-header">
						<?php
							the_archive_title( '<h1 class="page-title">', '</h1>' );
							the_archive_description( '<div class="archive-description">', '</div>' );
						?>
					</header><!-- .page-header -->

					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/*
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_format() );

					endwhile;

					the_posts_pagination( array(
						'prev_text'          => '<i class="fa fa-angle-double-left spaceRight" aria-hidden="true"></i>' . esc_html__( 'Previous', 'freddo' ),
						'next_text'          => esc_html__( 'Next', 'freddo' ) . '<i class="fa fa-angle-double-right spaceLeft" aria-hidden="true"></i>',
					) );
				
				endif; 

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_sidebar('push');
get_footer();
