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
  });

  var parents = function parents(elem, selector) {
    for (; elem && elem !== document; elem = elem.parentNode) {
      if (elem.matches(selector)) return elem;
    }

    return null;
  };

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
/******/ 	__webpack_require__.h = function() { return "cb585930c610839571c7"; }
/******/ }();
/******/ 
/******/ }
);
//# sourceMappingURL=script.3152430163bd085e40e9.hot-update.js.map