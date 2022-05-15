window.addEventListener("load", () => {
  // handle click on each primary button of the dashboard notification and shows the WP-Notify sidebar
  // document.querySelectorAll(".wp-notification-hub-trigger").forEach((e) => {
  //   e.addEventListener("click", function () {
  //     hub.classList.toggle("active");
  //   });
  // });

  // Click handler to add a new notification
  document.getElementById("save-post").addEventListener("click", function (e) {
    e.preventDefault();
    const title = document.getElementById("title").value;
    const content = document.getElementById("content").value;

    const div = document.createElement("div");
    div.className = "wp-notification wp-notice-alert is-dismissible";
    div.innerHTML = `<div class="wp-notification-wrap">
      <h2 class="wp-notification-title">${title}</h2>
      <p>${content}</p>
      <p class="wp-notification-source"><span class="name">#feature-notification</span> • <span class="date">just now</span></p>
    </div>
    <div class="wp-notification-image">
     <img src="https://source.unsplash.com/random/${Math.floor(
       Math.random() * 400
     )}×${Math.floor(Math.random() * 400)}">
    </div>`;

    document
      .querySelector("#wpbody-content .wrap h1")
      .insertAdjacentElement("afterend", div);
  });

  // Basic replacement for jQuery .parents()
  const parents = function (elem, selector) {
    for (; elem && elem !== document; elem = elem.parentNode) {
      if (elem.matches(selector)) return elem;
    }
    return null;
  };
  // Click handler for dismiss buttons
  document.querySelectorAll(".wp-notification-hub-dismiss").forEach((e) => {
    e.addEventListener("click", function () {
      const el = parents(this, ".wp-notification");
      if (el) {
        el.parentNode.removeChild(el);
      }
    });
  });
});
