import { fetchAPI, hydrate } from './actions';

export const fetchUpdates = function* () {
	const newNotifications = yield fetchAPI();

	console.log('Retrieved new notifications: ', newNotifications);

	if (newNotifications) {
		return hydrate(newNotifications);
	}
};
