/******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/scripts/demo.js":
/*!*****************************!*\
  !*** ./src/scripts/demo.js ***!
  \*****************************/
/***/ (function() {

window.addEventListener("load", function () {
  // handle click on each primary button of the dashboard notification and shows the WP-Notify sidebar
  // document.querySelectorAll(".wp-notification-hub-trigger").forEach((e) => {
  //   e.addEventListener("click", function () {
  //     hub.classList.toggle("active");
  //   });
  // });
  // Click handler to add a new notification
  document.getElementById("wp-notification-metabox-form").addEventListener("submit", function (e) {
    e.preventDefault();
    var title = document.getElementById("wp-notification-metabox-form-title").value;
    var message = document.getElementById("wp-notification-metabox-form-message").value;
    addNotify({
      title: title,
      message: message
    }); //   const div = document.createElement("div");
    //   div.className = "wp-notification wp-notice-alert is-dismissible";
    //   div.innerHTML = `<div class="wp-notification-wrap">
    //     <h2 class="wp-notification-title">${title}</h2>
    //     <p>${content}</p>
    //     <p class="wp-notification-source"><span class="name">#feature-notification</span> • <span class="date">just now</span></p>
    //   </div>
    //   <div class="wp-notification-image">
    //    <img src="https://source.unsplash.com/random/${Math.floor(
    //      Math.random() * 400
    //    )}×${Math.floor(Math.random() * 400)}">
    //   </div>`;
    //   document
    //     .querySelector("#wpbody-content .wrap h1")
    //     .insertAdjacentElement("afterend", div);
    // });
    // // Basic replacement for jQuery .parents()
    // const parents = function (elem, selector) {
    //   for (; elem && elem !== document; elem = elem.parentNode) {
    //     if (elem.matches(selector)) return elem;
    //   }
    //   return null;
    // };
    // // Click handler for dismiss buttons
    // document.querySelectorAll(".wp-notification-hub-dismiss").forEach((e) => {
    //   e.addEventListener("click", function () {
    //     const el = parents(this, ".wp-notification");
    //     if (el) {
    //       el.parentNode.removeChild(el);
    //     }
    //   });
  });
});

/***/ }),

