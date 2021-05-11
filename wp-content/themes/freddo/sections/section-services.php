<?php $showServices = freddo_options('_onepage_section_services', ''); ?>
<?php if ($showServices == 1) : ?>
	<?php
		$servicesSectionID = freddo_options('_onepage_id_services', 'services');
		$servicesTitle = freddo_options('_onepage_title_services', __('Services', 'freddo'));
		$servicesSubTitle = freddo_options('_onepage_subtitle_services', __('What We Offer', 'freddo'));
		$servicesPhrase = freddo_options('_onepage_phrase_services', '');
		$servicesTextarea = freddo_options('_onepage_textarea_services', '');
		$servicesImage = freddo_options('_onepage_servimage_services');
		$textLenght = freddo_options('_onepage_lenght_services', '30');
		$customMore = freddo_options('_excerpt_more', '&hellip;');
		$formattedOrPlain = freddo_options('_onepage_typetext_services', 'formatted');
		$singleServiceBox = array();
		$singleServiceFont = array();
		for( $number = 1; $number < FREDDO_VALUE_FOR_SERVICES; $number++ ){
			$singleServiceBox["$number"] = freddo_options('_onepage_choosepage_'.$number.'_services', '');
			$singleServiceFont["$number"] = freddo_options('_onepage_fontawesome_'.$number.'_services', '');
			$singleServiceOptLink["$number"] = freddo_options('_onepage_optlink_'.$number.'_services', '');
		}
	?>
<section class="freddo_onepage_section freddo_services" id="<?php echo esc_attr($servicesSectionID); ?>">
	<div class="freddo_services_color"></div>
	<div class="freddo_action_services">
		<?php if($servicesTitle || is_customize_preview()): ?>
			<h2 class="freddo_main_text"><?php echo esc_html($servicesTitle); ?></h2>
		<?php endif; ?>
		<?php if($servicesSubTitle || is_customize_preview()): ?>
			<p class="freddo_subtitle"><?php echo esc_html($servicesSubTitle); ?></p>
		<?php endif; ?>
		<div class="services_columns">
			<div class="one services_columns_single">
				<div class="singleServiceContent">
				<?php for( $number = 1; $number < FREDDO_VALUE_FOR_SERVICES; $number++ ) : ?>
					<?php if ($singleServiceBox["$number"]) : ?>
						<div class="singleService">
							<div class="serviceIcon">
								<?php if ($singleServiceOptLink["$number"]): ?>
									<a href="<?php echo esc_url($singleServiceOptLink["$number"]); ?>" title="<?php echo esc_attr(get_the_title(intval($singleServiceBox["$number"]))); ?>"><i class="<?php echo esc_attr($singleServiceFont["$number"]); ?>" aria-hidden="true"></i></a>
								<?php else: ?>
									<i class="<?php echo esc_attr($singleServiceFont["$number"]); ?>" aria-hidden="true"></i>
								<?php endif; ?>
							</div>
							<div class="serviceText">
								<?php if ($singleServiceOptLink["$number"]): ?>
									<h3><a href="<?php echo esc_url($singleServiceOptLink["$number"]); ?>" title="<?php echo esc_attr(get_the_title(intval($singleServiceBox["$number"]))); ?>"><?php echo esc_html(get_the_title(intval($singleServiceBox["$number"]))); ?></a></h3>
								<?php else: ?>
									<h3><?php echo esc_html(get_the_title(intval($singleServiceBox["$number"]))); ?></h3>
								<?php endif; ?>
								<?php
								$post_contentt = get_post(intval($singleServiceBox["$number"]));
								$content = $post_contentt->post_content;
								$content = apply_filters('the_content', $content);
								$content = str_replace(']]>', ']]&gt;', $content);
								?>
								<?php if ($formattedOrPlain == 'formatted'): ?>
									<p><?php echo force_balance_tags( html_entity_decode( wp_trim_words( do_shortcode( htmlentities($content) ), intval($textLenght), esc_html($customMore) ) ) ); ?></p>
								<?php else: ?>
									<p><?php echo wp_trim_words($content , intval($textLenght), esc_html($customMore) ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endfor; ?>
				</div>
			</div>
			<div class="two services_columns_single" style="background-image: url(<?php echo esc_url($servicesImage); ?>);">
				<div class="serviceColumnSingleColor"></div>
				<div class="serviceContent">
					<?php if ($servicesPhrase || is_customize_preview()): ?>
						<h3><?php echo esc_html($servicesPhrase); ?></h3>
					<?php endif; ?>
					<?php if ($servicesTextarea || is_customize_preview()): ?>
						<p><?php echo wp_kses($servicesTextarea, freddo_allowed_html()); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>