/* eslint-disable jsdoc/require-returns-type */

/**
 * @typedef {import('./index').Notice} Notice
 */

/**
 * Action creator to hydrate a notice from the store.
 *
 * @param {Notice[]} payload The notices to hydrate.
 * @return A redux action.
 */
export const hydrate = ( payload ) => {
	return {
		type: /** @type {const} */ ( 'HYDRATE' ),
		payload,
	};
};

/**
 * Action creator to clear a notification context from the store.
 *
 * @param {string} context The slug of the context to clear.
 * @return A redux action.
 */
export const clear = ( context ) => {
	return {
		type: /** @type {const} */ ( 'CLEAR' ),
		context,
	};
};

/**
 * Action creator to add a notice to the store.
 *
 * @param {Notice} payload The notice to add.
 * @return A redux action.
 */
export const addNotice = ( payload ) => {
	return {
		type: /** @type {const} */ ( 'ADD' ),
		payload,
	};
};

/**
 * Action creator to remove a notice from the store.
 *
 * @param {number} id The id of the notice to remove.
 * @return A redux action.
 */
export const removeNotice = ( id ) => {
	return {
		type: /** @type {const} */ ( 'DELETE' ),
		id,
	};
};

/**
 * Action creator to update a notice in the store.
 *
 * @param {Pick<Notice, 'id'>|Partial<Notice>} payload
 * @return A redux action.
 */
export const updateNotice = ( payload ) => {
	return {
		type: /** @type {const} */ ( 'UPDATE' ),
		payload,
	};
};

/**
 * Action creator to fetch notices.
 *
 * @param {string} path The REST API route from which to fetch notices.
 * @return A redux action.
 */
export const fetchAPI = ( path = '' ) => {
	return {
		type: /** @type {const} */ ( 'FETCH' ),
		path,
	};
};
