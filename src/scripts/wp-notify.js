/** WordPress Dependencies */
import { createElement, render } from '@wordpress/element';
import { dispatch, select } from '@wordpress/data';

/** WP Notify - Components */
import { NoticesArea } from './components/NoticesArea';

/** The store default data */
import { NOTIFY_NAMESPACE, contexts } from './store/constants';

/**
 * The redux store
 */
export * as store from './store';

/**
 * Wp-Notify Api
 *
 * @member {Object} notify
 */
const notify = {
	/**
	 * Fetch for new notices
	 */
	fetchUpdates: () => select( NOTIFY_NAMESPACE ).fetchUpdates(),

	/**
	 * List all notifications or those of a particular context
	 *
	 * @param {string|false} context
	 */
	get: ( context = '' ) => select( NOTIFY_NAMESPACE ).getNotices( context ),

	/**
	 * Search for a notification by key or term (term is optional, returns an array of objects)
	 *
	 * @param {string} term
	 * @param {Object} args
	 * @example ```js
	 * // if you need to find a notification by key
	 * notify.find(5) // [{ 'id': 5, title: "hello", location: "dashboard", ... }]
	 * // or by term
	 * notify.find("hello", {term: 'title'}) // [{ 'id': 5, title: "hello", location: "dashboard", ... }, {...}]
	 * ```
	 */
	find: ( term, args ) => select( NOTIFY_NAMESPACE ).findNotice( term, args ),

	/**
	 * Add a new notification
	 *
	 * @param {Object} payload
	 */
	add: ( payload ) => dispatch( NOTIFY_NAMESPACE ).addNotice( payload ),

	/**
	 * Remove a notification by key
	 *
	 * @param {number} id
	 */
	remove: ( id ) => dispatch( NOTIFY_NAMESPACE ).removeNotice( id ),

	/**
	 * Clear all notifications
	 *
	 * @param {string|false} context
	 */
	clear: ( context = 'adminbar' ) =>
		dispatch( NOTIFY_NAMESPACE ).clear( context ),
};

/** Appends the wp-notify instance to window.wp in order to provide a public API */
window.wp.notify = notify;

/**
 * Loops into contexts and register the found locations into the store state
 *
 * @param {string} context
 */
contexts.forEach( ( context ) =>
	select( NOTIFY_NAMESPACE ).registerContext( context )
);

/** after registering contexts we could fetch the notifications */
select( NOTIFY_NAMESPACE ).fetchUpdates();

/**
 * Loops into contexts and adds a NoticesArea component for each one
 */
contexts.forEach( ( context ) => {
	/**
	 * Renders the component into the specified context
	 *
	 * @member {HTMLElement} notifyDash - the area that will host the notifications
	 */
	render(
		createElement( NoticesArea, {
			context,
			splitBy: context === 'adminbar' ? 'date' : undefined,
		} ),
		document.getElementById( `wp-notify-${ context }` )
	);
} );

/**
 *  exports notify store functions for further uses
 */
export default notify;
