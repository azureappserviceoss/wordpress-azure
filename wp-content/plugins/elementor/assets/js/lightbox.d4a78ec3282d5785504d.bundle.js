/*! elementor - v3.2.3 - 05-05-2021 */
(self["webpackChunkelementor"] = self["webpackChunkelementor"] || []).push([["lightbox"],{

/***/ "../node_modules/@babel/runtime-corejs2/core-js/object/define-properties.js":
/*!**********************************************************************************!*\
  !*** ../node_modules/@babel/runtime-corejs2/core-js/object/define-properties.js ***!
  \**********************************************************************************/
/*! dynamic exports */
/*! exports [maybe provided (runtime-defined)] [no usage info] -> ../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/fn/object/define-properties.js */
/*! runtime requirements: module, __webpack_require__ */
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__(/*! core-js/library/fn/object/define-properties */ "../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/fn/object/define-properties.js");

/***/ }),

/***/ "../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/fn/object/define-properties.js":
/*!**********************************************************************************************************!*\
  !*** ../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/fn/object/define-properties.js ***!
  \**********************************************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: __webpack_require__, module */
/*! CommonJS bailout: module.exports is used directly at 3:0-14 */
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

__webpack_require__(/*! ../../modules/es6.object.define-properties */ "../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/modules/es6.object.define-properties.js");
var $Object = __webpack_require__(/*! ../../modules/_core */ "../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/modules/_core.js").Object;
module.exports = function defineProperties(T, D) {
  return $Object.defineProperties(T, D);
};


/***/ }),

/***/ "../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/modules/es6.object.define-properties.js":
/*!*******************************************************************************************************************!*\
  !*** ../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/modules/es6.object.define-properties.js ***!
  \*******************************************************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: __webpack_require__ */
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

var $export = __webpack_require__(/*! ./_export */ "../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/modules/_export.js");
// 19.1.2.3 / 15.2.3.7 Object.defineProperties(O, Properties)
$export($export.S + $export.F * !__webpack_require__(/*! ./_descriptors */ "../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/modules/_descriptors.js"), 'Object', { defineProperties: __webpack_require__(/*! ./_object-dps */ "../node_modules/@babel/runtime-corejs2/node_modules/core-js/library/modules/_object-dps.js") });


/***/ }),

/***/ "../assets/dev/js/frontend/utils/lightbox/lightbox.js":
/*!************************************************************!*\
  !*** ../assets/dev/js/frontend/utils/lightbox/lightbox.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: module, __webpack_require__ */
/*! CommonJS bailout: module.exports is used directly at 19:0-14 */
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime-corejs2/helpers/interopRequireDefault */ "../node_modules/@babel/runtime-corejs2/helpers/interopRequireDefault.js");

__webpack_require__(/*! core-js/modules/es6.regexp.match.js */ "../node_modules/core-js/modules/es6.regexp.match.js");

var _regenerator = _interopRequireDefault(__webpack_require__(/*! @babel/runtime/regenerator */ "../node_modules/@babel/runtime/regenerator/index.js"));

__webpack_require__(/*! regenerator-runtime/runtime.js */ "../node_modules/regenerator-runtime/runtime.js");

var _asyncToGenerator2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/asyncToGenerator */ "../node_modules/@babel/runtime-corejs2/helpers/asyncToGenerator.js"));

__webpack_require__(/*! core-js/modules/es6.array.find.js */ "../node_modules/core-js/modules/es6.array.find.js");

__webpack_require__(/*! core-js/modules/es6.regexp.replace.js */ "../node_modules/core-js/modules/es6.regexp.replace.js");

var _screenfull = _interopRequireDefault(__webpack_require__(/*! ./screenfull */ "../assets/dev/js/frontend/utils/lightbox/screenfull.js"));

