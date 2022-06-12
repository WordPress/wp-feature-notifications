/**
 * WP Notify - main
 */
import { Component, createElement, render } from '@wordpress/element';

// Redux stuff
import { configureStore } from '@reduxjs/toolkit';
import notifyReducer, {
	addNotice,
	removeNotice,
	clearNotices,
	fetchApi,
} from './reducer';
import Notifications from './components/notifications';

/**
 * Toggle notification hub
 */
const wpNotifyHub = document.getElementById( 'wp-admin-bar-wp-notify' );

const disableNotifyDrawer = () => {
	wpNotifyHub.classList.remove( 'active' );
	document.body.removeEventListener( 'click', disableNotifyDrawer );
};

// handle click on wp-admin bar bell icon that show the WP-Notify sidebar
wpNotifyHub.addEventListener( 'click', function ( e ) {
	e.stopPropagation();
	if ( ! wpNotifyHub.classList.contains( 'active' ) ) {
		this.classList.add( 'active' );
		document.body.addEventListener( 'click', disableNotifyDrawer );
	}
} );

/**
 * Enable the main dash notifications if available
 */
const notifyHub = document.getElementById( 'wp-notify-hub' );
const notifyDash = document.getElementById( 'wp-notify-dashboard-notices' );

/**
 * Redux config
 */
export const store = configureStore( {
	reducer: {
		notifications: notifyReducer,
	},
} );

window.wp.notify = [];
wp.notify.fetch = ( url ) => store.dispatch( fetchApi( url ) );
wp.notify.add = ( props ) => store.dispatch( addNotice( props ) );
wp.notify.remove = ( key, location = 'adminbar' ) =>
	store.dispatch( removeNotice( { key, location } ) );
wp.notify.clear = ( location = 'adminbar' ) =>
	store.dispatch( clearNotices( location ) );

/**
 * WP-Notify dashboard notifications
 */
class DashNotices extends Component {
	state = {
		notifications: { ...store.getState().notifications },
	};

	constructor( props ) {
		super( props );
		// watch for state updates
		store.subscribe( () => {
			this.setState( {
				notifications: { ...store.getState().notifications },
			} );
		} );
	}

	render() {
		return <Notifications location={ 'dashboard' } />;
	}
}
render( createElement( DashNotices ), notifyDash );

/**
 * WP-Notify toolbar in the secondary position of the admin bar
 */
class HubNotice extends Component {
	state = {
		notifications: { ...store.getState().notifications },
	};

	constructor( props ) {
		super( props );
		// watch for state updates
		store.subscribe( () => {
			this.setState( {
				notifications: { ...store.getState().notifications },
			} );
		} );
	}

	render() {
		return <Notifications location={ 'adminbar' } splitBy={ 'date' } />;
	}
}
render( createElement( HubNotice ), notifyHub );
