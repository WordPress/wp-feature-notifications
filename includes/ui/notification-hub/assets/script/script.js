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
});
