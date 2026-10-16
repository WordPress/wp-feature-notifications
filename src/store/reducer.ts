import type { Reducer } from 'redux';

import type { Notice } from '../types';

import { defaultContext } from './constants';
import { findContext } from './utils';

import type { Action, State } from './index';

/**
 * Merge incoming notices into an existing list, upserting by id.
 *
 * This keeps the state free of duplicate ids when a context is hydrated more than
 * once (for example by a manual refresh after the initial resolver fetch). The index
 * map keeps the merge linear in the number of notices.
 *
 * @param existing The notices already in the state for the context.
 * @param incoming The notices to merge in.
 *
 * @return The merged list of notices.
 */
const mergeNotices = (
	existing: Notice[] | undefined,
	incoming: Notice[]
): Notice[] => {
	const merged = [ ...( existing ?? [] ) ];
	const indexById = new Map(
		merged.map( ( notice, index ) => [ notice.id, index ] )
	);

	incoming.forEach( ( notice ) => {
		const index = indexById.get( notice.id );

		if ( index === undefined ) {
			indexById.set( notice.id, merged.length );
			merged.push( notice );
		} else {
			merged[ index ] = notice;
		}
	} );

	return merged;
};

/**
 * Reducer returning the next notices state. The notices state is an object
 * where each key is a context, its value an array of notice objects.
 *
 * @param state  The notifications redux state.
 * @param action The current action.
 */
const reducer: Reducer< State, Action > = ( state = {}, action ) => {
	switch ( action.type ) {
		case 'REGISTER_CONTEXT': {
			if ( state[ action.context ] ) {
				return state;
			}

			return { ...state, [ action.context ]: [] };
		}
		case 'HYDRATE': {
			const updated = { ...state };
			action.payload.forEach( ( notification ) => {
				const context = notification.context || defaultContext;
				updated[ context ] = mergeNotices( updated[ context ], [
					notification,
				] );
			} );
			return updated;
		}
		case 'ADD': {
			const context = action.payload.context || defaultContext;
			return {
				...state,
				[ context ]: mergeNotices( state[ context ], [
					action.payload,
				] ),
			};
		}
		case 'DELETE': {
			const context = findContext( state, action.id );
			if ( ! context ) {
				return state;
			}
			return {
				...state,
				[ context ]: state[ context ].filter(
					( notice ) => notice.id !== action.id
				),
			};
		}
		case 'CLEAR': {
			return {
				...state,
				[ action.context ]: [],
			};
		}
		case 'UPDATE': {
			const context = findContext( state, action.payload.id );
			if ( ! context ) {
				return state;
			}
			return {
				...state,
				[ context ]: state[ context ].map( ( notice ) =>
					notice.id === action.payload.id
						? { ...notice, ...action.payload } // merge the new object with the old object
						: notice
				),
			};
		}
	}

	return state;
};

export default reducer;
