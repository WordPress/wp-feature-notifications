/**
 * On load listen for metabox events like submit, clear etc
 */
import { dispatch } from '@wordpress/data';
import { NOTIFY_NAMESPACE } from '../store/constants';

window.addEventListener( 'load', () => {
	/**
	 * Adding an event listener to the form with the id of `wp-notification-metabox-form` that adds a new notification using the form data
	 */
	const wpNotificationMetabox = document.getElementById(
		'wp-notification-metabox-form'
	);

	if ( wpNotificationMetabox ) {
		wpNotificationMetabox.addEventListener( 'submit', ( e ) => {
			e.preventDefault();
			const title = document.getElementById(
				'wp-notification-metabox-form-title'
			).value;
			const message = document.getElementById(
				'wp-notification-metabox-form-message'
			).value;

			dispatch( NOTIFY_NAMESPACE ).addNotice( {
				title,
				message,
				context: 'dashboard',
			} );
		} );
	}

	/**
	 *  Adding an event listener to the form with the id of `wp-notification-metabox-form` that handles "clear all notifications"
	 */
	const wpNotificationClearAll = document.getElementById(
		'clear-all-wp-notify'
	);
	if ( wpNotificationClearAll )
		wpNotificationClearAll.addEventListener( 'click', () => {
			wp.notify.clear( 'dashboard' );
		} );
} );
