/*! elementor - v3.2.3 - 05-05-2021 */
(self["webpackChunkelementor"] = self["webpackChunkelementor"] || []).push([["video"],{

/***/ "../assets/dev/js/frontend/handlers/video.js":
/*!***************************************************!*\
  !*** ../assets/dev/js/frontend/handlers/video.js ***!
  \***************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/*! CommonJS bailout: exports is used directly at 7:23-30 */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _interopRequireDefault = __webpack_require__(/*! @babel/runtime-corejs2/helpers/interopRequireDefault */ "../node_modules/@babel/runtime-corejs2/helpers/interopRequireDefault.js");

var _Object$defineProperty = __webpack_require__(/*! @babel/runtime-corejs2/core-js/object/define-property */ "../node_modules/@babel/runtime-corejs2/core-js/object/define-property.js");

_Object$defineProperty(exports, "__esModule", {
  value: true
});

exports.default = void 0;

var _regenerator = _interopRequireDefault(__webpack_require__(/*! @babel/runtime/regenerator */ "../node_modules/@babel/runtime/regenerator/index.js"));

__webpack_require__(/*! regenerator-runtime/runtime.js */ "../node_modules/regenerator-runtime/runtime.js");

var _asyncToGenerator2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/asyncToGenerator */ "../node_modules/@babel/runtime-corejs2/helpers/asyncToGenerator.js"));

__webpack_require__(/*! core-js/modules/es7.array.includes.js */ "../node_modules/core-js/modules/es7.array.includes.js");

__webpack_require__(/*! core-js/modules/es6.string.includes.js */ "../node_modules/core-js/modules/es6.string.includes.js");

__webpack_require__(/*! core-js/modules/es6.regexp.replace.js */ "../node_modules/core-js/modules/es6.regexp.replace.js");

__webpack_require__(/*! core-js/modules/es6.array.find.js */ "../node_modules/core-js/modules/es6.array.find.js");

var _classCallCheck2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/classCallCheck */ "../node_modules/@babel/runtime-corejs2/helpers/classCallCheck.js"));

var _createClass2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/createClass */ "../node_modules/@babel/runtime-corejs2/helpers/createClass.js"));

var _get2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/get */ "../node_modules/@babel/runtime-corejs2/helpers/get.js"));

var _getPrototypeOf2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/getPrototypeOf */ "../node_modules/@babel/runtime-corejs2/helpers/getPrototypeOf.js"));

var _inherits2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/inherits */ "../node_modules/@babel/runtime-corejs2/helpers/inherits.js"));

var _createSuper2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/createSuper */ "../node_modules/@babel/runtime-corejs2/helpers/createSuper.js"));

