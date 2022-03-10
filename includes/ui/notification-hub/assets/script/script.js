window.addEventListener('load', (event) => {

  // handle click on wp-admin bar bell icon that show the WP-Notify sidebar
  const hub = document.getElementById('wp-admin-bar-notify_hub');
  hub.addEventListener("click", function() {
    this.classList.toggle("active")
  });

  // handle click on each primary button of the dashboard notification and shows the WP-Notify sidebar
  document.querySelectorAll('.wp-notification-hub-trigger').forEach((e) => {
    e.addEventListener("click", function() {
      hub.classList.toggle("active")
    });
  })

  // Click handler to add a new notification
  document.getElementById('save-post').addEventListener("click", function(e) {
    e.preventDefault();
    let title = document.getElementById('title').value;
    let content = document.getElementById('content').value;

    var div  = document.createElement('div');
    div.className = 'wp-notification wp-notice-alert is-dismissible';


    const notice_content =
    '<div class="wp-notification-wrap">\n' +
    '  <h2 class="wp-notification-title">' + title + '</h2>\n' +
    '  <p>' + content + '</p>\n' +
    '  <p class="wp-notification-source"><span class="name">#feature-notification</span> • <span class="date">just now</span></p>\n' +
    '</div>\n' +
    '<div class="wp-notification-image">\n' +
    ' <img src="https://source.unsplash.com/random/' + Math.floor(Math.random() * 400) + '×' + Math.floor(Math.random() * 400) + '">\n' +
    '</div>';

    div.innerHTML = notice_content;

    document.querySelector('#wpbody-content .wrap h1').insertAdjacentElement("afterend",div);
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
