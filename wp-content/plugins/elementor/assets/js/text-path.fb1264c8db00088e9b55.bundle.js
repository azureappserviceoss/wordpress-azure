/*! elementor - v3.2.3 - 05-05-2021 */
(self["webpackChunkelementor"] = self["webpackChunkelementor"] || []).push([["text-path"],{

/***/ "../assets/dev/js/frontend/utils/utils.js":
/*!************************************************!*\
  !*** ../assets/dev/js/frontend/utils/utils.js ***!
  \************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: __webpack_exports__, __webpack_require__ */
/*! CommonJS bailout: exports is used directly at 5:23-30 */
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {

"use strict";


var _Object$defineProperty = __webpack_require__(/*! @babel/runtime-corejs2/core-js/object/define-property */ "../node_modules/@babel/runtime-corejs2/core-js/object/define-property.js");

_Object$defineProperty(exports, "__esModule", {
  value: true
});

exports.escapeHTML = void 0;

__webpack_require__(/*! core-js/modules/es6.regexp.replace.js */ "../node_modules/core-js/modules/es6.regexp.replace.js");

// Escape HTML special chars to prevent XSS.
var escapeHTML = function escapeHTML(str) {
  var specialChars = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    "'": '&#39;',
    '"': '&quot;'
  };
  return str.replace(/[&<>'"]/g, function (tag) {
    return specialChars[tag] || tag;
  });
};

exports.escapeHTML = escapeHTML;

/***/ }),

/***/ "../modules/shapes/assets/js/frontend/handlers/text-path.js":
/*!******************************************************************!*\
  !*** ../modules/shapes/assets/js/frontend/handlers/text-path.js ***!
  \******************************************************************/
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

__webpack_require__(/*! core-js/modules/es6.regexp.split.js */ "../node_modules/core-js/modules/es6.regexp.split.js");

__webpack_require__(/*! core-js/modules/es6.regexp.replace.js */ "../node_modules/core-js/modules/es6.regexp.replace.js");

__webpack_require__(/*! core-js/modules/es6.string.link.js */ "../node_modules/core-js/modules/es6.string.link.js");

var _parseInt2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/core-js/parse-int */ "../node_modules/@babel/runtime-corejs2/core-js/parse-int.js"));

var _classCallCheck2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/classCallCheck */ "../node_modules/@babel/runtime-corejs2/helpers/classCallCheck.js"));

var _createClass2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/createClass */ "../node_modules/@babel/runtime-corejs2/helpers/createClass.js"));

var _inherits2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/inherits */ "../node_modules/@babel/runtime-corejs2/helpers/inherits.js"));

var _createSuper2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/createSuper */ "../node_modules/@babel/runtime-corejs2/helpers/createSuper.js"));

var _utils = __webpack_require__(/*! elementor-frontend/utils/utils */ "../assets/dev/js/frontend/utils/utils.js");