/***/ "./src/scripts/wp-notify.js":
/*!**********************************!*\
  !*** ./src/scripts/wp-notify.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/esm/createClass.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/esm/inherits.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_8__);









var _addNotify;



function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = (0,_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_4__["default"])(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return (0,_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_3__["default"])(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__["default"])(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

var __ = wp.i18n.__; // handle click on wp-admin bar bell icon that show the WP-Notify sidebar

document.getElementById("wp-admin-bar-wp-notify").addEventListener("click", function () {
  this.classList.toggle("active");
}); // creates notify container

var createPortal = wp.element.createPortal; // the dashboard notifications context

var NotifyContext = wp.element.createContext();

var NotificationsWrap = function NotificationsWrap(_ref) {
  var children = _ref.children,
      elementId = _ref.elementId;

  var _wp$element$useState = wp.element.useState(),
      _wp$element$useState2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_7__["default"])(_wp$element$useState, 2),
      NotifyElement = _wp$element$useState2[0],
      setNotifyElement = _wp$element$useState2[1];

  wp.element.useLayoutEffect(function () {
    var element = document.querySelector("#".concat(elementId));
    setNotifyElement(element);
  }, [elementId]);
  if (!NotifyElement) return null;
  return createPortal(children, NotifyElement);
}; // Handle changes and returns the array of displayed notifications


var NotifyReducer = function NotifyReducer(state, data) {
  switch (data.action) {
    case "ADD":
      {
        return [_objectSpread({}, data.payload)].concat((0,_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_6__["default"])(state));
      }

    case "REMOVE":
      {
        return state.filter(function (notification, id) {
          return id !== data.payload;
        });
      }

    case "CLEAR_ALL":
      {
        return [];
      }

    default:
      {
        return state;
      }
  }
}; // Notification controller


var DashNotifyController = function DashNotifyController(_ref2) {
  var children = _ref2.children;

  var _wp$element$useReduce = wp.element.useReducer(NotifyReducer, []),
      _wp$element$useReduce2 = (0,_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_7__["default"])(_wp$element$useReduce, 2),
      state = _wp$element$useReduce2[0],
      dispatch = _wp$element$useReduce2[1];

  var addNotify = function addNotify(props) {
    return dispatch({
      action: "ADD",
      payload: props
    });
  };

  var removeNotify = function removeNotify(id) {
    return dispatch({
      action: "REMOVE",
      payload: id
    });
  };

  var clearNotifies = function clearNotifies() {
    return dispatch({
      action: "CLEAR_ALL"
    });
  };

  window.addNotify = addNotify;
  window.removeNotify = removeNotify;
  window.clearNotifies = clearNotifies;
  console.log(state);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)(NotifyContext.Provider, {
    value: {
      notification: state,
      addNotify: addNotify,
      removeNotify: removeNotify,
      clearNotifies: clearNotifies
    }
  }, children);
};

var Notifications = function Notifications() {
  var _wp$element$useContex = wp.element.useContext(NotifyContext),
      notification = _wp$element$useContex.notification,
      removeNotify = _wp$element$useContex.removeNotify;

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)(NotificationsWrap, {
    elementId: "wp-notify-dashboard-notices"
  }, notification.map(function (notify, id) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)(DashNotice, {
      key: id,
      id: id,
      image: notify.image,
      title: notify.title,
      message: notify.message,
      acceptMessage: notify.accept,
      acceptLink: notify.acceptLink,
      dismissLabel: notify.dismiss,
      source: notify.source,
      date: notify.date,
      dismissible: notify.dismissible,
      onDismiss: function onDismiss() {
        return removeNotify(id);
      }
    });
  }));
}; // DashNotice class method.


var DashNotice = /*#__PURE__*/function (_wp$element$Component) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(DashNotice, _wp$element$Component);

  var _super = _createSuper(DashNotice);

  function DashNotice() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, DashNotice);

    return _super.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(DashNotice, [{
    key: "render",
    value: function render() {
      var classes = "wp-notification wp-notice-" + this.props.id;
      classes += this.props.dismissible ? " is-dismissible" : "";
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: classes
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: "wp-notification-wrap"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("h3", {
        className: "wp-notification-title"
      }, this.props.title), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("p", {
        dangerouslySetInnerHTML: {
          __html: this.props.message
        }
      }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: "wp-notification-actions-wrap"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("a", {
        className: "button button-primary wp-notification-hub-trigger",
        href: this.props.acceptLink
      }, this.props.acceptMessage), this.props.dismissible && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("button", {
        className: "button button-link wp-notification-hub-dismiss",
        onClick: this.props.onDismiss
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("span", {
        className: "dashicons dashicons-no-alt"
      }), this.props.dismissLabel)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("p", {
        className: "wp-notification-source"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("span", {
        className: "name"
      }, this.props.source), " ", "\u2022 ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("span", {
        className: "date"
      }, this.props.date))), this.props.image && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: "wp-notification-image"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("img", {
        src: this.props.image,
        alt: this.props.title + " image"
      })));
    }
  }]);

  return DashNotice;
}(wp.element.Component);

DashNotice.defaultProps = {
  id: false,
  image: false,
  title: "",
  message: "",
  acceptMessage: __("Accept"),
  acceptLink: "#",
  dismissLabel: __("dismiss"),
  source: "WordPress",
  date: __("Just now"),
  dismissible: true
};
/**
 * THIS IS FOR TESTING PURPOSE - TO BE REMOVED
 */

var Demo = function Demo() {
  var _wp$element$useContex2 = wp.element.useContext(NotifyContext),
      addNotify = _wp$element$useContex2.addNotify,
      clearNotifies = _wp$element$useContex2.clearNotifies;

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
    className: "demo"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("button", {
    className: "button button-primary",
    onClick: function onClick() {
      return addNotify({
        title: "Hello world!"
      });
    }
  }, __("Add")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("button", {
    className: "button button-primary",
    onClick: clearNotifies
  }, __("Clear all")));
};
/**
 * Render the main dash notification container
 */


wp.element.render((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)(DashNotifyController, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)(Demo, null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)(Notifications, null)), document.getElementById("wp-notify-dashboard-notices"));
/**
 * THIS IS FOR TESTING PURPOSE - TO BE REMOVED
 */

addNotify((_addNotify = {
  title: 'Try this new Notification feature',
  image: "https://gifimage.net/wp-content/uploads/2018/10/animation-notification-gif-2.gif"
}, (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__["default"])(_addNotify, "title", "Try this new Notification feature"), (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__["default"])(_addNotify, "source", "#WP-Notify"), (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__["default"])(_addNotify, "message", "We have just added a <b>wonderful feature!</b> You might want to give it a try so click on the bell icon on the right side of the adminbar 😉."), (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__["default"])(_addNotify, "acceptMessage", "Try this new feature"), (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__["default"])(_addNotify, "acceptLink", "https://github.com/WordPress/wp-notify"), (0,_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_5__["default"])(_addNotify, "dismissible", false), _addNotify)); // the WP-Notify toolbar in the secondary position of the admin bar

