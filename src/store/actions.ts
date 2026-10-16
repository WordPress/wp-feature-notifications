import apiFetch from '@wordpress/api-fetch';

import { API_PATH } from '../constants';
import type { Notice } from '../types';

import { normalizeNotices } from './normalize';
import type { RestNotification } from './normalize';

/**
 * The helpers exposed to a store thunk by `@wordpress/data`.
 */
export type ThunkArgs = {
	dispatch: {
		fetchUpdates: () => Promise< Notice[] >;
		hydrate: ( payload: Notice[] ) => ReturnType< typeof hydrate >;
	};
};

/**
 * Action creator to register a display context in the store.
 *
 * @param context The slug of the context to register.
 * @return A redux action.
 */
export const registerContext = ( context: string ) => {
	return {
		type: 'REGISTER_CONTEXT' as const,
		context,
	};
};

/**
 * Action creator to hydrate a notice from the store.
 *
 * @param payload The notices to hydrate.
 * @return A redux action.
 */
export const hydrate = ( payload: Notice[] ) => {
	return {
		type: 'HYDRATE' as const,
		payload,
	};
};

/**
 * Action creator to clear a notification context from the store.
 *
 * @param context The slug of the context to clear.
 * @return A redux action.
 */
export const clear = ( context: string ) => {
	return {
		type: 'CLEAR' as const,
		context,
	};
};

/**
 * Action creator to add a notice to the store.
 *
 * @param payload The notice to add.
 * @return A redux action.
 */
export const addNotice = ( payload: Notice ) => {
	return {
		type: 'ADD' as const,
		payload,
	};
};

/**
 * Action creator to remove a notice from the store.
 *
 * @param id The id of the notice to remove.
 * @return A redux action.
 */
export const removeNotice = ( id: number ) => {
	return {
		type: 'DELETE' as const,
		id,
	};
};

/**
 * Action creator to update a notice in the store.
 *
 * @param payload
 * @return A redux action.
 */
export const updateNotice = (
	payload: Pick< Notice, 'id' > | Partial< Notice >
) => {
	return {
		type: 'UPDATE' as const,
		payload,
	};
};

/**
 * In-flight fetch promise, used to de-duplicate concurrent `fetchUpdates` dispatches.
 *
 * The `getNotices` resolver runs once per distinct context, so on a page that renders
 * several contexts (adminbar + dashboard) the first reads happen at the same time and
 * would otherwise issue one identical request each.
 */
let inFlightFetch: Promise< Notice[] > | null = null;

/**
 * Thunk action creator to fetch notifications from the REST API and hydrate them
 * into the store.
 *
 * Unlike the previous generator resolver, this thunk dispatches the `HYDRATE` action
 * itself, so the fetched notices actually reach the state.
 *
 * @return A thunk that fetches and hydrates the notices.
 */
export const fetchUpdates =
	() =>
	async ( { dispatch }: ThunkArgs ) => {
		if ( inFlightFetch ) {
			return inFlightFetch;
		}

		const request = ( async () => {
			try {
				const response = ( await apiFetch( {
					path: API_PATH,
				} ) ) as RestNotification[];

				const notices = normalizeNotices( response );

				dispatch.hydrate( notices );

				return notices;
			} finally {
				inFlightFetch = null;
			}
		} )();

		inFlightFetch = request;

		return request;
	};
