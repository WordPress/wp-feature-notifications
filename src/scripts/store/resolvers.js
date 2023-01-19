import { fetchAPI, hydrate } from './actions';

/**
 * Fetch the rest api in order to get new notifications
 *
 * @return {Generator<{payload: *, type: string}, void, ?>} the generator object that will be used to add new notifications to the generator to the store
 */
export const fetchUpdates = function* () {
	const newNotifications = yield fetchAPI();

	console.log('Retrieved new notifications: ', newNotifications);

	if (newNotifications) {
		return hydrate(newNotifications);
	}
};