var HubNotice = /*#__PURE__*/function (_wp$element$Component2) {
  (0,_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_2__["default"])(HubNotice, _wp$element$Component2);

  var _super2 = _createSuper(HubNotice);

  function HubNotice() {
    (0,_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_0__["default"])(this, HubNotice);

    return _super2.apply(this, arguments);
  }

  (0,_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_1__["default"])(HubNotice, [{
    key: "render",
    value: function render() {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("section", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("header", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("h3", null, "2 unread notifications"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("button", {
        className: "wp-notification-action wp-notification-action-markread button-link"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("span", {
        className: "ab-icon dashicons-saved"
      }), " Mark all as read")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: "wp-notification notify",
        role: "status"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: "wp-notification-image"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("span", {
        className: "ab-icon dashicons-paperclip"
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: "wp-notification-wrap"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("h4", {
        className: "wp-notification-title"
      }, "Default"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("p", {
        className: "wp-notification-source"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("span", {
        className: "name"
      }, "WordPress"), " ", "\u2022" + " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("span", {
        className: "date"
      }, "2d ago")))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: "wp-notification plugin",
        role: "status"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: "wp-notification-image default"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("img", {
        src: "https://ps.w.org/contact-form-7/assets/icon-256x256.png",
        alt: "contact form 7 icon"
      })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("div", {
        className: "wp-notification-wrap"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("p", {
        className: "wp-notification-message"
      }, "There is a new version of Contact Form 7 available."), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("a", {
        href: "./wp-admin/plugins.php",
        className: "wp-notification-action"
      }, "Update now"), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("p", {
        className: "wp-notification-source"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("span", {
        className: "name"
      }, "WordPress"), " ", "\u2022" + " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_8__.createElement)("span", {
        className: "date"
      }, "1d ago")))));
    }
  }]);

  return HubNotice;
}(wp.element.Component);

wp.element.render(wp.element.createElement(HubNotice), document.getElementById("wp-notify-hub"));

/***/ }),

/***/ "./src/styles/wp-notify.css":
/*!**********************************!*\
  !*** ./src/styles/wp-notify.css ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/styles/wp-notify.scss":
/*!***********************************!*\
  !*** ./src/styles/wp-notify.scss ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js ***!
  \*********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayLikeToArray; }
/* harmony export */ });
function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayWithHoles; }
/* harmony export */ });
function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _arrayWithoutHoles; }
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(arr);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js ***!
  \**************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _assertThisInitialized; }
/* harmony export */ });
function _assertThisInitialized(self) {
  if (self === void 0) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return self;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/classCallCheck.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/classCallCheck.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _classCallCheck; }
/* harmony export */ });
function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/createClass.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/createClass.js ***!
  \****************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _createClass; }
/* harmony export */ });
function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  Object.defineProperty(Constructor, "prototype", {
    writable: false
  });
  return Constructor;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/defineProperty.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _defineProperty; }
/* harmony export */ });
function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/getPrototypeOf.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _getPrototypeOf; }
/* harmony export */ });
function _getPrototypeOf(o) {
  _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
    return o.__proto__ || Object.getPrototypeOf(o);
  };
  return _getPrototypeOf(o);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/inherits.js":
/*!*************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/inherits.js ***!
  \*************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _inherits; }
/* harmony export */ });
/* harmony import */ var _setPrototypeOf_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./setPrototypeOf.js */ "./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js");

function _inherits(subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function");
  }

  subClass.prototype = Object.create(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      writable: true,
      configurable: true
    }
  });
  Object.defineProperty(subClass, "prototype", {
    writable: false
  });
  if (superClass) (0,_setPrototypeOf_js__WEBPACK_IMPORTED_MODULE_0__["default"])(subClass, superClass);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArray.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _iterableToArray; }
