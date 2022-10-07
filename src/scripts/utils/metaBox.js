/**
 * Adding an event listener to the form with the id of `wp-notification-metabox-form` that adds a new notification using the form data
 */
window.addEventListener('load', () => {
	const wpNotificationMetabox = document.getElementById(
		'wp-notification-metabox-form'
	);

	if (wpNotificationMetabox) {
		document
			.getElementById('wp-notification-metabox-form')
			.addEventListener('submit', function (e) {
				e.preventDefault();
				const title = document.getElementById(
					'wp-notification-metabox-form-title'
				).value;
				const message = document.getElementById(
					'wp-notification-metabox-form-message'
				).value;

				wp.notify.add({
					title,
					message,
					location: 'dashboard',
				});
			});
	}
});
