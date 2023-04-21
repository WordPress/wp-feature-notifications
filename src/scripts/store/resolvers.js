import { fetchAPI, hydrate } from './actions';

/**
 * Fetch the rest api in order to get new notifications
 */
export const fetchUpdates = function* () {
	const newNotifications = yield fetchAPI();

	if ( newNotifications ) {
		return hydrate( newNotifications );
	}
};
