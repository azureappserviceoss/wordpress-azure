<?php 
$showBlog = freddo_options('_onepage_section_blog', '');
?>
<?php if ($showBlog == 1) : ?>
	<?php
		$blogSectionID = freddo_options('_onepage_id_blog','blog');
		$blogTitle = freddo_options('_onepage_title_blog',__('News', 'freddo'));
		$blogSubTitle = freddo_options('_onepage_subtitle_blog', __('Latest Posts', 'freddo'));
		$blogtoShow = freddo_options('_onepage_noposts_blog','3');
		$blogTextButton = freddo_options('_onepage_textbutton_blog',__('Go to the blog!', 'freddo'));
		$blogLinkButton = freddo_options('_onepage_linkbutton_blog', '#');
	?>
<section class="freddo_onepage_section freddo_blog" id="<?php echo esc_attr($blogSectionID); ?>">
	<div class="freddo_blog_color"></div>
	<div class="freddo_action_blog">
	<?php if($blogTitle || is_customize_preview()): ?>
		<h2 class="freddo_main_text"><?php echo esc_html($blogTitle); ?></h2>
	<?php endif; ?>
	<?php if($blogSubTitle || is_customize_preview()): ?>
		<p class="freddo_subtitle"><?php echo esc_html($blogSubTitle); ?></p>
	<?php endif; ?>
		<div class="blog_columns">
				<?php
					$args = array( 'posts_per_page' => intval($blogtoShow), 'post_status'=>'publish', 'post_type'=>'post', 'orderby'=>'date', 'ignore_sticky_posts' => true );
					$the_query = new WP_Query( $args );
					if ($the_query->have_posts()) :
					while( $the_query->have_posts() ) : $the_query->the_post();
				?>
					<div class="freddoBlogSingle">
						<?php
							if ( '' != get_the_post_thumbnail() ) {
								echo '<div class="entry-featuredImg"><a href="' .esc_url(get_permalink()). '">';
								the_post_thumbnail('freddo-little-post');
								echo '<div class="entry-featuredImg-border"></div></a></div>';
							}
						?>
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<?php if ( 'post' === get_post_type() ) : ?>
						<div class="entry-meta">
							<?php freddo_posted_on(); ?>
						</div><!-- .entry-meta -->
						<?php
						endif; ?>
						<div class="entry-summary">
							<?php the_excerpt(); ?>
						</div><!-- .entry-content -->
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
				<?php endif; ?>
				<?php if($blogTextButton): ?>
					<div class="freddoButton goToBlog"><a href="<?php echo esc_url($blogLinkButton); ?>"><?php echo esc_html($blogTextButton); ?></a></div>
				<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>