module.exports = elementorModules.ViewModule.extend({
  oldAspectRatio: null,
  oldAnimation: null,
  swiper: null,
  player: null,
  getDefaultSettings: function getDefaultSettings() {
    return {
      classes: {
        aspectRatio: 'elementor-aspect-ratio-%s',
        item: 'elementor-lightbox-item',
        image: 'elementor-lightbox-image',
        videoContainer: 'elementor-video-container',
        videoWrapper: 'elementor-fit-aspect-ratio',
        playButton: 'elementor-custom-embed-play',
        playButtonIcon: 'fa',
        playing: 'elementor-playing',
        hidden: 'elementor-hidden',
        invisible: 'elementor-invisible',
        preventClose: 'elementor-lightbox-prevent-close',
        slideshow: {
          container: 'swiper-container',
          slidesWrapper: 'swiper-wrapper',
          prevButton: 'elementor-swiper-button elementor-swiper-button-prev',
          nextButton: 'elementor-swiper-button elementor-swiper-button-next',
          prevButtonIcon: 'eicon-chevron-left',
          nextButtonIcon: 'eicon-chevron-right',
          slide: 'swiper-slide',
          header: 'elementor-slideshow__header',
          footer: 'elementor-slideshow__footer',
          title: 'elementor-slideshow__title',
          description: 'elementor-slideshow__description',
          counter: 'elementor-slideshow__counter',
          iconExpand: 'eicon-frame-expand',
          iconShrink: 'eicon-frame-minimize',
          iconZoomIn: 'eicon-zoom-in-bold',
          iconZoomOut: 'eicon-zoom-out-bold',
          iconShare: 'eicon-share-arrow',
          shareMenu: 'elementor-slideshow__share-menu',
          shareLinks: 'elementor-slideshow__share-links',
          hideUiVisibility: 'elementor-slideshow--ui-hidden',
          shareMode: 'elementor-slideshow--share-mode',
          fullscreenMode: 'elementor-slideshow--fullscreen-mode',
          zoomMode: 'elementor-slideshow--zoom-mode'
        }
      },
      selectors: {
        image: '.elementor-lightbox-image',
        links: 'a, [data-elementor-lightbox]',
        slideshow: {
          activeSlide: '.swiper-slide-active',
          prevSlide: '.swiper-slide-prev',
          nextSlide: '.swiper-slide-next'
        }
      },
      modalOptions: {
        id: 'elementor-lightbox',
        entranceAnimation: 'zoomIn',
        videoAspectRatio: 169,
        position: {
          enable: false
        }
      }
    };
  },
  getModal: function getModal() {
    if (!module.exports.modal) {
      this.initModal();
    }

    return module.exports.modal;
  },
  initModal: function initModal() {
    var modal = module.exports.modal = elementorFrontend.getDialogsManager().createWidget('lightbox', {
      className: 'elementor-lightbox',
      closeButton: true,
      closeButtonOptions: {
        iconClass: 'eicon-close',
        attributes: {
          tabindex: 0,
          role: 'button',
          'aria-label': elementorFrontend.config.i18n.close + ' (Esc)'
        }
      },
      selectors: {
        preventClose: '.' + this.getSettings('classes.preventClose')
      },
      hide: {
        onClick: true
      }
    });
    modal.on('hide', function () {
      modal.setMessage('');
    });
  },
  showModal: function showModal(options) {
    this.elements.$closeButton = this.getModal().getElements('closeButton');
    this.$buttons = this.elements.$closeButton;
    this.focusedButton = null;
    var self = this,
        defaultOptions = self.getDefaultSettings().modalOptions;
    self.id = options.id;
    self.setSettings('modalOptions', jQuery.extend(defaultOptions, options.modalOptions));
    var modal = self.getModal();
    modal.setID(self.getSettings('modalOptions.id'));

    modal.onShow = function () {
      DialogsManager.getWidgetType('lightbox').prototype.onShow.apply(modal, arguments);
      self.setEntranceAnimation();
    };

    modal.onHide = function () {
      DialogsManager.getWidgetType('lightbox').prototype.onHide.apply(modal, arguments);
      modal.getElements('message').removeClass('animated');

      if (_screenfull.default.isFullscreen) {
        self.deactivateFullscreen();
      }

      self.unbindHotKeys();
    };

    switch (options.type) {
      case 'video':
        self.setVideoContent(options);
        break;

      case 'image':
        var slides = [{
          image: options.url,
          index: 0,
          title: options.title,
          description: options.description
        }];
        options.slideshow = {
          slides: slides,
          swiper: {
            loop: false,
            pagination: false
          }
        };

      case 'slideshow':
        self.setSlideshowContent(options.slideshow);
        break;

      default:
        self.setHTMLContent(options.html);
    }

    modal.show();
  },
  createLightbox: function createLightbox(element) {
    var lightboxData = {};

    if (element.dataset.elementorLightbox) {
      lightboxData = JSON.parse(element.dataset.elementorLightbox);
    }

    if (lightboxData.type && 'slideshow' !== lightboxData.type) {
      this.showModal(lightboxData);
      return;
    }

    if (!element.dataset.elementorLightboxSlideshow) {
      var slideshowID = 'single-img';
      this.showModal({
        type: 'image',
        id: slideshowID,
        url: element.href,
        title: element.dataset.elementorLightboxTitle,
        description: element.dataset.elementorLightboxDescription,
        modalOptions: {
          id: 'elementor-lightbox-slideshow-' + slideshowID
        }
      });
      return;
    }

    var initialSlideURL = element.dataset.elementorLightboxVideo || element.href;
    this.openSlideshow(element.dataset.elementorLightboxSlideshow, initialSlideURL);
  },
  setHTMLContent: function setHTMLContent(html) {
    if (window.elementorCommon) {
      elementorCommon.helpers.hardDeprecated('elementorFrontend.utils.lightbox.setHTMLContent', '3.1.4');
    }

    this.getModal().setMessage(html);
  },
  setVideoContent: function setVideoContent(options) {
    var $ = jQuery,
        classes = this.getSettings('classes'),
        $videoContainer = $('<div>', {
      class: "".concat(classes.videoContainer, " ").concat(classes.preventClose)
    }),
        $videoWrapper = $('<div>', {
      class: classes.videoWrapper
    }),
        modal = this.getModal();
    var $videoElement;

    if ('hosted' === options.videoType) {
      var videoParams = $.extend({
        src: options.url,
        autoplay: ''
      }, options.videoParams);
      $videoElement = $('<video>', videoParams);
    } else {
      var videoURL = options.url.replace('&autoplay=0', '') + '&autoplay=1';
      $videoElement = $('<iframe>', {
        src: videoURL,
        allowfullscreen: 1
      });
    }

    $videoContainer.append($videoWrapper);
    $videoWrapper.append($videoElement);
    modal.setMessage($videoContainer);
    this.setVideoAspectRatio();
    var onHideMethod = modal.onHide;

    modal.onHide = function () {
      onHideMethod();
      this.$buttons = jQuery();
      this.focusedButton = null;
      modal.getElements('message').removeClass('elementor-fit-aspect-ratio');
    };
  },
  getShareLinks: function getShareLinks() {
    var _this = this;

    var i18n = elementorFrontend.config.i18n,
        socialNetworks = {
      facebook: i18n.shareOnFacebook,
      twitter: i18n.shareOnTwitter,
      pinterest: i18n.pinIt
    },
        $ = jQuery,
        classes = this.getSettings('classes'),
        selectors = this.getSettings('selectors'),
        $linkList = $('<div>', {
      class: classes.slideshow.shareLinks
    }),
        $activeSlide = this.getSlide('active'),
        $image = $activeSlide.find(selectors.image),
        videoUrl = $activeSlide.data('elementor-slideshow-video');
    var itemUrl;

    if (videoUrl) {
      itemUrl = videoUrl;
    } else {
      itemUrl = $image.attr('src');
    }

    $.each(socialNetworks, function (key, networkLabel) {
      var $link = $('<a>', {
        href: _this.createShareLink(key, itemUrl),
        target: '_blank'
      }).text(networkLabel);
      $link.prepend($('<i>', {
        class: 'eicon-' + key
      }));
      $linkList.append($link);
    });

    if (!videoUrl) {
      $linkList.append($('<a>', {
        href: itemUrl,
        download: ''
      }).text(i18n.downloadImage).prepend($('<i>', {
        class: 'eicon-download-bold',
        'aria-label': i18n.download
      })));
    }

    return $linkList;
  },
  createShareLink: function createShareLink(networkName, itemUrl) {
    var options = {};

    if ('pinterest' === networkName) {
      options.image = encodeURIComponent(itemUrl);
    } else {
      var hash = elementorFrontend.utils.urlActions.createActionHash('lightbox', {
        id: this.id,
        url: itemUrl
      });
      options.url = encodeURIComponent(location.href.replace(/#.*/, '')) + hash;
    }

    return ShareLink.getNetworkLink(networkName, options);
  },
  getSlideshowHeader: function getSlideshowHeader() {
    var i18n = elementorFrontend.config.i18n,
        $ = jQuery,
        showCounter = 'yes' === elementorFrontend.getKitSettings('lightbox_enable_counter'),
        showFullscreen = 'yes' === elementorFrontend.getKitSettings('lightbox_enable_fullscreen'),
        showZoom = 'yes' === elementorFrontend.getKitSettings('lightbox_enable_zoom'),
        showShare = 'yes' === elementorFrontend.getKitSettings('lightbox_enable_share'),
        classes = this.getSettings('classes'),
        slideshowClasses = classes.slideshow,
        elements = this.elements;

    if (!(showCounter || showFullscreen || showZoom || showShare)) {
      return;
    }

    elements.$header = $('<header>', {
      class: slideshowClasses.header + ' ' + classes.preventClose
    });

    if (showShare) {
      elements.$iconShare = $('<i>', {
        class: slideshowClasses.iconShare,
        role: 'button',
        'aria-label': i18n.share,
        'aria-expanded': false
      }).append($('<span>'));
      var $shareLinks = $('<div>');
      $shareLinks.on('click', function (e) {
        e.stopPropagation();
      });
      elements.$shareMenu = $('<div>', {
        class: slideshowClasses.shareMenu
      }).append($shareLinks);
      elements.$iconShare.add(elements.$shareMenu).on('click', this.toggleShareMenu);
      elements.$header.append(elements.$iconShare, elements.$shareMenu);
      this.$buttons = this.$buttons.add(elements.$iconShare);
    }

    if (showZoom) {
      elements.$iconZoom = $('<i>', {
        class: slideshowClasses.iconZoomIn,
        role: 'switch',
        'aria-checked': false,
        'aria-label': i18n.zoom
      });
      elements.$iconZoom.on('click', this.toggleZoomMode);
      elements.$header.append(elements.$iconZoom);
      this.$buttons = this.$buttons.add(elements.$iconZoom);
    }

    if (showFullscreen) {
      elements.$iconExpand = $('<i>', {
        class: slideshowClasses.iconExpand,
        role: 'switch',
        'aria-checked': false,
        'aria-label': i18n.fullscreen
      }).append($('<span>'), $('<span>'));
      elements.$iconExpand.on('click', this.toggleFullscreen);
      elements.$header.append(elements.$iconExpand);
      this.$buttons = this.$buttons.add(elements.$iconExpand);
    }

    if (showCounter) {
      elements.$counter = $('<span>', {
        class: slideshowClasses.counter
      });
      elements.$header.append(elements.$counter);
    }

    return elements.$header;
  },
  toggleFullscreen: function toggleFullscreen() {
    if (_screenfull.default.isFullscreen) {
      this.deactivateFullscreen();
    } else if (_screenfull.default.isEnabled) {
      this.activateFullscreen();
    }
  },
  toggleZoomMode: function toggleZoomMode() {
    if (1 !== this.swiper.zoom.scale) {
      this.deactivateZoom();
    } else {
      this.activateZoom();
    }
  },
  toggleShareMenu: function toggleShareMenu() {
    if (this.shareMode) {
      this.deactivateShareMode();
    } else {
      this.elements.$shareMenu.html(this.getShareLinks());
      this.activateShareMode();
    }
  },
  activateShareMode: function activateShareMode() {
    var classes = this.getSettings('classes');
    this.elements.$container.addClass(classes.slideshow.shareMode);
    this.elements.$iconShare.attr('aria-expanded', true); // Prevent swiper interactions while in share mode

    this.swiper.detachEvents(); // Temporarily replace tabbable buttons with share-menu items

    this.$originalButtons = this.$buttons;
    this.$buttons = this.elements.$iconShare.add(this.elements.$shareMenu.find('a'));
    this.shareMode = true;
  },
  deactivateShareMode: function deactivateShareMode() {
    var classes = this.getSettings('classes');
    this.elements.$container.removeClass(classes.slideshow.shareMode);
    this.elements.$iconShare.attr('aria-expanded', false);
    this.swiper.attachEvents();
    this.$buttons = this.$originalButtons;
    this.shareMode = false;
  },
  activateFullscreen: function activateFullscreen() {
    var classes = this.getSettings('classes');

    _screenfull.default.request(this.elements.$container.parents('.dialog-widget')[0]);

    this.elements.$iconExpand.removeClass(classes.slideshow.iconExpand).addClass(classes.slideshow.iconShrink).attr('aria-checked', 'true');
    this.elements.$container.addClass(classes.slideshow.fullscreenMode);
  },
  deactivateFullscreen: function deactivateFullscreen() {
    var classes = this.getSettings('classes');

    _screenfull.default.exit();

    this.elements.$iconExpand.removeClass(classes.slideshow.iconShrink).addClass(classes.slideshow.iconExpand).attr('aria-checked', 'false');
    this.elements.$container.removeClass(classes.slideshow.fullscreenMode);
  },
  activateZoom: function activateZoom() {
    var swiper = this.swiper,
        elements = this.elements,
        classes = this.getSettings('classes');
    swiper.zoom.in();
    swiper.allowSlideNext = false;
    swiper.allowSlidePrev = false;
    swiper.allowTouchMove = false;
    elements.$container.addClass(classes.slideshow.zoomMode);
    elements.$iconZoom.removeClass(classes.slideshow.iconZoomIn).addClass(classes.slideshow.iconZoomOut);
  },
  deactivateZoom: function deactivateZoom() {
    var swiper = this.swiper,
        elements = this.elements,
        classes = this.getSettings('classes');
    swiper.zoom.out();
    swiper.allowSlideNext = true;
    swiper.allowSlidePrev = true;
    swiper.allowTouchMove = true;
    elements.$container.removeClass(classes.slideshow.zoomMode);
    elements.$iconZoom.removeClass(classes.slideshow.iconZoomOut).addClass(classes.slideshow.iconZoomIn);
  },
  getSlideshowFooter: function getSlideshowFooter() {
    var $ = jQuery,
        classes = this.getSettings('classes'),
        $footer = $('<footer>', {
      class: classes.slideshow.footer + ' ' + classes.preventClose
    }),
        $title = $('<div>', {
      class: classes.slideshow.title
    }),
        $description = $('<div>', {
      class: classes.slideshow.description
    });
    $footer.append($title, $description);
    return $footer;
  },
  setSlideshowContent: function setSlideshowContent(options) {
    var _this2 = this;

    var i18n = elementorFrontend.config.i18n,
        $ = jQuery,
        isSingleSlide = 1 === options.slides.length,
        hasTitle = '' !== elementorFrontend.getKitSettings('lightbox_title_src'),
        hasDescription = '' !== elementorFrontend.getKitSettings('lightbox_description_src'),
        showFooter = hasTitle || hasDescription,
        classes = this.getSettings('classes'),
        slideshowClasses = classes.slideshow,
        $container = $('<div>', {
      class: slideshowClasses.container
    }),
        $slidesWrapper = $('<div>', {
      class: slideshowClasses.slidesWrapper
    });
    var $prevButton, $nextButton;
    options.slides.forEach(function (slide) {
      var slideClass = slideshowClasses.slide + ' ' + classes.item;

      if (slide.video) {
        slideClass += ' ' + classes.video;
      }

      var $slide = $('<div>', {
        class: slideClass
      });

      if (slide.video) {
        $slide.attr('data-elementor-slideshow-video', slide.video);
        var $playIcon = $('<div>', {
          class: classes.playButton
        }).html($('<i>', {
          class: classes.playButtonIcon,
          'aria-label': i18n.playVideo
        }));
        $slide.append($playIcon);
      } else {
        var $zoomContainer = $('<div>', {
          class: 'swiper-zoom-container'
        }),
            $slidePlaceholder = $('<div class="swiper-lazy-preloader"></div>'),
            imageAttributes = {
          'data-src': slide.image,
          class: classes.image + ' ' + classes.preventClose + ' swiper-lazy'
        };

        if (slide.title) {
          imageAttributes['data-title'] = slide.title;
          imageAttributes.alt = slide.title;
        }

        if (slide.description) {
          imageAttributes['data-description'] = slide.description;
          imageAttributes.alt += ' - ' + slide.description;
        }

        var $slideImage = $('<img>', imageAttributes);
        $zoomContainer.append([$slideImage, $slidePlaceholder]);
        $slide.append($zoomContainer);
      }

      $slidesWrapper.append($slide);
    });
    this.elements.$container = $container;
    this.elements.$header = this.getSlideshowHeader();
    $container.prepend(this.elements.$header).append($slidesWrapper);

    if (!isSingleSlide) {
      $prevButton = $('<div>', {
        class: slideshowClasses.prevButton + ' ' + classes.preventClose,
        'aria-label': i18n.previous
      }).html($('<i>', {
        class: slideshowClasses.prevButtonIcon
      }));
      $nextButton = $('<div>', {
        class: slideshowClasses.nextButton + ' ' + classes.preventClose,
        'aria-label': i18n.next
      }).html($('<i>', {
        class: slideshowClasses.nextButtonIcon
      }));
      $container.append($nextButton, $prevButton);
      this.$buttons = this.$buttons.add($nextButton).add($prevButton);
    }

    if (showFooter) {
      this.elements.$footer = this.getSlideshowFooter();
      $container.append(this.elements.$footer);
    }

    this.setSettings('hideUiTimeout', '');
    $container.on('click mousemove keypress', this.showLightboxUi);
    var modal = this.getModal();
    modal.setMessage($container);
    var onShowMethod = modal.onShow;
    modal.onShow = /*#__PURE__*/(0, _asyncToGenerator2.default)( /*#__PURE__*/_regenerator.default.mark(function _callee() {
      var swiperOptions, Swiper;
      return _regenerator.default.wrap(function _callee$(_context) {
        while (1) {
          switch (_context.prev = _context.next) {
            case 0:
              onShowMethod();
              swiperOptions = {
                pagination: {
                  el: '.' + slideshowClasses.counter,
                  type: 'fraction'
                },
                on: {
                  slideChangeTransitionEnd: _this2.onSlideChange
                },
                lazy: {
                  loadPrevNext: true
                },
                zoom: true,
                spaceBetween: 100,
                grabCursor: true,
                runCallbacksOnInit: false,
                loop: true,
                keyboard: true,
                handleElementorBreakpoints: true
              };

              if (!isSingleSlide) {
                swiperOptions.navigation = {
                  prevEl: $prevButton,
                  nextEl: $nextButton
                };
              }

              if (options.swiper) {
                $.extend(swiperOptions, options.swiper);
              }

              Swiper = elementorFrontend.utils.swiper;
              _context.next = 7;
              return new Swiper($container, swiperOptions);

            case 7:
              _this2.swiper = _context.sent;
              // Expose the swiper instance in the frontend
              $container.data('swiper', _this2.swiper);

              _this2.setVideoAspectRatio();

              _this2.playSlideVideo();

              if (showFooter) {
                _this2.updateFooterText();
              }

              _this2.bindHotKeys();

              _this2.makeButtonsAccessible();

            case 14:
            case "end":
              return _context.stop();
          }
        }
      }, _callee);
    }));
  },
  makeButtonsAccessible: function makeButtonsAccessible() {
    this.$buttons.attr('tabindex', 0).on('keypress', function (event) {
      var ENTER_KEY = 13,
          SPACE_KEY = 32;

      if (ENTER_KEY === event.which || SPACE_KEY === event.which) {
        jQuery(event.currentTarget).trigger('click');
      }
    });
  },
  showLightboxUi: function showLightboxUi() {
    var _this3 = this;

    var slideshowClasses = this.getSettings('classes').slideshow;
    this.elements.$container.removeClass(slideshowClasses.hideUiVisibility);
    clearTimeout(this.getSettings('hideUiTimeout'));
    this.setSettings('hideUiTimeout', setTimeout(function () {
      if (!_this3.shareMode) {
        _this3.elements.$container.addClass(slideshowClasses.hideUiVisibility);
      }
    }, 3500));
  },
  bindHotKeys: function bindHotKeys() {
    this.getModal().getElements('window').on('keydown', this.activeKeyDown);
  },
  unbindHotKeys: function unbindHotKeys() {
    this.getModal().getElements('window').off('keydown', this.activeKeyDown);
  },
  activeKeyDown: function activeKeyDown(event) {
    this.showLightboxUi();
    var TAB_KEY = 9;

    if (event.which === TAB_KEY) {
      var $buttons = this.$buttons;
      var focusedButton,
          isFirst = false,
          isLast = false;
      $buttons.each(function (index) {
        var item = $buttons[index];

        if (jQuery(item).is(':focus')) {
          focusedButton = item;
          isFirst = 0 === index;
          isLast = $buttons.length - 1 === index;
          return false;
        }
      });

      if (event.shiftKey) {
        if (isFirst) {
          event.preventDefault();
          $buttons.last().trigger('focus');
        }
      } else if (isLast || !focusedButton) {
        event.preventDefault();
        $buttons.first().trigger('focus');
      }
    }
  },
  setVideoAspectRatio: function setVideoAspectRatio(aspectRatio) {
    aspectRatio = aspectRatio || this.getSettings('modalOptions.videoAspectRatio');
    var $widgetContent = this.getModal().getElements('widgetContent'),
        oldAspectRatio = this.oldAspectRatio,
        aspectRatioClass = this.getSettings('classes.aspectRatio');
    this.oldAspectRatio = aspectRatio;

    if (oldAspectRatio) {
      $widgetContent.removeClass(aspectRatioClass.replace('%s', oldAspectRatio));
    }

    if (aspectRatio) {
      $widgetContent.addClass(aspectRatioClass.replace('%s', aspectRatio));
    }
  },
  getSlide: function getSlide(slideState) {
    return jQuery(this.swiper.slides).filter(this.getSettings('selectors.slideshow.' + slideState + 'Slide'));
  },
  updateFooterText: function updateFooterText() {
    if (!this.elements.$footer) {
      return;
    }

    var classes = this.getSettings('classes'),
        $activeSlide = this.getSlide('active'),
        $image = $activeSlide.find('.elementor-lightbox-image'),
        titleText = $image.data('title'),
        descriptionText = $image.data('description'),
        $title = this.elements.$footer.find('.' + classes.slideshow.title),
        $description = this.elements.$footer.find('.' + classes.slideshow.description);
    $title.text(titleText || '');
    $description.text(descriptionText || '');
  },
  playSlideVideo: function playSlideVideo() {
    var _this4 = this;

    var $activeSlide = this.getSlide('active'),
        videoURL = $activeSlide.data('elementor-slideshow-video');

    if (!videoURL) {
      return;
    }

    var classes = this.getSettings('classes'),
        $videoContainer = jQuery('<div>', {
      class: classes.videoContainer + ' ' + classes.invisible
    }),
        $videoWrapper = jQuery('<div>', {
      class: classes.videoWrapper
    }),
        $playIcon = $activeSlide.children('.' + classes.playButton);
    var videoType, apiProvider;
    $videoContainer.append($videoWrapper);
    $activeSlide.append($videoContainer);

    if (-1 !== videoURL.indexOf('vimeo.com')) {
      videoType = 'vimeo';
      apiProvider = elementorFrontend.utils.vimeo;
    } else if (videoURL.match(/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com)/)) {
      videoType = 'youtube';
      apiProvider = elementorFrontend.utils.youtube;
    }

    var videoID = apiProvider.getVideoIDFromURL(videoURL);
    apiProvider.onApiReady(function (apiObject) {
      if ('youtube' === videoType) {
        _this4.prepareYTVideo(apiObject, videoID, $videoContainer, $videoWrapper, $playIcon);
      } else if ('vimeo' === videoType) {
        _this4.prepareVimeoVideo(apiObject, videoID, $videoContainer, $videoWrapper, $playIcon);
      }
    });
    $playIcon.addClass(classes.playing).removeClass(classes.hidden);
  },
  prepareYTVideo: function prepareYTVideo(YT, videoID, $videoContainer, $videoWrapper, $playIcon) {
    var _this5 = this;

    var classes = this.getSettings('classes'),
        $videoPlaceholderElement = jQuery('<div>');
    var startStateCode = YT.PlayerState.PLAYING;
    $videoWrapper.append($videoPlaceholderElement); // Since version 67, Chrome doesn't fire the `PLAYING` state at start time

    if (window.chrome) {
      startStateCode = YT.PlayerState.UNSTARTED;
    }

    $videoContainer.addClass('elementor-loading' + ' ' + classes.invisible);
    this.player = new YT.Player($videoPlaceholderElement[0], {
      videoId: videoID,
      events: {
        onReady: function onReady() {
          $playIcon.addClass(classes.hidden);
          $videoContainer.removeClass(classes.invisible);

          _this5.player.playVideo();
        },
        onStateChange: function onStateChange(event) {
          if (event.data === startStateCode) {
            $videoContainer.removeClass('elementor-loading' + ' ' + classes.invisible);
          }
        }
      },
      playerVars: {
        controls: 0,
        rel: 0
      }
    });
  },
  prepareVimeoVideo: function prepareVimeoVideo(Vimeo, videoId, $videoContainer, $videoWrapper, $playIcon) {
    var classes = this.getSettings('classes'),
        vimeoOptions = {
      id: videoId,
      autoplay: true,
      transparent: false,
      playsinline: false
    };
    this.player = new Vimeo.Player($videoWrapper, vimeoOptions);
    this.player.ready().then(function () {
      $playIcon.addClass(classes.hidden);
      $videoContainer.removeClass(classes.invisible);
    });
  },
  setEntranceAnimation: function setEntranceAnimation(animation) {
    animation = animation || elementorFrontend.getCurrentDeviceSetting(this.getSettings('modalOptions'), 'entranceAnimation');
    var $widgetMessage = this.getModal().getElements('message');

    if (this.oldAnimation) {
      $widgetMessage.removeClass(this.oldAnimation);
    }

    this.oldAnimation = animation;

    if (animation) {
      $widgetMessage.addClass('animated ' + animation);
    }
  },
  openSlideshow: function openSlideshow(slideshowID, initialSlideURL) {
    var $allSlideshowLinks = jQuery(this.getSettings('selectors.links')).filter(function (index, element) {
      var $element = jQuery(element);
      return slideshowID === element.dataset.elementorLightboxSlideshow && !$element.parent('.swiper-slide-duplicate').length && !$element.parents('.slick-cloned').length;
    });
    var slides = [];
    var initialSlideIndex = 0;
    $allSlideshowLinks.each(function () {
      var slideVideo = this.dataset.elementorLightboxVideo;
      var slideIndex = this.dataset.elementorLightboxIndex;

      if (undefined === slideIndex) {
        slideIndex = $allSlideshowLinks.index(this);
      }

      if (initialSlideURL === this.href || slideVideo && initialSlideURL === slideVideo) {
        initialSlideIndex = slideIndex;
      }

      var slideData = {
        image: this.href,
        index: slideIndex,
        title: this.dataset.elementorLightboxTitle,
        description: this.dataset.elementorLightboxDescription
      };

      if (slideVideo) {
        slideData.video = slideVideo;
      }

      slides.push(slideData);
    });
    slides.sort(function (a, b) {
      return a.index - b.index;
    });
    this.showModal({
      type: 'slideshow',
      id: slideshowID,
      modalOptions: {
        id: 'elementor-lightbox-slideshow-' + slideshowID
      },
      slideshow: {
        slides: slides,
        swiper: {
          initialSlide: +initialSlideIndex
        }
      }
    });
  },
  onSlideChange: function onSlideChange() {
    this.getSlide('prev').add(this.getSlide('next')).add(this.getSlide('active')).find('.' + this.getSettings('classes.videoWrapper')).remove();
    this.playSlideVideo();
    this.updateFooterText();
  }
});

/***/ }),

