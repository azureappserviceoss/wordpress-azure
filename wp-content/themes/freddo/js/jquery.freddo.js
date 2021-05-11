(function($) {
	'use strict';
	$( window ).scroll(function () {
		if( $( 'ul.freddo_sectionmap' ).length ) {
			var currentNode = null;
			$('.freddo_onepage_section').each(function () {
				var s = $(this);
				var currentId = s.attr('id') || '';
				if ( $( window ).scrollTop() >= s.offset().top - 1) {
					currentNode = currentId;
				}

			});
			$('ul.freddo_sectionmap li').removeClass('current-section');
			if ( currentNode ) {
				$('ul.freddo_sectionmap li').find('a[href$="#' + currentNode + '"]').parent().addClass('current-section');
			}
		}
	});
	$(window).on('load', function() {
		if ( $( '.flexslider' ).length ) {
		  $('.flexslider').flexslider({
			animation: 'fade',
			controlNav: true,
			directionNav: false, 
			slideshowSpeed: $('.freddo_onepage_section.freddo_slider').attr('data-speed'),
			animationSpeed: 1000,
			pauseOnHover: $('.freddo_onepage_section.freddo_slider').attr('data-hover')
		  });
		}
	});
	$(document).ready(function() {
		/*-----------------------------------------------------------------------------------*/
		/*  Page Loader
		/*-----------------------------------------------------------------------------------*/ 
			if ( $( '.freddoLoader' ).length ) {
				$('.freddoLoader').delay(600).fadeOut(1000);
			}
		/*-----------------------------------------------------------------------------------*/
		/*  Detect Mobile Browser
		/*-----------------------------------------------------------------------------------*/
			var mobileDetect = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
		/*-----------------------------------------------------------------------------------*/
		/*  Home icon in main menu
		/*-----------------------------------------------------------------------------------*/ 
			$('.main-navigation .menu-item-home:first-child > a').prepend('<i class="fa fa-home spaceRight"></i>');
		/*-----------------------------------------------------------------------------------*/
		/*  Add class for inputs in comments and search widget
		/*-----------------------------------------------------------------------------------*/ 
			$( '#comments.comments-area input[type="text"], #comments.comments-area input[type="email"], #comments.comments-area input[type="url"], #comments.comments-area input[type="password"], #comments.comments-area input[type="search"], .widget.widget_search input[type="search"], .widget.woocommerce.widget_product_search input[type="search"], .search-container input[type="search"], #comments.comments-area textarea' ).wrap( '<div class="inc-input"></div>' );
			$('<span class="focus-bg"></span>').insertAfter('#comments.comments-area input[type="text"], #comments.comments-area input[type="email"], #comments.comments-area input[type="url"], #comments.comments-area input[type="password"], #comments.comments-area input[type="search"], .widget.widget_search input[type="search"], .widget.woocommerce.widget_product_search input[type="search"], .search-container input[type="search"], #comments.comments-area textarea');
		/*-----------------------------------------------------------------------------------*/
		/*  Scroll to section
		/*-----------------------------------------------------------------------------------*/ 
			$('ul.menu a[href*="#"]:not([href="#"]), ul.freddo_sectionmap li a').click(function() {
				if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'') && location.hostname === this.hostname) {
				  var target = $(this.hash);
				  target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				  if (target.length) {
					$('html, body').animate({
					  scrollTop: target.offset().top
					}, 1000);
					$('.main-navigation').removeClass('toggled');
					$('.menu-toggle').html('<i class="fa fa-lg fa-bars"></i>');
					return false;
				  }
				}
			});
		/*-----------------------------------------------------------------------------------*/
		/*  Check if featured image exist
		/*-----------------------------------------------------------------------------------*/ 
			if ( $( '.freddoBigImage, .flexslider' ).length ) {
			} else {
				$('header.site-header').addClass('noImage');
			}
		/*-----------------------------------------------------------------------------------*/
		/*  Sidebar Push Button
		/*-----------------------------------------------------------------------------------*/ 
			$('.hamburger-menu, .opacityBox, .close-hamburger').click(function(){
				$('.hamburger-menu, .opacityBox, #page.site, #tertiary.widget-area').toggleClass('yesOpen');
			});
		/*-----------------------------------------------------------------------------------*/
		/*  Search Push Button
		/*-----------------------------------------------------------------------------------*/ 
			$('.search-button, .opacityBoxSearch').click(function(){
				$('.search-button, .opacityBoxSearch, .search-container,#page.site').toggleClass('serOpen');
			});
		/*-----------------------------------------------------------------------------------*/
		/*  Menu Widget
		/*-----------------------------------------------------------------------------------*/
			if ( $( 'aside ul.menu, aside ul.product-categories' ).length ) {
				$('aside ul.menu, aside ul.product-categories').find('li').each(function(){
					if($(this).children('ul').length > 0){
						$(this).append('<span class="indicatorBar"></span>');
					}
				});
				$('aside ul.menu > li.menu-item-has-children .indicatorBar, .aside ul.menu > li.page_item_has_children .indicatorBar, aside ul.product-categories > li.cat-parent .indicatorBar').click(function() {
					$(this).parent().find('> ul.sub-menu, > ul.children').toggleClass('yesOpenBar');
					$(this).toggleClass('yesOpenBar');
					var $self = $(this).parent();
					if($self.find('> ul.sub-menu, > ul.children').hasClass('yesOpenBar')) {
						$self.find('> ul.sub-menu, > ul.children').slideDown(300);
					} else {
						$self.find('> ul.sub-menu, > ul.children').slideUp(200);
					}
				});
			}
		/*-----------------------------------------------------------------------------------*/
		/*  Sub-Menu Position
		/*-----------------------------------------------------------------------------------*/
			if (!mobileDetect) {
				$('.main-navigation').find('li').each(function(){
					if ($('ul', this).length) {
						var elm = $('ul:first', this);
						var off = elm.offset();
						var l = off.left;
						var w = elm.width();
						var docH = $("body").height();
						var docW = $("body").width();
						var isEntirelyVisible = (l + w <= docW);
						if (!isEntirelyVisible) {
							$(this).addClass('invert');
						} else {
							$(this).removeClass('invert');
						}
					}
				});
			}
		/*-----------------------------------------------------------------------------------*/
		/*  Mobile Menu
		/*-----------------------------------------------------------------------------------*/ 
			if ($( window ).width() <= 1025) {
				$('.main-navigation').find('li').each(function(){
					if($(this).children('ul').length > 0){
						$(this).append('<span class="indicator"></span>');
					}
				});
				$('.main-navigation ul > li.menu-item-has-children .indicator, .main-navigation ul > li.page_item_has_children .indicator').click(function() {
					$(this).parent().find('> ul.sub-menu, > ul.children').toggleClass('yesOpen');
					$(this).toggleClass('yesOpen');
					var $self = $(this).parent();
					if($self.find('> ul.sub-menu, > ul.children').hasClass('yesOpen')) {
						$self.find('> ul.sub-menu, > ul.children').slideDown(300);
					} else {
						$self.find('> ul.sub-menu, > ul.children').slideUp(200);
					}
				});
			}
			$(window).resize(function() {
				if ($( window ).width() > 1025) {
					$('.main-navigation ul > li.menu-item-has-children, .main-navigation ul > li.page_item_has_children').find('> ul.sub-menu, > ul.children').slideDown(300);
				}
			});
		/*-----------------------------------------------------------------------------------*/
		/*  Waypoints general script
		/*-----------------------------------------------------------------------------------*/ 
			if($('body').hasClass('page-template-template-onepage')) {
				if ( $.isFunction($.fn.waypoint) ) {
					/*-----------------------------------------------------------------------------------*/
					/*  Waypoints for skills
					/*-----------------------------------------------------------------------------------*/ 
					$('section.freddo_skills').waypoint(function() {
						$('.skillBottom .skillRealBar').each( function() {
						var $this = $(this);
							setTimeout(function() {
								$this.css('width',$this.data('number'));
							}, $this.data('delay'));
						});
						$('.skillTop .skillValue').each( function() {
						var $this = $(this);
							setTimeout(function() {
								$this.css({'opacity':'1', 'bottom': '-5px'});
							}, 1000 + $this.data('delay'));
						});
					},{
						triggerOnce: true,
						offset: '60%'
					});
					/*-----------------------------------------------------------------------------------*/
					/*  Waypoints for contact icon
					/*-----------------------------------------------------------------------------------*/ 
					$('section.freddo_contact').waypoint(function() {
						$('.contact_columns .freddoContactIcon').css({'opacity':'0.1', 'right': '25px'});
					},{
						triggerOnce: true,
						offset: '20%'
					});
				}
			}
		/*-----------------------------------------------------------------------------------*/
		/*  Scroll To Top
		/*-----------------------------------------------------------------------------------*/ 
			if (!mobileDetect || $('#toTop').hasClass('scrolltop_on') ) {
				$(window).scroll(function(){
					if ($(this).scrollTop() > 700) {
						$('#toTop').addClass('visible');
					} 
					else {
						$('#toTop').removeClass('visible');
					}
				}); 
				$('#toTop').click(function(){
					$('html, body').animate({ scrollTop: 0 }, 1000);
					return false;
				});
			}
		/*-----------------------------------------------------------------------------------*/
		/*  Detect Mobile Browser
		/*-----------------------------------------------------------------------------------*/ 
			if ( !mobileDetect ) {
				/*-----------------------------------------------------------------------------------*/
				/*  Header Parallax
				/*-----------------------------------------------------------------------------------*/ 
					if($('.freddoBigImage').hasClass('withZoom')) {
						$( '.freddoBigImage' ).data( 'height', $( '.freddoBigImage' ).outerHeight() );
						$( window ).scroll( function() {
							var position = window.scrollY,
								bottom   = window.innerHeight - document.getElementById( 'colophon' ).offsetHeight,
								height   = $( '.freddoBigImage' ).data( 'height' ),
								content  = $( '#content' ).offset().top,
								footer   = $( '#colophon' ).offset().top - position;

							if ( position > 0 && content > position && footer > bottom ) {
								if ( position < height ) {
									$('.freddoBigImage').css({
										'transform' : 'scale('+( 1 + position / height * 0.3 )+','+( 1 + position / height * 0.3 )+')'
									});
								}
							} else if ( position <= 0 ) {
								$('.freddoBigImage').css({
									'transform' : 'scale('+1+','+1+')'
								});
							}
						});
					}
				/*-----------------------------------------------------------------------------------*/
				/*  Slider Parallax
				/*-----------------------------------------------------------------------------------*/ 
					if($('.freddo_slider').hasClass('withZoom')) {
						$( '.flexslider .slides > li .flexImage' ).data( 'height', $( '.flexslider .slides > li .flexImage' ).outerHeight() );
						$( window ).scroll( function() {
							var position = window.scrollY,
								bottom   = window.innerHeight - document.getElementById( 'colophon' ).offsetHeight,
								height   = $( '.flexslider .slides > li .flexImage' ).data( 'height' ),
								content  = $( 'section.freddo_slider' ).offset().top + $( 'section.freddo_slider' ).outerHeight() ,
								footer   = $( '#colophon' ).offset().top - position;

							if ( position > 0 && content > position && footer > bottom ) {
								if ( position < height ) {
									$('.flexslider .slides > li .flexImage').css({
										'transform' : 'scale('+( 1 + position / height * 0.3 )+','+( 1 + position / height * 0.3 )+')'
									});
								}
							} else if ( position <= 0 ) {
								$('.flexslider .slides > li .flexImage').css({
									'transform' : 'scale('+1+','+1+')'
								});
							}
						});
					}
				/*-----------------------------------------------------------------------------------*/
				/*  Menu Fixed
				/*-----------------------------------------------------------------------------------*/ 
					var $filter = $('header.site-header');
					if ($filter.length) {
						$(window).scroll(function () {
							if (!$filter.hasClass('menuMinor') && $(window).scrollTop() > 0 ) {
								$filter.addClass('menuMinor');
								$('body').addClass('menuMinor');
								$('.site-branding .site-description').slideUp(200);
							} else if ($filter.hasClass('menuMinor') && $(window).scrollTop() <= 0 ) {
								$filter.removeClass('menuMinor');
								$('body').removeClass('menuMinor');
								$('.site-branding .site-description').slideDown(200);
							}
						});
					}
				/*-----------------------------------------------------------------------------------*/
				/*  Social Buttons Float
				/*-----------------------------------------------------------------------------------*/ 
					if ( $( '.site-social ' ).length ) {
						if ( $( '.freddoBigImage' ).length ) {
							$(window).scroll(function () {
								if ($(window).scrollTop() >= $('.freddoBigImage').outerHeight() ) {
									$('.site-social-float').addClass('showSocial');
								} else {
									$('.site-social-float').removeClass('showSocial');
								}
							});
						} else if ( $( '.flexslider' ).length ) {
							$(window).scroll(function () {
								if ($(window).scrollTop() >= $('.flexslider').outerHeight() ) {
									$('.site-social-float').addClass('showSocial');
								} else {
									$('.site-social-float').removeClass('showSocial');
								}
							});
						} else {
							$('.site-social-float').addClass('showSocial');
						}
					}
				/*-----------------------------------------------------------------------------------*/
				/*  Scroll Down button
				/*-----------------------------------------------------------------------------------*/ 
					if ( $( '.scrollDown' ).length ) {
						$('.scrollDown').click(function(){
							$('html, body').animate({ scrollTop: $('.freddoBigImage, .freddo_slider').outerHeight() }, 1000);
							return false;
						});
					}
			}
	});
})(jQuery);