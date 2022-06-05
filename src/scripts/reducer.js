import { createSlice } from '@reduxjs/toolkit';

// TODO: maybe a filter is needed in order to add some custom places?
const locations = [ 'dashboard', 'adminbar' ];
const notifyCollection = {};
locations.forEach( ( location ) => notifyCollection[ location ] = [] );

/**
 * Handle changes and returns the array of displayed notifications
 */
const notifyController = createSlice( {
	name: 'wp-notify',
	initialState: notifyCollection,
	reducers: {
		addNotice( state, action ) {
			// provide a fallback if the location was missing
			const location = action.payload.location || 'adminbar';
			// adds the current notification into current state
			state[ location ].push( action.payload );
		},
		removeNotice( state, action ) {
			state[ action.payload.location ].splice(
				state[ action.payload.location ].findIndex( ( arrow ) => arrow.id === action.payload.key )
				, 1
			);
		},
		clearNotices( state, action ) {
			state[ action.payload ] = [];
		},
	},
} );

export const {
	addNotice,
	removeNotice,
	clearNotices,
} = notifyController.actions;
export default notifyController.reducer;
