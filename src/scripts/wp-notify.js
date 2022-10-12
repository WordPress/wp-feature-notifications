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
	listNotices,
	findNotice,
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
 * Wp-Notify Api
 *
 * @example if you need to enable the notification outside dashboard and wpNotify setting page
 *
 * @typedef {Object} wp - wp
 * @typedef {Object} wp.notify - the notifications controller
 * @property {Function} fetch  - fetch for new notices
 * @property {Function} add    - add a new notification
 * @property {Function} remove - remove a notification by key
 * @property {Function} clear  - clear all notifications
 * @property {Function} list   - list all notifications or those of a particular location
 * @property {Function} find   - search for a notification by source
 *
 */
window.wp.notify = [];
/**
 * @param {string} url
 */
wp.notify.fetch = (url) => store.dispatch(fetchApi(url));
/**
 * @param {{location: string, title: *, message: *}} props
 * @param {*}                                        location
 */
wp.notify.add = (props, location = 'dashboard') =>
	store.dispatch(addNotice(props, location));
/**
 * @param {null}   key
 * @param {string} location
 */
wp.notify.remove = (key, location = 'dashboard') =>
	store.dispatch(removeNotice({ key, location }));
/**
 * @param {string|false} location
 */
wp.notify.clear = (location = 'adminbar') =>
	store.dispatch(clearNotices(location));
/**
 * @param {string|false} location
 */
wp.notify.list = (location) => listNotices(location);
/**
 * @param {string} term
 * @param {Object} args
 */
wp.notify.find = (term, args) => findNotice(term, args);

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
