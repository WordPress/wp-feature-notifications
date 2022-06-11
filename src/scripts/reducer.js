import { createSlice } from '@reduxjs/toolkit';
import axios from 'axios';

// TODO: maybe a filter is needed in order to add some custom places?
const locations = [ 'dashboard', 'adminbar' ];
const notifyCollection = {};
locations.forEach( ( location ) => ( notifyCollection[ location ] = [] ) );

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
				state[ action.payload.location ].findIndex(
					( arrow ) => arrow.id === action.payload.key
				),
				1
			);
		},
		clearNotices( state, action ) {
			state[ action.payload ] = [];
		},
	},
} );

export function fetchApi( path ) {
	return function ( dispatch ) {
		return axios.get( path ).then( ( response ) => {
			response.data.forEach( ( notice ) => {
				dispatch( addNotice( notice ) );
			} );
		} );
	};
}

export const {
	addNotice,
	removeNotice,
	clearNotices,
} = notifyController.actions;
export default notifyController.reducer;
