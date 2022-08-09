/* global pagenow, wp_notify_data */
import { store } from './wp-notify';
import { clearNotices } from './reducer';

window.addEventListener( 'load', () => {
	/** Getting the metabox with the id of `wp-notification-metabox-form` */
	const wpNotificationMetabox = document.getElementById(
		'wp-notification-metabox-form'
	);

	if ( ! wpNotificationMetabox ) {
		return;
	}
	// Click handler to add a new notification
	document
		.getElementById( 'wp-notification-metabox-form' )
		.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			const title = document.getElementById(
				'wp-notification-metabox-form-title'
			).value;
			const message = document.getElementById(
				'wp-notification-metabox-form-message'
			).value;

			wp.notify.add( {
				title,
				message,
				location: 'dashboard',
			} );
		} );

	// flush all notices
	document
		.getElementById( 'clear-all-wp-notify' )
		.addEventListener( 'click', () =>
			store.dispatch( clearNotices( 'dashboard' ) )
		);
} );

/**
 * @module wpNotify
 *
 * @description add some demo notifications to the dashboard
 *
 * @example if you need to enbale the notification outside dashboard and wpNotify setting page
 *
 * @type {wp.notify} notification module
 * @property {Function} {fetch} - fetch for new notifications
 */
if (
	pagenow &&
	( pagenow === 'settings_page_wp-notify' || pagenow === 'dashboard' )
) {
	// eslint-disable-next-line camelcase
	wp.notify.fetch( wp_notify_data.pluginUrl + '/src/scripts/fake_api.json' );
}
