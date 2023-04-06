import { dispatch } from '@wordpress/data';
import { NOTIFY_NAMESPACE } from '../store/constants';
import { __ } from '@wordpress/i18n';

/**
 * It clears the notices in the selected context
 *
 * @param {string} context - The context of the notices. This is used to determine which notices to clear.
 */
export const clearNotifyDrawer = ( context ) => {
	dispatch( NOTIFY_NAMESPACE ).clear( context );
};

export const wpNotifyHub = document.getElementById( 'wp-admin-bar-wp-notify' );

export const toggleNotifyDrawer = ( e ) => {
	if ( wpNotifyHub.isActive && ! wpNotifyHub.contains( e.target ) ) {
		e.stopPropagation();
		enableNotifyDrawer( e );
	} else {
		e.stopPropagation();
		disableNotifyDrawer( e );
	}
};

/**
 * When the user clicks on the notification drawer, the drawer is disabled
 *
 * @callback {disableNotifyDrawer} disableNotifyDrawer
 */
export const disableNotifyDrawer = () => {
	wpNotifyHub.isActive = false;
	wpNotifyHub.classList.remove( 'active' );
	// remove document body the listener to disable the drawer
	drawerExitKey( false );
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
	wpNotifyHub.isActive = true;
	wpNotifyHub.classList.add( 'active' );
	// listen for clicks outside the drawer to disable it
	drawerExitKey( true );
};

function drawerExitKey( enabled ) {
	if ( enabled ) {
		document.body.onkeydown = ( ev ) => {
			if ( 'key' in ev && ( ev.key === 'Escape' || ev.key === 'Esc' ) ) {
				disableNotifyDrawer( ev );
			}
		};
	} else {
		document.body.onclick = null;
		document.body.onkeydown = null;
	}
}

/**
 * Action handler for the notification drawer
 */
if ( wpNotifyHub ) {
	const wpNotifyHubIcon = wpNotifyHub.querySelector( '.ab-item' );

	wpNotifyHub.isActive = false;
	/**
	 * Notification hub
	 * Handle click on wp-admin bar bell icon that show the WP-Notify sidebar
	 *
	 * @member {HTMLElement} wpNotifyHub - the Notification Hub Controller
	 * @event enableNotifyDrawer - When the user clicks or focus on the notification drawer, the drawer is enabled
	 * @event disableNotifyDrawer - on focus out
	 */
	wpNotifyHubIcon.onclick = toggleNotifyDrawer;

	// keyboard event when the user focus on the notification drawer
	wpNotifyHub.addEventListener( 'focus', toggleNotifyDrawer, true );
	wpNotifyHub.addEventListener( 'blur', disableNotifyDrawer, true );
}

export default () => {
	return (
		<>
			<div className="wp-notification-hub-wrapper">
				<h2 className="screen-reader-text">
					{ __( 'Notifications' ) }
				</h2>
				<div id="wp-notify-adminbar"></div>
			</div>
		</>
	);
};
