/** WordPress Dependencies */
import { dispatch, select } from '@wordpress/data';

import './wp-notifications.scss';

/** The store default data */
import { STORE_NAMESPACE } from './constants';
import { contexts } from './store/constants';
import type { Notice } from './types';
import { addContext } from './utils/init';

/**
 * The redux store
 */
export * as store from './store';

/**
 * WP Feature Notifications API
 */
const notifications = {
	/**
	 * Fetch for new notices
	 */
	fetchUpdates: () => select( STORE_NAMESPACE ).fetchUpdates(),

	/**
	 * List all notifications or those of a particular context
	 *
	 * @param context The display context to retrieve.
	 */
	get: ( context = '' ) => select( STORE_NAMESPACE ).getNotices( context ),

	/**
	 * Search for a notification by key or term (term is optional, returns an array of objects)
	 *
	 * @param term
	 * @param args
	 * @example ```js
	 * // if you need to find a notification by key
	 * notifications.find(5) // [{ 'id': 5, title: "hello", location: "dashboard", ... }]
	 * // or by term
	 * notifications.find("hello", {term: 'title'}) // [{ 'id': 5, title: "hello", location: "dashboard", ... }, {...}]
	 * ```
	 */
	find: ( term: string, args: any ) =>
		select( STORE_NAMESPACE ).findNotice( term, args ),

	/**
	 * Add a new notification
	 *
	 * @param payload
	 */
	add: ( payload: Notice ) =>
		dispatch( STORE_NAMESPACE ).addNotice( payload ),

	/**
	 * Remove a notification by key
	 *
	 * @param id The id of the notification to remove.
	 */
	remove: ( id: number ) => dispatch( STORE_NAMESPACE ).removeNotice( id ),

	/**
	 * Clear all notifications
	 *
	 * @param context The display context to clear of notifications.
	 */
	clear: ( context = 'adminbar' ) =>
		dispatch( STORE_NAMESPACE ).clear( context ),
};

/** Appends the wp-notifications instance to window.wp in order to provide a public API */
window.wp.notifications = notifications;

/**
 * Loops into contexts and register the found locations into the store state
 */
contexts.forEach( ( context ) =>
	select( STORE_NAMESPACE ).registerContext( context )
);

/** after registering contexts we could fetch the notifications */
select( STORE_NAMESPACE ).fetchUpdates();

/**
 * Loops into contexts and adds a NoticesArea component for each one
 */
contexts.forEach( ( context ) => addContext( context ) );

/**
 *  exports notifications store functions for further uses
 */
export default notifications;