/* harmony export */ });
function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js":
/*!*************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js ***!
  \*************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _iterableToArrayLimit; }
/* harmony export */ });
function _iterableToArrayLimit(arr, i) {
  var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"];

  if (_i == null) return;
  var _arr = [];
  var _n = true;
  var _d = false;

  var _s, _e;

  try {
    for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value);

      if (i && _arr.length === i) break;
    }
  } catch (err) {
    _d = true;
    _e = err;
  } finally {
    try {
      if (!_n && _i["return"] != null) _i["return"]();
    } finally {
      if (_d) throw _e;
    }
  }

  return _arr;
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js":
/*!********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js ***!
  \********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _nonIterableRest; }
/* harmony export */ });
function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _nonIterableSpread; }
/* harmony export */ });
function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js":
/*!******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/possibleConstructorReturn.js ***!
  \******************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _possibleConstructorReturn; }
/* harmony export */ });
/* harmony import */ var _typeof_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./typeof.js */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _assertThisInitialized_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./assertThisInitialized.js */ "./node_modules/@babel/runtime/helpers/esm/assertThisInitialized.js");


function _possibleConstructorReturn(self, call) {
  if (call && ((0,_typeof_js__WEBPACK_IMPORTED_MODULE_0__["default"])(call) === "object" || typeof call === "function")) {
    return call;
  } else if (call !== void 0) {
    throw new TypeError("Derived constructors may only return object or undefined");
  }

  return (0,_assertThisInitialized_js__WEBPACK_IMPORTED_MODULE_1__["default"])(self);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/setPrototypeOf.js ***!
  \*******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _setPrototypeOf; }
/* harmony export */ });
function _setPrototypeOf(o, p) {
  _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };

  return _setPrototypeOf(o, p);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/slicedToArray.js ***!
  \******************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _slicedToArray; }
/* harmony export */ });
/* harmony import */ var _arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithHoles.js */ "./node_modules/@babel/runtime/helpers/esm/arrayWithHoles.js");
/* harmony import */ var _iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArrayLimit.js */ "./node_modules/@babel/runtime/helpers/esm/iterableToArrayLimit.js");
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");
/* harmony import */ var _nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableRest.js */ "./node_modules/@babel/runtime/helpers/esm/nonIterableRest.js");




function _slicedToArray(arr, i) {
  return (0,_arrayWithHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(arr) || (0,_iterableToArrayLimit_js__WEBPACK_IMPORTED_MODULE_1__["default"])(arr, i) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(arr, i) || (0,_nonIterableRest_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js ***!
  \**********************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _toConsumableArray; }
/* harmony export */ });
/* harmony import */ var _arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayWithoutHoles.js */ "./node_modules/@babel/runtime/helpers/esm/arrayWithoutHoles.js");
/* harmony import */ var _iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./iterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/iterableToArray.js");
/* harmony import */ var _unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js");
/* harmony import */ var _nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./nonIterableSpread.js */ "./node_modules/@babel/runtime/helpers/esm/nonIterableSpread.js");




function _toConsumableArray(arr) {
  return (0,_arrayWithoutHoles_js__WEBPACK_IMPORTED_MODULE_0__["default"])(arr) || (0,_iterableToArray_js__WEBPACK_IMPORTED_MODULE_1__["default"])(arr) || (0,_unsupportedIterableToArray_js__WEBPACK_IMPORTED_MODULE_2__["default"])(arr) || (0,_nonIterableSpread_js__WEBPACK_IMPORTED_MODULE_3__["default"])();
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/typeof.js":
/*!***********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/typeof.js ***!
  \***********************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _typeof; }
/* harmony export */ });
function _typeof(obj) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
  }, _typeof(obj);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js":
/*!*******************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/unsupportedIterableToArray.js ***!
  \*******************************************************************************/
/***/ (function(__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ _unsupportedIterableToArray; }
/* harmony export */ });
/* harmony import */ var _arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/esm/arrayLikeToArray.js");

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return (0,_arrayLikeToArray_js__WEBPACK_IMPORTED_MODULE_0__["default"])(o, minLen);
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
!function() {
"use strict";
/*!**************************!*\
  !*** ./src/wp-notify.js ***!
  \**************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scripts_wp_notify__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./scripts/wp-notify */ "./src/scripts/wp-notify.js");
/* harmony import */ var _scripts_demo__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./scripts/demo */ "./src/scripts/demo.js");
/* harmony import */ var _scripts_demo__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_scripts_demo__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _styles_wp_notify_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./styles/wp-notify.scss */ "./src/styles/wp-notify.scss");
/* harmony import */ var _styles_wp_notify_css__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./styles/wp-notify.css */ "./src/styles/wp-notify.css");




}();
/******/ })()
;
//# sourceMappingURL=wp-notify.js.map