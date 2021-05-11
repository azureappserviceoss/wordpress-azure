/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	/* Text */
	wp.customize( 'freddo_theme_options[_onepage_text_1_slider]', function( value ) {
		value.bind( function( to ) {
			$( '.flexslider .slides > li:first-child .flexText .inside h2' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_text_2_slider]', function( value ) {
		value.bind( function( to ) {
			$( '.flexslider .slides > li:nth-child(2) .flexText .inside h2' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_text_3_slider]', function( value ) {
		value.bind( function( to ) {
			$( '.flexslider .slides > li:nth-child(3) .flexText .inside h2' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtext_1_slider]', function( value ) {
		value.bind( function( to ) {
			$( '.flexslider .slides > li:first-child .flexText .inside span' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtext_2_slider]', function( value ) {
		value.bind( function( to ) {
			$( '.flexslider .slides > li:nth-child(2) .flexText .inside span' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtext_3_slider]', function( value ) {
		value.bind( function( to ) {
			$( '.flexslider .slides > li:nth-child(3) .flexText .inside span' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_title_aboutus]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_aboutus .freddo_main_text' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtitle_aboutus]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_aboutus .freddo_subtitle' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textbutton_aboutus]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_aboutus .freddoButton.aboutus a' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_title_features]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_features .freddo_main_text' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtitle_features]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_features .freddo_subtitle' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_title_skills]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_skills .freddo_main_text' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtitle_skills]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_skills .freddo_subtitle' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_phrase_cta]', function( value ) {
		value.bind( function( to ) {
			$( '.cta_columns .ctaPhrase h3' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_desc_cta]', function( value ) {
		value.bind( function( to ) {
			$( '.cta_columns .ctaPhrase p' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_title_services]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_services .freddo_main_text' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtitle_services]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_services .freddo_subtitle' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_phrase_services]', function( value ) {
		value.bind( function( to ) {
			$( '.services_columns_single .serviceContent h3' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textarea_services]', function( value ) {
		value.bind( function( to ) {
			$( '.services_columns_single .serviceContent p' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_title_blog]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_blog .freddo_main_text' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtitle_blog]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_blog .freddo_subtitle' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_title_team]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_team .freddo_main_text' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtitle_team]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_team .freddo_subtitle' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_title_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_contact .freddo_main_text' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_subtitle_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddo_action_contact .freddo_subtitle' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_additionaltext_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddoAdditionalText p' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_companyname_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddoCompanyName h3' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_companyaddress1_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddoCompanyAddress1 p' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_companyaddress2_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddoCompanyAddress2 p' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_companyaddress3_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddoCompanyAddress3 p' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_companyphone_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddoCompanyPhone p' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_companyfax_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddoCompanyFax p' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_companyemail_contact]', function( value ) {
		value.bind( function( to ) {
			$( '.freddoCompanyEmail p' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_post_scrolldown_text]', function( value ) {
		value.bind( function( to ) {
			$( '.freddoBigText .scrollDown span' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_scrolldown_text]', function( value ) {
		value.bind( function( to ) {
			$( '.flexslider .scrollDown span' ).text( to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_copyright_text]', function( value ) {
		value.bind( function( to ) {
			$( '.site-copy-down .site-info span.custom' ).text( to );
		} );
	} );
	/* Background Color and Text */
	wp.customize( 'freddo_theme_options[_onepage_imgcolor_aboutus]', function( value ) {
		value.bind( function( to ) {
			$('.freddo_aboutus_color').css('background-color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textcolor_aboutus]', function( value ) {
		value.bind( function( to ) {
			$('section.freddo_aboutus').css('color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_imgcolor_features]', function( value ) {
		value.bind( function( to ) {
			$('.freddo_features_color, .features_columns_single:hover .featuresIcon i, .features_columns_single:focus .featuresIcon i, .features_columns_single:active .featuresIcon i').css('background-color', to );
			$('.features_columns_single .featuresIcon i').css('color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textcolor_features]', function( value ) {
		value.bind( function( to ) {
			$('section.freddo_features, .features_columns_single:hover .featuresIcon i, .features_columns_single:focus .featuresIcon i, .features_columns_single:active .featuresIcon i').css('color', to );
			$('.features_columns_single .featuresIcon i').css('background-color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_imgcolor_skills]', function( value ) {
		value.bind( function( to ) {
			$('.freddo_skills_color').css('background-color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textcolor_skills]', function( value ) {
		value.bind( function( to ) {
			$('section.freddo_skills').css('color', to );
			$('.skillBottom .skillBar, .skillBottom .skillRealBar, .skillBottom .skillRealBarCyrcle').css('background', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_imgcolor_cta]', function( value ) {
		value.bind( function( to ) {
			$('.freddo_cta_color, section.freddo_cta:hover .cta_columns .ctaIcon i, section.freddo_cta:focus .cta_columns .ctaIcon i, section.freddo_cta:active .cta_columns .ctaIcon i').css('background-color', to );
			$('.cta_columns .ctaIcon i').css('color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textcolor_cta]', function( value ) {
		value.bind( function( to ) {
			$('section.freddo_cta, section.freddo_cta:hover .cta_columns .ctaIcon i, section.freddo_cta:focus .cta_columns .ctaIcon i, section.freddo_cta:active .cta_columns .ctaIcon i').css('color', to );
			$('.cta_columns .ctaIcon i').css('background', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_imgcolor_services]', function( value ) {
		value.bind( function( to ) {
			$('.freddo_services_color, .services_columns .singleService:hover .serviceIcon i, .services_columns .singleService:focus .serviceIcon i, .services_columns .singleService:active .serviceIcon i').css('background-color', to );
			$('.serviceIcon i, .services_columns_single .serviceContent').css('color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textcolor_services]', function( value ) {
		value.bind( function( to ) {
			$('section.freddo_services, .services_columns .singleService:hover .serviceIcon i, .services_columns .singleService:focus .serviceIcon i, .services_columns .singleService:active .serviceIcon i').css('color', to );
			$('.serviceIcon i').css('background', to );
			$('.services_columns_single.two .serviceColumnSingleColor').css('background-color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_imgcolor_blog]', function( value ) {
		value.bind( function( to ) {
			$('.freddo_blog_color').css('background-color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textcolor_blog]', function( value ) {
		value.bind( function( to ) {
			$('section.freddo_blog, .freddoBlogSingle h2 a, .freddoBlogSingle h2 a:hover, .freddoBlogSingle h2 a:focus, .freddoBlogSingle h2 a:active').css('color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_imgcolor_team]', function( value ) {
		value.bind( function( to ) {
			$('.freddo_team_color').css('background-color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textcolor_team]', function( value ) {
		value.bind( function( to ) {
			$('section.freddo_team').css('color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_imgcolor_contact]', function( value ) {
		value.bind( function( to ) {
			$('.freddo_contact_color, .contact_columns .freddoContactForm input:not([type="submit"]), .contact_columns .freddoContactForm textarea').css('background-color', to );
			$('.freddoCompanyAddress1Icon, .freddoCompanyPhoneIcon, .freddoCompanyFaxIcon, .freddoCompanyEmailIcon').css('color', to );
		} );
	} );
	wp.customize( 'freddo_theme_options[_onepage_textcolor_contact]', function( value ) {
		value.bind( function( to ) {
			$('section.freddo_contact, .contact_columns .freddoContactForm input:not([type="submit"]), .contact_columns .freddoContactForm input:not([type="submit"]):focus, .contact_columns .freddoContactForm textarea, .contact_columns .freddoContactForm textarea:focus').css('color', to );
			$('section.freddo_contact, .contact_columns .freddoContactForm input:not([type="submit"]), .contact_columns .freddoContactForm input:not([type="submit"]):focus, .contact_columns .freddoContactForm textarea, .contact_columns .freddoContactForm textarea:focus').css('border-color', to );
			$('.freddoCompanyAddress1Icon, .freddoCompanyPhoneIcon, .freddoCompanyFaxIcon, .freddoCompanyEmailIcon').css('background', to );
		} );
	} );
} )( jQuery );
