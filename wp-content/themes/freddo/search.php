<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package freddo
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php
			if ( have_posts() ) : ?>
			
				<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) : ?>

					<header class="page-header">
						<h1 class="page-title"><?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'freddo' ), '<span>' . get_search_query() . '</span>' );
						?></h1>
					</header><!-- .page-header -->

					<?php
					/* Start the Loop */
					while ( have_posts() ) : the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
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
	</section><!-- #primary -->

<?php
get_sidebar();
get_sidebar('push');
get_footer();
