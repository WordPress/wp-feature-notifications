import { findContext } from './utils';

/**
 * Reducer returning the next notices state. The notices state is an object
 * where each key is a context, its value an array of notice objects.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
const reducer = (state = {}, action) => {
	switch (action.type) {
		case 'HYDRATE':
			let updated = { ...state };
			action.payload.forEach((notification) => {
				const context = notification.context || 'adminbar';
				updated = {
					...updated,
					[context]: [...updated[context], notification],
				};
			});
			return updated;

		case 'ADD':
			return {
				...state,
				[action.payload.context]: [
					...state[action.payload.context],
					action.payload,
				],
			};

		case 'DELETE':
			const context = findContext(state, action.id);
			return {
				...state,
				[context]: state[context].filter(
					(notice) => notice.id !== action.id
				),
			};

		case 'CLEAR':
			state[action.context] = [];
			return { ...state };

		case 'UPDATE':
			const location = findContext(state, action.payload.id);
			state[location].map((notice) =>
				notice.id === action.payload.id
					? { ...notice, ...action.payload } // merge the new object with the old object
					: notice
			);
	}

	return state;
};

export default reducer;
