import apiFetch from '@wordpress/api-fetch';
import { API_PATH } from './constants';

export const FETCH = (action) => {
	return apiFetch({
		path: API_PATH + action.path,
	});
};
