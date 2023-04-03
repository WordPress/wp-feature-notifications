import { dispatch } from '@wordpress/data';
import { WEEK_IN_SECONDS } from '../components/NoticesArea';
import { NOTIFY_NAMESPACE } from '../store/constants';

/**
 * It clears the notices in the selected context
 *
 * @param {string} context - The context of the notices. This is used to determine which notices to clear.
 */
export const clearNotifyDrawer = ( context ) => {
	dispatch( NOTIFY_NAMESPACE ).clear( context );
};

/**
 * At the moment the function return the notifications if the split by isn't set to "date"
 *
 * @param {Array}  notifications
 * @param {string} by
 *
 * @return {Array} two list of Notifications, one for the new and one for the old
 */
export const getSorted = ( notifications, by = 'date' ) => {
	const Limit = by === 'date' ? Date.now() - WEEK_IN_SECONDS : false;
	if ( Limit ) {
		return notifications.reduce(
			( [ current, past ], item ) => {
				return item.date >= Limit
					? [ [ ...current, item ], past ]
					: [ current, [ ...past, item ] ];
			},
			[ [], [] ]
		);
	}
	return notifications;
};

export const wpNotifyHub = document.getElementById( 'wp-admin-bar-wp-notify' );

/**
 * When the user clicks on the notification drawer, the drawer is disabled
 *
 * @param {Event} e
 * @callback {disableNotifyDrawer} disableNotifyDrawer
 */
export const disableNotifyDrawer = ( e ) => {
	e.stopPropagation();
	wpNotifyHub.classList.remove( 'active' );
	document.onkeydown = null;
	document.body.removeEventListener( 'click', disableNotifyDrawer );
};

/**
 * Enable the notification drawer
 * If the notification drawer is not active, add the active class to the notification drawer and add an event listener to the body to disable the notification drawer
 * The first thing we do is stop the propagation of the event. This is important because we don't want the event to bubble up to the body and trigger the disableNotifyDrawer function
 *
 * @callback {enableNotifyDrawer} enableNotifyDrawer
 * @param {Event} e - The event object.
 */
export const enableNotifyDrawer = ( e ) => {
	e.stopPropagation();
	if ( ! wpNotifyHub.classList.contains( 'active' ) ) {
		wpNotifyHub.classList.add( 'active' );
		document.body.addEventListener( 'click', disableNotifyDrawer );
		document.onkeydown = ( ev ) => {
			if ( 'key' in ev && ( ev.key === 'Escape' || ev.key === 'Esc' ) ) {
				disableNotifyDrawer();
			}
		};
	}
};

/**
 * Action handler for the notification drawer
 */
if ( wpNotifyHub ) {
	/**
	 * Notification hub
	 * Handle click on wp-admin bar bell icon that show the WP-Notify sidebar
	 *
	 * @member {HTMLElement} wpNotifyHub - the Notification Hub Controller
	 * @event enableNotifyDrawer - When the user clicks or focus on the notification drawer, the drawer is enabled
	 * @event disableNotifyDrawer - on focus out
	 */
	wpNotifyHub.addEventListener( 'click', enableNotifyDrawer );
	wpNotifyHub.addEventListener( 'focus', enableNotifyDrawer, true );
	wpNotifyHub.addEventListener( 'blur', disableNotifyDrawer, true );
}
