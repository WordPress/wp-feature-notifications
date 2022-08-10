/**
 * WP Notify
 *
 * @file index.js
 * @name wpNotify
 * @description A feature plugin for WordPress, which aims to create a new (better) way to manage and deliver notifications to the relevant audience.
 * @author the WordPress Community
 *
 */

/** WordPress Dependencies */
import { Component, createElement, render } from '@wordpress/element';

/** Redux */
import { configureStore } from '@reduxjs/toolkit';
import notifyReducer, {
	addNotice,
	removeNotice,
	clearNotices,
	fetchApi,
} from './reducer';

/** WP Notify - Components */
import Notifications from './components/notifications';

/**
 * Notification hub
 * Enables the main dash notifications if available
 * Enables the admin area if available
 *
 * @typedef {HTMLElement} wpNotifyHub - the Notification Hub Controller
 * @member {HTMLElement} notifyHub - the notification hub that you can find on the right side of the admin bar
 * @member {HTMLElement} notifyDash - the notification container located in the dashboard
 */
const wpNotifyHub = document.getElementById( 'wp-admin-bar-wp-notify' );
const notifyHub = document.getElementById( 'wp-notify-hub' );
const notifyDash = document.getElementById( 'wp-notify-dashboard-notices' );

/**
 * When the user clicks on the notification drawer, the drawer is disabled
 *
 * @callback {disableNotifyDrawer} disableNotifyDrawer
 */
const disableNotifyDrawer = () => {
	wpNotifyHub.classList.remove( 'active' );
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
const enableNotifyDrawer = ( e ) => {
	e.stopPropagation();
	if ( ! wpNotifyHub.classList.contains( 'active' ) ) {
		wpNotifyHub.classList.add( 'active' );
		document.body.addEventListener( 'click', disableNotifyDrawer );
	}
};

/**
 * Handle click on wp-admin bar bell icon that show the WP-Notify sidebar
 *
 * @event enableNotifyDrawer - by default on click
 */
wpNotifyHub.addEventListener( 'click', enableNotifyDrawer );

/**
 * Creating a store for the redux state.
 *
 * @typedef {Object} Store
 * @property {Function} dispatch - The only way to update the state is to call store.dispatch() and pass in an action object.
 * @property {Function} getState - Returns the current state tree of your application. It is equal to the last value returned by the store's reducer.
 *
 * @return {Store} A Redux store that lets you read the state, dispatch actions and subscribe to changes.
 */
export const store = configureStore( {
	reducer: {
		notifications: notifyReducer,
	},
} );

/**
 * @typedef {Object} wp - the WordPress scripts
 * @typedef {Object} wp.notify - the notifications controller
 * @property {Function} fetch  - fetch api for updates
 * @property {Function} add    - add a new notification
 * @property {Function} remove - remove a new notification
 * @property {Function} clear  - clear all notifications
 */
window.wp.notify = [];
wp.notify.fetch = ( url ) => store.dispatch( fetchApi( url ) );
wp.notify.add = ( props ) => store.dispatch( addNotice( props ) );
wp.notify.remove = ( key, location = 'adminbar' ) =>
	store.dispatch( removeNotice( { key, location } ) );
wp.notify.clear = ( location = 'adminbar' ) =>
	store.dispatch( clearNotices( location ) );

/**
 * WP-Notify dashboard notifications
 * It watches for state updates and renders the Notifications component when it detects a change
 *
 * @module DashNotices
 *
 * @typedef {JSX.Element} DashNotices
 * @property {Object} notifications - the notification collection
 * @return {JSX.Element} Notifications
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
 * It watches for state updates and renders a <Notifications /> component with the updated state
 *
 * @module HubNotice
 * @return {JSX.Element} Notifications
 * @param {store} store
 * @type {Function} store.getState
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
