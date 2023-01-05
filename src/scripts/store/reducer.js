function findContext(notifications, key) {
	notifications.forEach((location) => {
		const found = location.find((notification) => notification.id === key);
		if (found) {
			return found;
		}
	});
}
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
		case 'ADD':
			return {
				...state,
				[action.payload.context]: [
					...state[action.payload.context],
					action.payload,
				],
			};

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

		case 'DELETE':
			const context = findContext(action.key);
			return {
				...state[context].slice(0, action.key),
				...state[context].slice(action.key + 1),
			};

		case 'CLEAR':
			state[action.payload.context] = [];
			return state;

		case 'UPDATE':
			return state[action.payload.context].map((notice) =>
				notice.key === action.key ? action.payload : notice
			);
	}

	return state;
};

export default reducer;
