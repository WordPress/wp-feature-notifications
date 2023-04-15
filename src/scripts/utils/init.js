import { createRoot } from '@wordpress/element';
import { NoticesArea } from '../components/NoticesArea';
import { NotificationHub } from '../components/NotificationHub';

export function addContext( context ) {
	/** Get the component container */
	const notificationContext = document.getElementById(
		context === 'adminbar'
			? 'wp-admin-bar-wp-notifications-hub'
			: `wp-notifications-${ context }`
	);

	/** If the notification context exists, render it */
	if ( notificationContext ) {
		/** Creates a root for Notification area, whenever is the hub or a defined area like the dashboard */
		const componentRoot = createRoot( notificationContext );

		/**
		 * Renders the component into the specified context
		 *
		 * @member {HTMLElement} notifyDash - the area that will host the notifications
		 */
		componentRoot.render(
			context === 'adminbar' ? (
				<NotificationHub />
			) : (
				<NoticesArea context={ context } />
			)
		);
	}
}