var Video = /*#__PURE__*/function (_elementorModules$fro) {
  (0, _inherits2.default)(Video, _elementorModules$fro);

  var _super = (0, _createSuper2.default)(Video);

  function Video() {
    (0, _classCallCheck2.default)(this, Video);
    return _super.apply(this, arguments);
  }

  (0, _createClass2.default)(Video, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          imageOverlay: '.elementor-custom-embed-image-overlay',
          video: '.elementor-video',
          videoIframe: '.elementor-video-iframe'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      return {
        $imageOverlay: this.$element.find(selectors.imageOverlay),
        $video: this.$element.find(selectors.video),
        $videoIframe: this.$element.find(selectors.videoIframe)
      };
    }
  }, {
    key: "handleVideo",
    value: function handleVideo() {
      var _this = this;

      if (this.getElementSettings('lightbox')) {
        return;
      }

      if ('youtube' === this.getElementSettings('video_type')) {
        this.apiProvider.onApiReady(function (apiObject) {
          _this.elements.$imageOverlay.remove();

          _this.prepareYTVideo(apiObject, true);
        });
      } else {
        this.elements.$imageOverlay.remove();
        this.playVideo();
      }
    }
  }, {
    key: "playVideo",
    value: function playVideo() {
      if (this.elements.$video.length) {
        // this.youtubePlayer exists only for YouTube videos, and its play function is different.
        if (this.youtubePlayer) {
          this.youtubePlayer.playVideo();
        } else {
          this.elements.$video[0].play();
        }

        return;
      }

      var $videoIframe = this.elements.$videoIframe,
          lazyLoad = $videoIframe.data('lazy-load');

      if (lazyLoad) {
        $videoIframe.attr('src', lazyLoad);
      }

      var newSourceUrl = $videoIframe[0].src.replace('&autoplay=0', '');
      $videoIframe[0].src = newSourceUrl + '&autoplay=1';

      if ($videoIframe[0].src.includes('vimeo.com')) {
        var videoSrc = $videoIframe[0].src,
            timeMatch = /#t=[^&]*/.exec(videoSrc); // Param '#t=' must be last in the URL

        $videoIframe[0].src = videoSrc.slice(0, timeMatch.index) + videoSrc.slice(timeMatch.index + timeMatch[0].length) + timeMatch[0];
      }
    }
  }, {
    key: "animateVideo",
    value: function () {
      var _animateVideo = (0, _asyncToGenerator2.default)( /*#__PURE__*/_regenerator.default.mark(function _callee() {
        var lightbox;
        return _regenerator.default.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                _context.next = 2;
                return elementorFrontend.utils.lightbox;

              case 2:
                lightbox = _context.sent;
                lightbox.setEntranceAnimation(this.getCurrentDeviceSetting('lightbox_content_animation'));

              case 4:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, this);
      }));

      function animateVideo() {
        return _animateVideo.apply(this, arguments);
      }

      return animateVideo;
    }()
  }, {
    key: "handleAspectRatio",
    value: function () {
      var _handleAspectRatio = (0, _asyncToGenerator2.default)( /*#__PURE__*/_regenerator.default.mark(function _callee2() {
        var lightbox;
        return _regenerator.default.wrap(function _callee2$(_context2) {
          while (1) {
            switch (_context2.prev = _context2.next) {
              case 0:
                _context2.next = 2;
                return elementorFrontend.utils.lightbox;

              case 2:
                lightbox = _context2.sent;
                lightbox.setVideoAspectRatio(this.getElementSettings('aspect_ratio'));

              case 4:
              case "end":
                return _context2.stop();
            }
          }
        }, _callee2, this);
      }));

      function handleAspectRatio() {
        return _handleAspectRatio.apply(this, arguments);
      }

      return handleAspectRatio;
    }()
  }, {
    key: "hideLightbox",
    value: function () {
      var _hideLightbox = (0, _asyncToGenerator2.default)( /*#__PURE__*/_regenerator.default.mark(function _callee3() {
        var lightbox;
        return _regenerator.default.wrap(function _callee3$(_context3) {
          while (1) {
            switch (_context3.prev = _context3.next) {
              case 0:
                _context3.next = 2;
                return elementorFrontend.utils.lightbox;

              case 2:
                lightbox = _context3.sent;
                lightbox.getModal().hide();

              case 4:
              case "end":
                return _context3.stop();
            }
          }
        }, _callee3);
      }));

      function hideLightbox() {
        return _hideLightbox.apply(this, arguments);
      }

      return hideLightbox;
    }()
  }, {
    key: "prepareYTVideo",
    value: function prepareYTVideo(YT, onOverlayClick) {
      var _this2 = this;

      var elementSettings = this.getElementSettings(),
          playerOptions = {
        videoId: this.videoID,
        events: {
          onReady: function onReady() {
            if (elementSettings.mute) {
              _this2.youtubePlayer.mute();
            }

            if (elementSettings.autoplay || onOverlayClick) {
              _this2.youtubePlayer.playVideo();
            }
          },
          onStateChange: function onStateChange(event) {
            if (event.data === YT.PlayerState.ENDED && elementSettings.loop) {
              _this2.youtubePlayer.seekTo(elementSettings.start || 0);
            }
          }
        },
        playerVars: {
          controls: elementSettings.controls ? 1 : 0,
          rel: elementSettings.rel ? 1 : 0,
          playsinline: elementSettings.play_on_mobile ? 1 : 0,
          modestbranding: elementSettings.modestbranding ? 1 : 0,
          autoplay: elementSettings.autoplay ? 1 : 0,
          start: elementSettings.start,
          end: elementSettings.end
        }
      }; // To handle CORS issues, when the default host is changed, the origin parameter has to be set.

      if (elementSettings.yt_privacy) {
        playerOptions.host = 'https://www.youtube-nocookie.com';
        playerOptions.origin = window.location.hostname;
      }

      this.youtubePlayer = new YT.Player(this.elements.$video[0], playerOptions);
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      this.elements.$imageOverlay.on('click', this.handleVideo.bind(this));
    }
  }, {
    key: "onInit",
    value: function onInit() {
      var _this3 = this;

      (0, _get2.default)((0, _getPrototypeOf2.default)(Video.prototype), "onInit", this).call(this);
      var elementSettings = this.getElementSettings();

      if ('youtube' !== elementSettings.video_type) {
        // Currently the only API integration in the Video widget is for the YT API
        return;
      }

      this.apiProvider = elementorFrontend.utils.youtube;
      this.videoID = this.apiProvider.getVideoIDFromURL(elementSettings.youtube_url); // If there is an image overlay, the YouTube video prep method will be triggered on click

      if (!this.videoID) {
        return;
      } // If the user is using an image overlay, loading the API happens on overlay click instead of on init.


      if (elementSettings.show_image_overlay && elementSettings.image_overlay.url) {
        return;
      }

      if (elementSettings.lazy_load) {
        this.intersectionObserver = elementorModules.utils.Scroll.scrollObserver({
          callback: function callback(event) {
            if (event.isInViewport) {
              _this3.intersectionObserver.unobserve(_this3.elements.$video.parent()[0]);

              _this3.apiProvider.onApiReady(function (apiObject) {
                return _this3.prepareYTVideo(apiObject);
              });
            }
          }
        }); // We observe the parent, since the video container has a height of 0.

        this.intersectionObserver.observe(this.elements.$video.parent()[0]);
        return;
      } // When Optimized asset loading is set to off, the video type is set to 'Youtube', and 'Privacy Mode' is set
      // to 'On', there might be a conflict with other videos that are loaded WITHOUT privacy mode, such as a
      // video bBackground in a section. In these cases, to avoid the conflict, a timeout is added to postpone the
      // initialization of the Youtube API object.


      if (!elementorFrontend.config.experimentalFeatures['e_optimized_assets_loading']) {
        setTimeout(function () {
          _this3.apiProvider.onApiReady(function (apiObject) {
            return _this3.prepareYTVideo(apiObject);
          });
        }, 0);
      } else {
        this.apiProvider.onApiReady(function (apiObject) {
          return _this3.prepareYTVideo(apiObject);
        });
      }
    }
  }, {
    key: "onElementChange",
    value: function onElementChange(propertyName) {
      if (0 === propertyName.indexOf('lightbox_content_animation')) {
        this.animateVideo();
        return;
      }

      var isLightBoxEnabled = this.getElementSettings('lightbox');

      if ('lightbox' === propertyName && !isLightBoxEnabled) {
        this.hideLightbox();
        return;
      }

      if ('aspect_ratio' === propertyName && isLightBoxEnabled) {
        this.handleAspectRatio();
      }
    }
  }]);
  return Video;
}(elementorModules.frontend.handlers.Base);

exports.default = Video;

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
//# sourceMappingURL=video.a67431c1becbede4d80c.bundle.js.map