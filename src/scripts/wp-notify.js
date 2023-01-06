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
import { dispatch, select } from '@wordpress/data';

/** WP Notify - Components */
import { NoticesArea } from './components/NoticesArea';

/** The store default data */
import { NOTIFY_NAMESPACE, contexts } from './store/constants';

/**
 * Wp-Notify Api
 *
 * @example if you need to enable the notification outside dashboard and wpNotify setting page
 *
 * @typedef {Object} notify - the notifications controller
 *
 * @property {Function} fetchUpdates - fetch for new notices
 * @property {Function} add          - add a new notification
 * @property {Function} remove       - remove a notification by key
 * @property {Function} clear        - clear all notifications
 * @property {Function} list         - list all notifications or those of a particular context
 * @property {Function} find         - search for a notification by source
 *
 */
const notify = {
	/**
	 * fetches api and update notification store
	 */
	fetchUpdates: () => select(NOTIFY_NAMESPACE).fetchUpdates(),

	/**
	 * @param {string|false} context
	 */
	get: (context = '') => select(NOTIFY_NAMESPACE).getNotices(context),

	/**
	 * @param {string} term
	 * @param {Object} args
	 */
	find: (term, args) => select(NOTIFY_NAMESPACE).findNotice(term, args),

	/**
	 * @param {{context: string, title: *, message: *}} payload
	 */
	add: (payload) => dispatch(NOTIFY_NAMESPACE).addNotice(payload),

	/**
	 * @param {null} id
	 */
	remove: (id) => dispatch(NOTIFY_NAMESPACE).removeNotice(id),

	/**
	 * @param {string|false} context
	 */
	clear: (context = 'adminbar') => dispatch(NOTIFY_NAMESPACE).clear(context),
};
/** export wp-notify for further uses */
export default notify;

/** append the wp-notify instance to window.wp in order to provide a public API */
window.wp.notify = notify;

/**
 * Loops into contexts and register the found locations into the store state
 *
 * @param {context} context
 */
contexts.forEach((context) =>
	select(NOTIFY_NAMESPACE).registerContext(context)
);

/** after registering contexts we could fetch the notifications */
select(NOTIFY_NAMESPACE).fetchUpdates();

/**
 * Loops into contexts and adds a NoticesArea component for each one
 */
contexts.forEach((context) => {
	/**
	 * Renders the component into the specified context
	 *
	 * @member {HTMLElement} notifyDash - the area that will host the notifications
	 */
	render(
		createElement(NoticesArea, {
			context,
			splitBy: context === 'adminbar' ? 'date' : undefined,
		}),
		document.getElementById(`wp-notify-${context}`)
	);
});
