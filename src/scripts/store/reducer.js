import { createSlice } from '@reduxjs/toolkit';

// TODO: maybe a filter is needed in order to add some custom places?
const locations = ['dashboard', 'adminbar'];
const notifyCollection = {};

// It's a shorthand for:
// locations = { notifyCollection.dashboard = [], notifyCollection.adminbar = []}
locations.forEach((location) => (notifyCollection[location] = []));

/**
 * Handle changes and returns the array of displayed notifications (a slice of the Redux store)
 *
 * @property {Object}   reducers              - the store reducers
 * @typedef reducers
 * @property {Function} reducers.addNotice    - adds the current notification into current state
 * @property {Function} reducers.removeNotice - remove the given notification from current state
 * @property {Function} reducers.clearNotices - wipe all notifications from the current state
 */
const notifyController = createSlice({
	name: 'wp-notify',
	initialState: notifyCollection,
	reducers: {
		addNotice(state, action) {
			// provide a fallback if the location was missing
			const location = action.payload.location || 'adminbar';
			// adds the current notification into current state
			state[location].push(action.payload);
		},
		removeNotice(state, action) {
			state[action.payload.location] = [
				...state[action.payload.location].slice(0, action.payload.key),
				...state[action.payload.location].slice(action.payload.key + 1),
			];
		},
		clearNotices(state, action) {
			state[action.payload] = [];
		},
	},
});

/** Exporting the actions and the reducer */
export const { addNotice, removeNotice, clearNotices } =
	notifyController.actions;
export default notifyController.reducer;