/***/ "../assets/dev/js/frontend/utils/lightbox/screenfull.js":
/*!**************************************************************!*\
  !*** ../assets/dev/js/frontend/utils/lightbox/screenfull.js ***!
  \**************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: module, __webpack_require__ */
/*! CommonJS bailout: module.exports is used directly at 13:52-66 */
/*! CommonJS bailout: module.exports is used directly at 104:6-20 */
/*! CommonJS bailout: module.exports is used directly at 138:4-18 */
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime-corejs2/helpers/interopRequireDefault */ "../node_modules/@babel/runtime-corejs2/helpers/interopRequireDefault.js");

var _defineProperties = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/core-js/object/define-properties */ "../node_modules/@babel/runtime-corejs2/core-js/object/define-properties.js"));

var _promise = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/core-js/promise */ "../node_modules/@babel/runtime-corejs2/core-js/promise.js"));

(function () {
  'use strict';

  var document = typeof window !== 'undefined' && typeof window.document !== 'undefined' ? window.document : {};
  var isCommonjs =  true && module.exports;

  var fn = function () {
    var val;
    var fnMap = [['requestFullscreen', 'exitFullscreen', 'fullscreenElement', 'fullscreenEnabled', 'fullscreenchange', 'fullscreenerror'], // New WebKit
    ['webkitRequestFullscreen', 'webkitExitFullscreen', 'webkitFullscreenElement', 'webkitFullscreenEnabled', 'webkitfullscreenchange', 'webkitfullscreenerror'], // Old WebKit
    ['webkitRequestFullScreen', 'webkitCancelFullScreen', 'webkitCurrentFullScreenElement', 'webkitCancelFullScreen', 'webkitfullscreenchange', 'webkitfullscreenerror'], ['mozRequestFullScreen', 'mozCancelFullScreen', 'mozFullScreenElement', 'mozFullScreenEnabled', 'mozfullscreenchange', 'mozfullscreenerror'], ['msRequestFullscreen', 'msExitFullscreen', 'msFullscreenElement', 'msFullscreenEnabled', 'MSFullscreenChange', 'MSFullscreenError']];
    var i = 0;
    var l = fnMap.length;
    var ret = {};

    for (; i < l; i++) {
      val = fnMap[i];

      if (val && val[1] in document) {
        var valLength = val.length;

        for (i = 0; i < valLength; i++) {
          ret[fnMap[0][i]] = val[i];
        }

        return ret;
      }
    }

    return false;
  }();

  var eventNameMap = {
    change: fn.fullscreenchange,
    error: fn.fullscreenerror
  };
  var screenfull = {
    request: function request(element) {
      return new _promise.default(function (resolve, reject) {
        var onFullScreenEntered = function () {
          this.off('change', onFullScreenEntered);
          resolve();
        }.bind(this);

        this.on('change', onFullScreenEntered);
        element = element || document.documentElement;

        _promise.default.resolve(element[fn.requestFullscreen]()).catch(reject);
      }.bind(this));
    },
    exit: function exit() {
      return new _promise.default(function (resolve, reject) {
        if (!this.isFullscreen) {
          resolve();
          return;
        }

        var onFullScreenExit = function () {
          this.off('change', onFullScreenExit);
          resolve();
        }.bind(this);

        this.on('change', onFullScreenExit);

        _promise.default.resolve(document[fn.exitFullscreen]()).catch(reject);
      }.bind(this));
    },
    toggle: function toggle(element) {
      return this.isFullscreen ? this.exit() : this.request(element);
    },
    onchange: function onchange(callback) {
      this.on('change', callback);
    },
    onerror: function onerror(callback) {
      this.on('error', callback);
    },
    on: function on(event, callback) {
      var eventName = eventNameMap[event];

      if (eventName) {
        document.addEventListener(eventName, callback, false);
      }
    },
    off: function off(event, callback) {
      var eventName = eventNameMap[event];

      if (eventName) {
        document.removeEventListener(eventName, callback, false);
      }
    },
    raw: fn
  };

  if (!fn) {
    if (isCommonjs) {
      module.exports = {
        isEnabled: false
      };
    } else {
      window.screenfull = {
        isEnabled: false
      };
    }

    return;
  }

  (0, _defineProperties.default)(screenfull, {
    isFullscreen: {
      get: function get() {
        return Boolean(document[fn.fullscreenElement]);
      }
    },
    element: {
      enumerable: true,
      get: function get() {
        return document[fn.fullscreenElement];
      }
    },
    isEnabled: {
      enumerable: true,
      get: function get() {
        // Coerce to boolean in case of old WebKit
        return Boolean(document[fn.fullscreenEnabled]);
      }
    }
  });

  if (isCommonjs) {
    module.exports = screenfull;
  } else {
    window.screenfull = screenfull;
  }
})();

