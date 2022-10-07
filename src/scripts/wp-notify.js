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
import { createElement, render } from '@wordpress/element';

/** Redux */
import { configureStore } from '@reduxjs/toolkit';
import notifyReducer, {
	addNotice,
	removeNotice,
	clearNotices,
} from './store/reducer';
import fetchApi from './store/fetchApi';

/** WP Notify - Components */
import Notices from './components/Notices';

/**
 * Creating a store for the redux state.
 *
 * @typedef {Object} Store
 * @property {Function} dispatch - The only way to update the state is to call store.dispatch() and pass in an action object.
 * @property {Function} getState - Returns the current state tree of your application. It is equal to the last value returned by the store's reducer.
 *
 * @return {Store} A Redux store that lets you read the state, dispatch actions and subscribe to changes.
 */
export const store = configureStore({
	reducer: {
		notifications: notifyReducer,
	},
});

/**
 * @typedef {Object} wp - the WordPress scripts
 * @typedef {Object} wp.notify - the notifications controller
 * @property {Function} fetch  - fetch api for updates
 * @property {Function} add    - add a new notification
 * @property {Function} remove - remove a new notification
 * @property {Function} clear  - clear all notifications
 */
window.wp.notify = [];
wp.notify.fetch = (url) => store.dispatch(fetchApi(url));
wp.notify.add = (props) => store.dispatch(addNotice(props));
wp.notify.remove = (key, location = 'adminbar') =>
	store.dispatch(removeNotice({ key, location }));
wp.notify.clear = (location = 'adminbar') =>
	store.dispatch(clearNotices(location));

export default wp.notify;

/**
 * Renders the DashNotices component
 *
 * @member {HTMLElement} notifyDash - the notification container located in the dashboard
 */
export const notifyDash = document.getElementById(
	'wp-notify-dashboard-notices'
);
render(
	createElement(Notices, { location: 'dashboard', splitBy: false }),
	notifyDash
);

/**
 * Renders the HubNotice component
 *
 * @member {HTMLElement} notifyHub - the notification hub that you can find on the right side of the admin bar
 */
export const notifyHub = document.getElementById('wp-notify-hub');
render(
	createElement(Notices, { location: 'adminbar', splitBy: 'date' }),
	notifyHub
);
