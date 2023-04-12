import { createRoot } from '@wordpress/element';
import { NoticesArea } from '../components/NoticesArea';
import { NotificationHub as Hub } from '../components/NotificationHub';

export function addContext( context ) {
	/** Get the component container */
	const notifyContainer = document.getElementById(
		`wp-notification-${ context }`
	);

	/** Creates a root for NoticesArea component. */
	const notifyRoot = createRoot( notifyContainer );

	/**
	 * Renders the component into the specified context
	 *
	 * @member {HTMLElement} notifyDash - the area that will host the notifications
	 */
	notifyRoot.render( <NoticesArea context={ context } /> );
}

export function addHub() {
	/** Get the Notification Hub area (admin bar) */
	const adminBarWpNotify = document.getElementById(
		'wp-admin-bar-wp-notification-hub'
	);

	/** Creates a root for Notification Hub area */
	const hubRoot = createRoot( adminBarWpNotify );

	/** Init the Notification Hub component */
	hubRoot.render( <Hub /> );
}
