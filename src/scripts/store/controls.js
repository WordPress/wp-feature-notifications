import apiFetch from '@wordpress/api-fetch';
import { API_PATH } from './constants';

/**
 * Fetches the wp-notify rest api endpoint for the specified endpoint
 *
 * @param {string} action - the action to execute
 * @return {Promise} - the Promise with the results
 */
export const FETCH = ( action ) => {
	return apiFetch( {
		path: API_PATH + action.path,
	} );
};
