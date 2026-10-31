import { __ } from '@wordpress/i18n';

import type { Notice } from '../types';

import { findContext } from './utils';

import type { State } from './index';

/**
 * Fetch the rest api in order to get new notifications
 *
 * @param state the current state
 * @param force
 * @return the new notifications
 */
export const fetchUpdates = ( state: State, force = false ): State =>
	state || {};

/**
 * Get the notices for the given context
 *
 * @param state   the current state
 * @param context the name of the list of notifications you want to retrieve
 *
 * @return The list of notices of the context
 */
export const getNotices = (
	state: State,
	context: string
): Notice[] | null => {
	return context ? state[ context ] : null;
};

/**
 * Adds a context to the current state.
 * commonly it's fired when the NotificationArea is registered
 *
 * @param state   The current state.
 * @param context The context to add.
 *
 * @return the notice store state
 */
export const registerContext = ( state: State, context: string ): State => {
	if ( ! state[ context ] ) {
		state[ context ] = [];
	}
	return state;
};

/**
 * It searches the Redux store for a notification by ID or by a search term
 *
 * @param state      The current state.
 * @param searchTerm The term to search for.
 * @param args       The search args.
 *
 * @return The search result
 */
export const findNotice = (
	state: State,
	searchTerm: string | number,
	args = { term: 'source' }
): Notice | Notice[] | string => {
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
