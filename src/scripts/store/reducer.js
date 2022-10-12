import { createSlice } from '@reduxjs/toolkit';
import { store } from '../wp-notify';
import { __ } from '@wordpress/i18n';

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

/**
 * It returns the notifications from the Redux store
 *
 * @param {string|false} location
 *
 * @return {store} The notifications state from the store.
 */
export const listNotices = (location = false) => {
	if (location) {
		const storeData = store.getState().notifications[location];
		if (storeData) return storeData;
	}
	return store.getState().notifications;
};

/**
 * It searches the Redux store for a notification by ID or by a search term
 *
 * @param {string} searchTerm - The term you want to search for.
 * @param {Object} [args]     - search args
 * @return {Object} the search result
 */
export const findNotice = (
	searchTerm,
	args = { key: 'source', location: 'dashboard' }
) => {
	// get the notifications array
	const storeData = store.getState().notifications;

	// return the notification by id and location
	if (typeof searchTerm === 'number' && args.location) {
		return storeData[args.location][searchTerm];
	}

	// search the notification by key and searchTerm
	const found = [];
	Object.entries(storeData).forEach((locationNotices) => {
		const location = locationNotices[0];
		found[location] = locationNotices[1].filter(
			(el) =>
				el[args.key] &&
				el[args.key].toLowerCase().includes(searchTerm) && {
					location: el,
				}
		);
	});

	return found ? found : __('Nothing was found');
};

export default notifyController.reducer;