/***/ }),

/***/ "../node_modules/core-js/modules/es6.regexp.match.js":
/*!***********************************************************!*\
  !*** ../node_modules/core-js/modules/es6.regexp.match.js ***!
  \***********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: __webpack_require__ */
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var anObject = __webpack_require__(/*! ./_an-object */ "../node_modules/core-js/modules/_an-object.js");
var toLength = __webpack_require__(/*! ./_to-length */ "../node_modules/core-js/modules/_to-length.js");
var advanceStringIndex = __webpack_require__(/*! ./_advance-string-index */ "../node_modules/core-js/modules/_advance-string-index.js");
var regExpExec = __webpack_require__(/*! ./_regexp-exec-abstract */ "../node_modules/core-js/modules/_regexp-exec-abstract.js");

// @@match logic
__webpack_require__(/*! ./_fix-re-wks */ "../node_modules/core-js/modules/_fix-re-wks.js")('match', 1, function (defined, MATCH, $match, maybeCallNative) {
  return [
    // `String.prototype.match` method
    // https://tc39.github.io/ecma262/#sec-string.prototype.match
    function match(regexp) {
      var O = defined(this);
      var fn = regexp == undefined ? undefined : regexp[MATCH];
      return fn !== undefined ? fn.call(regexp, O) : new RegExp(regexp)[MATCH](String(O));
    },
    // `RegExp.prototype[@@match]` method
    // https://tc39.github.io/ecma262/#sec-regexp.prototype-@@match
    function (regexp) {
      var res = maybeCallNative($match, regexp, this);
      if (res.done) return res.value;
      var rx = anObject(regexp);
      var S = String(this);
      if (!rx.global) return regExpExec(rx, S);
      var fullUnicode = rx.unicode;
      rx.lastIndex = 0;
      var A = [];
      var n = 0;
      var result;
      while ((result = regExpExec(rx, S)) !== null) {
        var matchStr = String(result[0]);
        A[n] = matchStr;
        if (matchStr === '') rx.lastIndex = advanceStringIndex(S, toLength(rx.lastIndex), fullUnicode);
        n++;
      }
      return n === 0 ? null : A;
    }
  ];
});


