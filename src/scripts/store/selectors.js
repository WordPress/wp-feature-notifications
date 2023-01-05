/**
 * A "selector function" is any function that accepts the Redux store state (or part of the state) as an argument, and returns data that is based on that state.
 */

export const fetchUpdates = (state) => state || {};

export const getNotices = (state, location) => {
	return location ? state[location] : state;
};

export function registerContext(state, context) {
	if (!state[context]) {
		state[context] = [];
	}

	console.log('added context ' + context, state);

	return state;
}
