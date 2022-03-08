self["webpackHotUpdatewp_notify"]("script",{

/***/ "./includes/ui/notification-hub/assets/script/script.js":
/*!**************************************************************!*\
  !*** ./includes/ui/notification-hub/assets/script/script.js ***!
  \**************************************************************/
/***/ (function() {

window.addEventListener('load', function (event) {
  var hub = document.getElementById('wp-admin-bar-notify_hub');
  hub.addEventListener("click", function () {
    this.classList.toggle("active");
  });
  document.querySelectorAll('.wp-notification-hub-trigger').forEach(function (e) {
    e.addEventListener("click", function () {
      hub.classList.toggle("active");
    });
  }); // Basic replacement for jQuery .parents()

  var parents = function parents(elem, selector) {
    for (; elem && elem !== document; elem = elem.parentNode) {
      if (elem.matches(selector)) return elem;
    }

    return null;
  }; // Click handler for dismiss buttons


  document.querySelectorAll('.wp-notification-hub-dismiss').forEach(function (e) {
    e.addEventListener("click", function () {
      var el = parents(this, '.wp-notification');

      if (el) {
        el.parentNode.removeChild(el);
      }
    });
  });
});

/***/ })

},
/******/ function(__webpack_require__) { // webpackRuntimeModules
/******/ /* webpack/runtime/getFullHash */
/******/ !function() {
/******/ 	__webpack_require__.h = function() { return "61f43ef58a05861e59b5"; }
/******/ }();
/******/ 
/******/ }
);
//# sourceMappingURL=script.0fd05ef402f4a6c9f168.hot-update.js.map