import type { Reducer } from 'redux';

import { findContext } from './utils';

import type { Action, State } from './index';

/**
 * Reducer returning the next notices state. The notices state is an object
 * where each key is a context, its value an array of notice objects.
 *
 * @param state  The notifications redux state.
 * @param action The current action.
 */
const reducer: Reducer< State, Action > = ( state = {}, action ) => {
	switch ( action.type ) {
		case 'HYDRATE': {
			let updated = { ...state };
			action.payload.forEach( ( notification ) => {
				const context = notification.context || 'adminbar';
				updated = {
					...updated,
					[ context ]: [ ...updated[ context ], notification ],
				};
			} );
			return updated;
		}
		case 'REHYDRATE': {
			let updated = { ...state };
			// Merge the new notifications with the existing ones.
			action.payload.forEach( ( notification ) => {
				const context = notification.context || 'adminbar';

				const existingOnes = updated[ context ] || [];
				const existing = existingOnes.findIndex(
					( notice ) => notice.id === notification.id
				);

				if ( existing > -1 ) {
					updated[ context ][ existing ] = notification;
				} else {
					updated = {
						...updated,
						[ context ]: [ ...updated[ context ], notification ],
					};
				}
			} );

			// Remove any notifications that are no longer in the payload.
			for ( const context in updated ) {
				updated[ context ] = updated[ context ].filter( ( notice ) => {
					return action.payload.find( ( payloadNotice ) => {
						return payloadNotice.id === notice.id;
					} );
				} );
			}

			return updated;
		}
		case 'ADD': {
			return {
				...state,
				[ action.payload.context ]: [
					...state[ action.payload.context ],
					action.payload,
				],
			};
		}
		case 'DELETE': {
			const context = findContext( state, action.id );
			return {
				...state,
				[ context ]: state[ context ].filter(
					( notice ) => notice.id !== action.id
				),
			};
		}
		case 'CLEAR': {
			state[ action.context ] = [];
			return { ...state };
		}
		case 'UPDATE': {
			const context = findContext( state, action.payload.id );
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
