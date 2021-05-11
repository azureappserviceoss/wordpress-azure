(function($) {
	"use strict";

	$(document).ready(function() {

		//FontAwesome Icon Control JS
		$('body').on('click', '.freddo-icon-list li', function(){
			var icon_class = $(this).find('i').attr('class');
			$(this).addClass('icon-active').siblings().removeClass('icon-active');
			$(this).parent('.freddo-icon-list').prev('.freddo-selected-icon').children('i').attr('class','').addClass(icon_class);
			$(this).parent('.freddo-icon-list').next('input').val(icon_class).trigger('change');
		});

		$('body').on('click', '.freddo-selected-icon', function(){
			$(this).next().slideToggle();
		});
		
		// FontAwesome search filter
		$( '.freddo-icon-list' ).each(function() {
			$(this).find('#freddoInputFilter').on('keyup', function() {
				var value = $(this).val().toLowerCase(),
					where = $(this).closest('.freddo-icon-list').find('li');
				$(where).filter(function() {
					$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
				});
			});
		});
		
		//Scroll to section
		$('body').on('click', '#sub-accordion-panel-cresta_freddo_onepage .control-subsection .accordion-section-title', function(event) {
			var section_class = $(this).parent('.control-subsection').attr('id');
			scrollToSection( section_class );
		});
		
		function scrollToSection( section_class ){
			var preview_section_class = 'freddo_slider';
			var $contents = jQuery('#customize-preview iframe').contents();
			switch ( section_class ) {
				case 'accordion-section-cresta_freddo_onepage_section_slider':
				preview_section_class = 'freddo_slider';
				break;

				case 'accordion-section-cresta_freddo_onepage_section_aboutus':
				preview_section_class = 'freddo_aboutus';
				break;

				case 'accordion-section-cresta_freddo_onepage_section_features':
				preview_section_class = 'freddo_features';
				break;

				case 'accordion-section-cresta_freddo_onepage_section_skills':
				preview_section_class = 'freddo_skills';
				break;

				case 'accordion-section-cresta_freddo_onepage_section_cta':
				preview_section_class = 'freddo_cta';
				break;

				case 'accordion-section-cresta_freddo_onepage_section_services':
				preview_section_class = 'freddo_services';
				break;

				case 'accordion-section-cresta_freddo_onepage_section_blog':
				preview_section_class = 'freddo_blog';
				break;

				case 'accordion-section-cresta_freddo_onepage_section_team':
				preview_section_class = 'freddo_team';
				break;

				case 'accordion-section-cresta_freddo_onepage_section_contact':
				preview_section_class = 'freddo_contact';
				break;
			}
			if( $contents.find('.'+preview_section_class).length > 0 ){
				$contents.find('html, body').animate({
				scrollTop: $contents.find( '.' + preview_section_class ).offset().top
				}, 1000);
			}
		}
		
	});

})(jQuery);