var TextPathHandler = /*#__PURE__*/function (_elementorModules$fro) {
  (0, _inherits2.default)(TextPathHandler, _elementorModules$fro);

  var _super = (0, _createSuper2.default)(TextPathHandler);

  function TextPathHandler() {
    (0, _classCallCheck2.default)(this, TextPathHandler);
    return _super.apply(this, arguments);
  }

  (0, _createClass2.default)(TextPathHandler, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          pathContainer: '.e-text-path',
          svg: '.e-text-path > svg'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var _this$getSettings = this.getSettings(),
          selectors = _this$getSettings.selectors;

      var element = this.$element[0];
      return {
        widgetWrapper: element,
        pathContainer: element.querySelector(selectors.pathContainer),
        svg: element.querySelector(selectors.svg),
        textPath: element.querySelector(selectors.textPath)
      };
    }
    /**
     * Initialize the object.
     *
     * @returns {void}
     */

  }, {
    key: "onInit",
    value: function onInit() {
      this.elements = this.getDefaultElements(); // Generate unique IDs using the wrapper's `data-id`.

      this.pathId = "e-path-".concat(this.elements.widgetWrapper.dataset.id);
      this.textPathId = "e-text-path-".concat(this.elements.widgetWrapper.dataset.id);

      if (!this.elements.svg) {
        return;
      }

      this.initTextPath();
    }
    /**
     * Set the start offset for the text.
     *
     * @param offset {string|int} The text start offset.
     *
     * @returns {void}
     */

  }, {
    key: "setOffset",
    value: function setOffset(offset) {
      if (!this.elements.textPath) {
        return;
      }

      if (this.isRTL()) {
        offset = 100 - (0, _parseInt2.default)(offset);
      }

      this.elements.textPath.setAttribute('startOffset', offset + '%');
    }
    /**
     * Handle element settings changes.
     *
     * @param setting {Object} The settings object from the editor.
     *
     * @returns {void}
     */

  }, {
    key: "onElementChange",
    value: function onElementChange(setting) {
      var _this$getElementSetti = this.getElementSettings(),
          startPoint = _this$getElementSetti.start_point,
          text = _this$getElementSetti.text;

      switch (setting) {
        case 'start_point':
          this.setOffset(startPoint.size);
          break;

        case 'text':
          this.setText(text);
          break;

        case 'text_path_direction':
          this.setOffset(startPoint.size);
          this.setText(text);
          break;

        default:
          break;
      }
    }
    /**
     * Attach a unique id to the path.
     *
     * @returns {void}
     */

  }, {
    key: "attachIdToPath",
    value: function attachIdToPath() {
      // Prioritize the custom `data` attribute over the `path` element, and fallback to the first `path`.
      var path = this.elements.svg.querySelector('[data-path-anchor]') || this.elements.svg.querySelector('path');
      path.id = this.pathId;
    }
    /**
     * Initialize the text path element.
     *
     * @returns {void}
     */

  }, {
    key: "initTextPath",
    value: function initTextPath() {
      var _this$getElementSetti2 = this.getElementSettings(),
          startPoint = _this$getElementSetti2.start_point,
          text = _this$getElementSetti2.text;

      this.attachIdToPath(); // Generate the `textPath` element with its settings.

      this.elements.svg.innerHTML += "\n\t\t\t<text>\n\t\t\t\t<textPath id=\"".concat(this.textPathId, "\" href=\"#").concat(this.pathId, "\"></textPath>\n\t\t\t</text>\n\t\t"); // Regenerate the elements object to have access to `this.elements.textPath`.

      this.elements.textPath = this.elements.svg.querySelector("#".concat(this.textPathId));
      this.setOffset(startPoint.size);
      this.setText(text);
    }
    /**
     * Set the new text into the path.
     *
     * @param newText {string} The new text to put in the text path.
     *
     * @returns {void}
     */

  }, {
    key: "setText",
    value: function setText(newText) {
      var _this$getElementSetti4;

      var _this$getElementSetti3 = (_this$getElementSetti4 = this.getElementSettings()) === null || _this$getElementSetti4 === void 0 ? void 0 : _this$getElementSetti4.link,
          url = _this$getElementSetti3.url,
          isExternal = _this$getElementSetti3.is_external,
          nofollow = _this$getElementSetti3.nofollow;

      var target = isExternal ? '_blank' : '',
          rel = nofollow ? 'nofollow' : ''; // Add link attributes.

      if (url) {
        newText = "<a href=\"".concat((0, _utils.escapeHTML)(url), "\" rel=\"").concat(rel, "\" target=\"").concat(target, "\">").concat((0, _utils.escapeHTML)(newText), "</a>");
      } // Set the text.


      this.elements.textPath.innerHTML = newText; // Remove the cloned element if exists.

      var existingClone = this.elements.svg.querySelector("#".concat(this.textPathId, "-clone"));

      if (existingClone) {
        existingClone.remove();
      } // Reverse the text if needed.


      if (this.shouldReverseText()) {
        // Keep an invisible selectable copy of original element for better a11y.
        var clone = this.elements.textPath.cloneNode();
        clone.id += '-clone';
        clone.classList.add('elementor-hidden');
        clone.textContent = newText;
        this.elements.textPath.parentNode.appendChild(clone);
        this.reverseToRTL();
      }
    }
    /**
     * Determine if the current layout should be RTL.
     *
     * @returns {boolean}
     */

  }, {
    key: "isRTL",
    value: function isRTL() {
      var _this$getElementSetti5 = this.getElementSettings(),
          direction = _this$getElementSetti5.text_path_direction;

      var isRTL = elementorFrontend.config.is_rtl;

      if (direction) {
        isRTL = 'rtl' === direction;
      }

      return isRTL;
    }
    /**
     * Determine if it should RTL the text (reversing it, etc.).
     *
     * @returns {boolean}
     */

  }, {
    key: "shouldReverseText",
    value: function shouldReverseText() {
      return this.isRTL() && -1 === navigator.userAgent.indexOf('Firefox');
    }
    /**
     * Reverse the text path to support RTL.
     *
     * @returns {void}
     */

  }, {
    key: "reverseToRTL",
    value: function reverseToRTL() {
      // Make sure to use the inner `a` tag if exists.
      var parentElement = this.elements.textPath;
      parentElement = parentElement.querySelector('a') || parentElement; // Catch all RTL chars and reverse their order.

      var pattern = /([\u0591-\u07FF\u200F\u202B\u202E\uFB1D-\uFDFD\uFE70-\uFEFC\s$&+,:;=?@#|'<>.^*()%!-]+)/ig; // Reverse the text.

      parentElement.textContent = parentElement.textContent.replace(pattern, function (word) {
        return word.split('').reverse().join('');
      }); // Add a11y attributes.

      parentElement.setAttribute('aria-hidden', true);
    }
  }]);
  return TextPathHandler;
}(elementorModules.frontend.handlers.Base);

