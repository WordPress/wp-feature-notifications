import { fetchAPI, hydrate, rehydrate } from './actions';

/**
 * Fetch the rest api in order to get new notifications
 *
 * @param force
 */
export const fetchUpdates = function* ( force = false ) {
	const newNotifications = yield fetchAPI();

	const action = force ? rehydrate : hydrate;

	if ( newNotifications ) {
		return action( newNotifications );
	}
};
