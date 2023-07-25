import type { Notice } from '../types';

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
 * Action creator to fetch notices.
 *
 * @param path The REST API route from which to fetch notices.
 * @return A redux action.
 */
export const fetchAPI = ( path = '' ) => {
	return {
		type: 'FETCH' as const,
		path,
	};
};