exports.default = TextPathHandler;

/***/ }),

/***/ "../node_modules/core-js/modules/_string-html.js":
/*!*******************************************************!*\
  !*** ../node_modules/core-js/modules/_string-html.js ***!
  \*******************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: module, __webpack_require__ */
/*! CommonJS bailout: module.exports is used directly at 12:0-14 */
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var $export = __webpack_require__(/*! ./_export */ "../node_modules/core-js/modules/_export.js");
var fails = __webpack_require__(/*! ./_fails */ "../node_modules/core-js/modules/_fails.js");
var defined = __webpack_require__(/*! ./_defined */ "../node_modules/core-js/modules/_defined.js");
var quot = /"/g;
// B.2.3.2.1 CreateHTML(string, tag, attribute, value)
var createHTML = function (string, tag, attribute, value) {
  var S = String(defined(string));
  var p1 = '<' + tag;
  if (attribute !== '') p1 += ' ' + attribute + '="' + String(value).replace(quot, '&quot;') + '"';
  return p1 + '>' + S + '</' + tag + '>';
};
module.exports = function (NAME, exec) {
  var O = {};
  O[NAME] = exec(createHTML);
  $export($export.P + $export.F * fails(function () {
    var test = ''[NAME]('"');
    return test !== test.toLowerCase() || test.split('"').length > 3;
  }), 'String', O);
};


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


/***/ }),

/***/ "../node_modules/core-js/modules/es6.string.link.js":
/*!**********************************************************!*\
  !*** ../node_modules/core-js/modules/es6.string.link.js ***!
  \**********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements: __webpack_require__ */
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

"use strict";

// B.2.3.10 String.prototype.link(url)
__webpack_require__(/*! ./_string-html */ "../node_modules/core-js/modules/_string-html.js")('link', function (createHTML) {
  return function link(url) {
    return createHTML(this, 'a', 'href', url);
  };
});


/***/ })

}]);
//# sourceMappingURL=text-path.fb1264c8db00088e9b55.bundle.js.map