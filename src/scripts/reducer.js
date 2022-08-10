import { createSlice } from '@reduxjs/toolkit';
import axios from 'axios';
import { delay } from './utils';

// TODO: maybe a filter is needed in order to add some custom places?
const locations = [ 'dashboard', 'adminbar' ];
const notifyCollection = {};
/* It's a shorthand for:
 *  locations = { notifyCollection.dashboard = [], notifyCollection.adminbar = []}
 * */
locations.forEach( ( location ) => ( notifyCollection[ location ] = [] ) );

/**
 * Handle changes and returns the array of displayed notifications (a slice of the Redux store)
 *
 * @property {Object}   reducers              - the store reducers
 * @typedef reducers
 * @property {Function} reducers.addNotice    - adds the current notification into current state
 * @property {Function} reducers.removeNotice - remove the given notification from current state
 * @property {Function} reducers.clearNotices - wipe all notifications from the current state
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
			state[ action.payload.location ] = [
				...state[ action.payload.location ].slice(
					0,
					action.payload.key
				),
				...state[ action.payload.location ].slice(
					action.payload.key + 1
				),
			];
		},
		clearNotices( state, action ) {
			state[ action.payload ] = [];
		},
	},
} );

/**
 * It fetches a list of notices from the API,
 * and then dispatches an action to add each notice to the store
 *
 * @param {string} path - The path to the API endpoint.
 *
 * @return {Function} A function that takes a dispatch function as an argument.
 */
export function fetchApi( path ) {
	return function ( dispatch ) {
		return axios.get( path ).then( ( response ) => {
			// TODO: or maybe it's better to deliver all notifications immediately
			response.data.forEach( ( notice ) => {
				delay( 100 ).then( () => dispatch( addNotice( notice ) ) );
			} );
		} );
	};
}

/** Exporting the actions and the reducer */
export const { addNotice, removeNotice, clearNotices } =
	notifyController.actions;
export default notifyController.reducer;