/***/ }),

/***/ "../node_modules/core-js/modules/es6.regexp.replace.js":
/*!*************************************************************!*\
  !*** ../node_modules/core-js/modules/es6.regexp.replace.js ***!
  \*************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: __webpack_require__ */
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";


var anObject = __webpack_require__(/*! ./_an-object */ "../node_modules/core-js/modules/_an-object.js");
var toObject = __webpack_require__(/*! ./_to-object */ "../node_modules/core-js/modules/_to-object.js");
var toLength = __webpack_require__(/*! ./_to-length */ "../node_modules/core-js/modules/_to-length.js");
var toInteger = __webpack_require__(/*! ./_to-integer */ "../node_modules/core-js/modules/_to-integer.js");
var advanceStringIndex = __webpack_require__(/*! ./_advance-string-index */ "../node_modules/core-js/modules/_advance-string-index.js");
var regExpExec = __webpack_require__(/*! ./_regexp-exec-abstract */ "../node_modules/core-js/modules/_regexp-exec-abstract.js");
var max = Math.max;
var min = Math.min;
var floor = Math.floor;
var SUBSTITUTION_SYMBOLS = /\$([$&`']|\d\d?|<[^>]*>)/g;
var SUBSTITUTION_SYMBOLS_NO_NAMED = /\$([$&`']|\d\d?)/g;

var maybeToString = function (it) {
  return it === undefined ? it : String(it);
};

// @@replace logic
__webpack_require__(/*! ./_fix-re-wks */ "../node_modules/core-js/modules/_fix-re-wks.js")('replace', 2, function (defined, REPLACE, $replace, maybeCallNative) {
  return [
    // `String.prototype.replace` method
    // https://tc39.github.io/ecma262/#sec-string.prototype.replace
    function replace(searchValue, replaceValue) {
      var O = defined(this);
      var fn = searchValue == undefined ? undefined : searchValue[REPLACE];
      return fn !== undefined
        ? fn.call(searchValue, O, replaceValue)
        : $replace.call(String(O), searchValue, replaceValue);
    },
    // `RegExp.prototype[@@replace]` method
    // https://tc39.github.io/ecma262/#sec-regexp.prototype-@@replace
    function (regexp, replaceValue) {
      var res = maybeCallNative($replace, regexp, this, replaceValue);
      if (res.done) return res.value;

      var rx = anObject(regexp);
      var S = String(this);
      var functionalReplace = typeof replaceValue === 'function';
      if (!functionalReplace) replaceValue = String(replaceValue);
      var global = rx.global;
      if (global) {
        var fullUnicode = rx.unicode;
        rx.lastIndex = 0;
      }
      var results = [];
      while (true) {
        var result = regExpExec(rx, S);
        if (result === null) break;
        results.push(result);
        if (!global) break;
        var matchStr = String(result[0]);
        if (matchStr === '') rx.lastIndex = advanceStringIndex(S, toLength(rx.lastIndex), fullUnicode);
      }
      var accumulatedResult = '';
      var nextSourcePosition = 0;
      for (var i = 0; i < results.length; i++) {
        result = results[i];
        var matched = String(result[0]);
        var position = max(min(toInteger(result.index), S.length), 0);
        var captures = [];
        // NOTE: This is equivalent to
        //   captures = result.slice(1).map(maybeToString)
        // but for some reason `nativeSlice.call(result, 1, result.length)` (called in
        // the slice polyfill when slicing native arrays) "doesn't work" in safari 9 and
        // causes a crash (https://pastebin.com/N21QzeQA) when trying to debug it.
        for (var j = 1; j < result.length; j++) captures.push(maybeToString(result[j]));
        var namedCaptures = result.groups;
        if (functionalReplace) {
          var replacerArgs = [matched].concat(captures, position, S);
          if (namedCaptures !== undefined) replacerArgs.push(namedCaptures);
          var replacement = String(replaceValue.apply(undefined, replacerArgs));
        } else {
          replacement = getSubstitution(matched, S, position, captures, namedCaptures, replaceValue);
        }
        if (position >= nextSourcePosition) {
          accumulatedResult += S.slice(nextSourcePosition, position) + replacement;
          nextSourcePosition = position + matched.length;
        }
      }
      return accumulatedResult + S.slice(nextSourcePosition);
    }
  ];

    // https://tc39.github.io/ecma262/#sec-getsubstitution
  function getSubstitution(matched, str, position, captures, namedCaptures, replacement) {
    var tailPos = position + matched.length;
    var m = captures.length;
    var symbols = SUBSTITUTION_SYMBOLS_NO_NAMED;
    if (namedCaptures !== undefined) {
      namedCaptures = toObject(namedCaptures);
      symbols = SUBSTITUTION_SYMBOLS;
    }
    return $replace.call(replacement, symbols, function (match, ch) {
      var capture;
      switch (ch.charAt(0)) {
        case '$': return '$';
        case '&': return matched;
        case '`': return str.slice(0, position);
        case "'": return str.slice(tailPos);
        case '<':
          capture = namedCaptures[ch.slice(1, -1)];
          break;
        default: // \d\d?
          var n = +ch;
          if (n === 0) return match;
          if (n > m) {
            var f = floor(n / 10);
            if (f === 0) return match;
            if (f <= m) return captures[f - 1] === undefined ? ch.charAt(1) : captures[f - 1] + ch.charAt(1);
            return match;
          }
          capture = captures[n - 1];
      }
      return capture === undefined ? '' : capture;
    });
  }
});


/***/ })

}]);
//# sourceMappingURL=lightbox.d4a78ec3282d5785504d.bundle.js.map