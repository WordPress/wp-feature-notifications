window.addEventListener('load', (event) => {
  const hub = document.getElementById('wp-admin-bar-notify_hub');
  hub.addEventListener("click", function() {
    this.classList.toggle("active")
  });
  document.querySelectorAll('.wp-notification-hub-trigger').forEach((e) => {
    e.addEventListener("click", function() {
      hub.classList.toggle("active")
    });
  })


  // Basic replacement for jQuery .parents()
  let parents = function (elem, selector) {
    for ( ; elem && elem !== document; elem = elem.parentNode ) {
      if ( elem.matches( selector ) ) return elem;
    }
    return null;
  };

  // Click handler for dismiss buttons
  document.querySelectorAll('.wp-notification-hub-dismiss').forEach((e) => {
    e.addEventListener("click", function() {
      let el = parents(this, '.wp-notification');
      if ( el ) {
        el.parentNode.removeChild(el);
      }
    });
  })
});
