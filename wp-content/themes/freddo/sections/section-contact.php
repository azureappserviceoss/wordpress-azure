<?php $showContact = freddo_options('_onepage_section_contact', ''); ?>
<?php if ($showContact == 1) : ?>
	<?php
		$contactSectionID = freddo_options('_onepage_id_contact', 'contact');
		$contactTitle = freddo_options('_onepage_title_contact', __('Contact Us', 'freddo'));
		$contactSubTitle = freddo_options('_onepage_subtitle_contact', __('Get in touch', 'freddo'));
		$contactAddText = freddo_options('_onepage_additionaltext_contact', '');
		$contactCompanyName = freddo_options('_onepage_companyname_contact', '');
		$contactCompanyAddress1 = freddo_options('_onepage_companyaddress1_contact', '');
		$contactCompanyAddress2 = freddo_options('_onepage_companyaddress2_contact', '');
		$contactCompanyAddress3 = freddo_options('_onepage_companyaddress3_contact', '');
		$contactCompanyPhone = freddo_options('_onepage_companyphone_contact', '');
		$contactCompanyFax = freddo_options('_onepage_companyfax_contact', '');
		$contactCompanyEmail = freddo_options('_onepage_companyemail_contact', '');
		$contactShortcode = freddo_options('_onepage_shortcode_contact', '');
		$contactIcon = freddo_options('_onepage_icon_contact', 'fa fa-envelope');
		$contactCompanyPhoneLink = freddo_options('_onepage_companyphone_contact_link', '');
		$contactCompanyEmailLink = freddo_options('_onepage_companyemail_contact_link', '');
	?>
<section class="freddo_onepage_section freddo_contact <?php echo $contactShortcode ? 'withForm' : 'noForm' ?>" id="<?php echo esc_attr($contactSectionID); ?>">
	<div class="freddo_contact_color"></div>
	<div class="freddo_action_contact">
		<?php if($contactTitle || is_customize_preview()): ?>
			<h2 class="freddo_main_text"><?php echo esc_html($contactTitle); ?></h2>
		<?php endif; ?>
		<?php if($contactSubTitle || is_customize_preview()): ?>
			<p class="freddo_subtitle"><?php echo esc_html($contactSubTitle); ?></p>
		<?php endif; ?>
		<div class="contact_columns">
			<?php if($contactShortcode): ?>
			<div class="freddoContactForm">
				<?php echo do_shortcode(wp_kses_post($contactShortcode)); ?>
			</div>
			<?php endif; ?>
			<div class="freddoContactField">
				<?php if($contactAddText || is_customize_preview()): ?>
					<div class="freddoAdditionalText"><p><?php echo wp_kses($contactAddText, freddo_allowed_html()); ?></p></div>
				<?php endif; ?>
				<?php if($contactCompanyName || is_customize_preview()): ?>
					<div class="freddoCompanyName"><h3><?php echo esc_html($contactCompanyName); ?></h3></div>
				<?php endif; ?>
				<?php if($contactCompanyAddress1 || is_customize_preview()): ?>
					<div class="freddoCompanyAddress1"><div class="freddoCompanyAddress1Icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div><p><?php echo esc_html($contactCompanyAddress1); ?></p></div>
				<?php endif; ?>
				<?php if($contactCompanyAddress2 || is_customize_preview()): ?>
					<div class="freddoCompanyAddress2"><p><?php echo esc_html($contactCompanyAddress2); ?></p></div>
				<?php endif; ?>
				<?php if($contactCompanyAddress3 || is_customize_preview()): ?>
					<div class="freddoCompanyAddress3"><p><?php echo esc_html($contactCompanyAddress3); ?></p></div>
				<?php endif; ?>
				<?php if($contactCompanyPhone || is_customize_preview()): ?>
					<?php if($contactCompanyPhoneLink) : ?>
						<?php $numberLink = filter_var($contactCompanyPhone, FILTER_SANITIZE_NUMBER_INT); ?>
						<div class="freddoCompanyPhone"><div class="freddoCompanyPhoneIcon"><i class="fa fa-phone" aria-hidden="true"></i></div><p><a href="tel:<?php echo esc_attr($numberLink); ?>"><?php echo esc_html($contactCompanyPhone); ?></a></p></div>
					<?php else : ?>
						<div class="freddoCompanyPhone"><div class="freddoCompanyPhoneIcon"><i class="fa fa-phone" aria-hidden="true"></i></div><p><?php echo esc_html($contactCompanyPhone); ?></p></div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if($contactCompanyFax || is_customize_preview()): ?>
					<div class="freddoCompanyFax"><div class="freddoCompanyFaxIcon"><i class="fa fa-fax" aria-hidden="true"></i></div><p><?php echo esc_html($contactCompanyFax); ?></p></div>
				<?php endif; ?>
				<?php if(is_email($contactCompanyEmail) || is_customize_preview()): ?>
					<?php if($contactCompanyEmailLink) : ?>
						<div class="freddoCompanyEmail"><div class="freddoCompanyEmailIcon"><i class="fa fa-envelope" aria-hidden="true"></i></div><p><a href="mailto:<?php echo esc_html(antispambot($contactCompanyEmail)); ?>"><?php echo esc_html(antispambot($contactCompanyEmail)); ?></a></p></div>
					<?php else : ?>
						<div class="freddoCompanyEmail"><div class="freddoCompanyEmailIcon"><i class="fa fa-envelope" aria-hidden="true"></i></div><p><?php echo esc_html(antispambot($contactCompanyEmail)); ?></p></div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php if($contactIcon): ?>
				<div class="freddoContactIcon"><i class="<?php echo esc_attr($contactIcon); ?>" aria-hidden="true"></i></div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>