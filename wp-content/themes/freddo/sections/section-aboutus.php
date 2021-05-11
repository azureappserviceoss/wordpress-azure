<?php $showAboutus = freddo_options('_onepage_section_aboutus', ''); ?>
<?php if ($showAboutus == 1) : ?>
	<?php
		$aboutusSectionID = freddo_options('_onepage_id_aboutus', 'aboutus');
		$aboutusTitle = freddo_options('_onepage_title_aboutus', __('About Us', 'freddo'));
		$aboutusSubTitle = freddo_options('_onepage_subtitle_aboutus', __('Who We Are', 'freddo'));
		$aboutusPageBox = freddo_options('_onepage_choosepage_aboutus');
		$aboutusButtonText = freddo_options('_onepage_textbutton_aboutus', __('More Information', 'freddo'));
		$aboutusButtonLink = freddo_options('_onepage_linkbutton_aboutus', '#');
	?>
<section class="freddo_onepage_section freddo_aboutus <?php echo has_post_thumbnail($aboutusPageBox) ? 'withImage' : 'noImage' ?>" id="<?php echo esc_attr($aboutusSectionID); ?>">
	<div class="freddo_aboutus_color"></div>
	<div class="freddo_action_aboutus">
		<?php if($aboutusTitle || is_customize_preview()): ?>
			<h2 class="freddo_main_text"><?php echo esc_html($aboutusTitle); ?></h2>
		<?php endif; ?>
		<?php if($aboutusSubTitle || is_customize_preview()): ?>
			<p class="freddo_subtitle"><?php echo esc_html($aboutusSubTitle); ?></p>
		<?php endif; ?>
		<div class="aboutus_columns">
			<div class="one aboutus_columns_three">
				<div class="aboutInner">
					<?php if($aboutusPageBox) : ?>
					<h3><?php echo esc_html(get_the_title(intval($aboutusPageBox))); ?></h3>
					<?php
						$args = array(
						  'p'         => intval($aboutusPageBox),
						  'post_type' => 'page'
						);
						$query = new WP_Query( $args );
						if ( $query->have_posts() ) :
							while ( $query->have_posts() ) :
								$query->the_post();
								?>
								<article id="post-<?php the_ID(); ?>" <?php post_class( 'freddo-aboutus' ); ?> >
									<?php
									the_content(
										sprintf(
											/* translators: %s: Name of current post */
											__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'freddo' ),
											get_the_title()
										)
									);
									?>
								</article>
								<?php
							endwhile;
						endif;
						wp_reset_postdata();
					?>
					<?php endif; ?>
					<?php if($aboutusButtonText || is_customize_preview()): ?>
						<div class="freddoButton aboutus"><a href="<?php echo esc_url($aboutusButtonLink); ?>"><?php echo esc_html($aboutusButtonText); ?></a></div>
					<?php endif; ?>
				</div>
			</div>
			<?php if ('' != get_the_post_thumbnail($aboutusPageBox)) : ?>
				<div class="two aboutus_columns_three">
					<div class="aboutInnerImage">
						<?php echo get_the_post_thumbnail(intval($aboutusPageBox), 'large'); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>