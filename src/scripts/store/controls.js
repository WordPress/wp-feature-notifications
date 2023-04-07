import apiFetch from '@wordpress/api-fetch';
import { API_PATH } from './constants';

/**
 * @typedef {import('./index').Notice} Notice
 */
/**
 * Fetches the wp-notify rest api endpoint for the specified endpoint
 *
 * @param {{path: string}} action The action to execute
 * @return {Promise<Notice>} The Promise with the results
 */
export const FETCH = ( action ) => {
	return apiFetch( {
		path: API_PATH + action.path,
	} );
};
