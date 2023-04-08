import { __ } from '@wordpress/i18n';
import { findContext } from './utils';

/**
 * @typedef {import('./index').Notice} Notice
 * @typedef {import('./index').State} State
 */

/**
 * Fetch the rest api in order to get new notifications
 *
 * @param {State} state the current state
 * @return {State} the new notifications
 */
export const fetchUpdates = ( state ) => state || {};

/**
 * Get the notices for the given context
 *
 * @param {State}  state   the current state
 * @param {string} context the name of the list of notifications you want to retrieve
 *
 * @return {Notice[]} the list of notices of the context
 */
export const getNotices = ( state, context ) => {
	return state[ context ];
};

/**
 * Adds a context to the current state.
 * commonly it's fired when the NotifyArea is registered
 *
 * @param {State}  state   the current state
 * @param {string} context the context to add
 *
 * @return {State} the notice store state
 */
export const registerContext = ( state, context ) => {
	if ( ! state[ context ] ) {
		state[ context ] = [];
	}
	return state;
};

/**
 * It searches the Redux store for a notification by ID or by a search term
 *
 * @param {State}         state      - the current state
 * @param {string|number} searchTerm - The term you want to search for.
 * @param {?Object|Array} [args]     - search args
 *
 * @return {Notice|Notice[]|string} the search result
 */
export const findNotice = ( state, searchTerm, args = { term: 'source' } ) => {
	// return the notification by id
	if ( typeof searchTerm === 'number' ) {
		const context = findContext( state, searchTerm );
		return state[ context ].find( ( notice ) => notice.id === searchTerm );
	}

	// Search the notification by key and searchTerm
	if ( typeof searchTerm === 'string' ) {
		const searchFor = args.term || 'source';
		searchTerm = searchTerm.toLowerCase();
		// merge all Object state Items into a single state item
		const found = Object.values( state )
			.flat()
			.filter(
				( el ) =>
					el[ searchFor ] &&
					el[ searchFor ].toLowerCase().includes( searchTerm )
			);

		if ( found.length ) {
			return found;
		}
	}
	return __( 'nothing was found' );
};
