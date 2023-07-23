import apiFetch from '@wordpress/api-fetch';

import { API_PATH } from '../constants';
import type { Notice } from '../types';

/**
 * Fetches the wp-notifications rest api endpoint for the specified endpoint
 *
 * @param action The action to execute
 * @return The Promise with the results
 */
export function FETCH< Action extends { path: string } >( action: Action ) {
	return apiFetch( {
		path: API_PATH + action.path,
	} ).then( ( notices: any[] ) =>
		notices.map(
			( notice ) =>
				( {
					...notice,
					date: new Date( notice.date ),
				} as Notice )
		)
	);
}
