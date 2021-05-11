/*! elementor - v3.2.3 - 05-05-2021 */
(self["webpackChunkelementor"] = self["webpackChunkelementor"] || []).push([["alert"],{

/***/ "../assets/dev/js/frontend/handlers/alert.js":
/*!***************************************************!*\
  !*** ../assets/dev/js/frontend/handlers/alert.js ***!
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

__webpack_require__(/*! core-js/modules/es6.array.find.js */ "../node_modules/core-js/modules/es6.array.find.js");

var _classCallCheck2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/classCallCheck */ "../node_modules/@babel/runtime-corejs2/helpers/classCallCheck.js"));

var _createClass2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/createClass */ "../node_modules/@babel/runtime-corejs2/helpers/createClass.js"));

var _inherits2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/inherits */ "../node_modules/@babel/runtime-corejs2/helpers/inherits.js"));

var _createSuper2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/createSuper */ "../node_modules/@babel/runtime-corejs2/helpers/createSuper.js"));

var Alert = /*#__PURE__*/function (_elementorModules$fro) {
  (0, _inherits2.default)(Alert, _elementorModules$fro);

  var _super = (0, _createSuper2.default)(Alert);

  function Alert() {
    (0, _classCallCheck2.default)(this, Alert);
    return _super.apply(this, arguments);
  }

  (0, _createClass2.default)(Alert, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      return {
        selectors: {
          dismissButton: '.elementor-alert-dismiss'
        }
      };
    }
  }, {
    key: "getDefaultElements",
    value: function getDefaultElements() {
      var selectors = this.getSettings('selectors');
      return {
        $dismissButton: this.$element.find(selectors.dismissButton)
      };
    }
  }, {
    key: "bindEvents",
    value: function bindEvents() {
      this.elements.$dismissButton.on('click', this.onDismissButtonClick.bind(this));
    }
  }, {
    key: "onDismissButtonClick",
    value: function onDismissButtonClick() {
      this.$element.fadeOut();
    }
  }]);
  return Alert;
}(elementorModules.frontend.handlers.Base);

exports.default = Alert;

/***/ })

}]);
//# sourceMappingURL=alert.1ddc787a09e65acb8bd5.bundle.js.map