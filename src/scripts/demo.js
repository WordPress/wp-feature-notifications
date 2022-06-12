window.addEventListener("load", () => {
  const wpNotificationMetabox = document.getElementById(
    "wp-notification-metabox-form"
  );

  if (!wpNotificationMetabox) return;
  // Click handler to add a new notification
  document
    .getElementById("wp-notification-metabox-form")
    .addEventListener("submit", function (e) {
      e.preventDefault();
      const title = document.getElementById(
        "wp-notification-metabox-form-title"
      ).value;
      const message = document.getElementById(
        "wp-notification-metabox-form-message"
      ).value;

      addNotify({
        title,
        message,
      });
    });

  // flush all notices
  document
    .getElementById("clear-all-wp-notify")
    .addEventListener("click", () => clearNotifies());
});
