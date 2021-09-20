/*! elementor - v3.2.3 - 05-05-2021 */
(self["webpackChunkelementor"] = self["webpackChunkelementor"] || []).push([["toggle"],{

/***/ "../assets/dev/js/frontend/handlers/toggle.js":
/*!****************************************************!*\
  !*** ../assets/dev/js/frontend/handlers/toggle.js ***!
  \****************************************************/
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

var _objectSpread2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/objectSpread2 */ "../node_modules/@babel/runtime-corejs2/helpers/objectSpread2.js"));

var _classCallCheck2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/classCallCheck */ "../node_modules/@babel/runtime-corejs2/helpers/classCallCheck.js"));

var _createClass2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/createClass */ "../node_modules/@babel/runtime-corejs2/helpers/createClass.js"));

var _get2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/get */ "../node_modules/@babel/runtime-corejs2/helpers/get.js"));

var _getPrototypeOf2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/getPrototypeOf */ "../node_modules/@babel/runtime-corejs2/helpers/getPrototypeOf.js"));

var _inherits2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/inherits */ "../node_modules/@babel/runtime-corejs2/helpers/inherits.js"));

var _createSuper2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime-corejs2/helpers/createSuper */ "../node_modules/@babel/runtime-corejs2/helpers/createSuper.js"));

var _baseTabs = _interopRequireDefault(__webpack_require__(/*! ./base-tabs */ "../assets/dev/js/frontend/handlers/base-tabs.js"));

var Toggle = /*#__PURE__*/function (_TabsModule) {
  (0, _inherits2.default)(Toggle, _TabsModule);

  var _super = (0, _createSuper2.default)(Toggle);

  function Toggle() {
    (0, _classCallCheck2.default)(this, Toggle);
    return _super.apply(this, arguments);
  }

  (0, _createClass2.default)(Toggle, [{
    key: "getDefaultSettings",
    value: function getDefaultSettings() {
      var defaultSettings = (0, _get2.default)((0, _getPrototypeOf2.default)(Toggle.prototype), "getDefaultSettings", this).call(this);
      return (0, _objectSpread2.default)((0, _objectSpread2.default)({}, defaultSettings), {}, {
        showTabFn: 'slideDown',
        hideTabFn: 'slideUp',
        hidePrevious: false,
        autoExpand: 'editor'
      });
    }
  }]);
  return Toggle;
}(_baseTabs.default);

exports.default = Toggle;

/***/ })

}]);
//# sourceMappingURL=toggle.f62080504158ea96ac4c.bundle.js.map