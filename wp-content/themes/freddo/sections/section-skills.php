<?php $showSkills = freddo_options('_onepage_section_skills', ''); ?>
<?php if ($showSkills == 1) : ?>
	<?php
		$skillsSectionID = freddo_options('_onepage_id_skills', 'skills');
		$skillsTitle = freddo_options('_onepage_title_skills', __('Our Skills', 'freddo'));
		$skillsSubTitle = freddo_options('_onepage_subtitle_skills', __('What We Do', 'freddo'));
		$skillName = array();
		$skillValue = array();
		for( $number = 1; $number < FREDDO_VALUE_FOR_SKILLS; $number++ ){
			$skillName["$number"] = freddo_options('_onepage_skillname_'.$number.'_skills', '');
			$skillValue["$number"] = freddo_options('_onepage_skillvalue_'.$number.'_skills', '');
		}
	?>
<section class="freddo_onepage_section freddo_skills" id="<?php echo esc_attr($skillsSectionID); ?>">
	<div class="freddo_skills_color"></div>
	<div class="freddo_action_skills">
	<?php if($skillsTitle || is_customize_preview()): ?>
		<h2 class="freddo_main_text"><?php echo esc_html($skillsTitle); ?></h2>
	<?php endif; ?>
	<?php if($skillsSubTitle || is_customize_preview()): ?>
		<p class="freddo_subtitle"><?php echo esc_html($skillsSubTitle); ?></p>
	<?php endif; ?>
		<div class="skills_columns">
			<?php for( $number = 1; $number < FREDDO_VALUE_FOR_SKILLS; $number++ ) : ?>
				<?php if ($skillName["$number"]) : ?>
					<div class="freddoSkill">
						<div class="skillTop">
							<div class="skillName"><?php echo esc_html($skillName["$number"]); ?></div>
							<div class="skillNameUnder"><?php echo esc_html($skillName["$number"]); ?></div>
							<div class="skillValue" data-delay="<?php echo intval($number * 150) ?>"><span><?php echo intval($skillValue["$number"]); ?></span><i><?php esc_html_e('%', 'freddo'); ?></i></div>
						</div>
						<div class="skillBottom">
							<div class="skillBar"></div>
							<div class="skillRealBar" data-number="<?php echo intval($skillValue["$number"]); ?>%" data-delay="<?php echo intval($number * 150) ?>"><div class="skillRealBarCyrcle"></div></div>
						</div>
					</div>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	</div>
</section>
<?php endif; ?>