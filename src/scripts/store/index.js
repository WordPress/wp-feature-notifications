import { createReduxStore, register } from '@wordpress/data';

import { NOTIFY_NAMESPACE } from './constants';
import reducer from './reducer';
import * as actions from './actions';
import * as selectors from './selectors';
import * as controls from './controls';
import * as resolvers from './resolvers';

/**
 * Creating a store for the redux state.
 *
 * @typedef {Object} Store
 * @property {Function} dispatch - The only way to update the state is to call store.dispatch() and pass in an action object.
 * @property {Function} getState - Returns the current state tree of your application. It is equal to the last value returned by the store's reducer.
 *
 * @return {store} A Redux store that lets you read the state, dispatch actions and subscribe to changes.
 */
const store = createReduxStore(NOTIFY_NAMESPACE, {
	reducer,
	actions,
	selectors,
	controls,
	resolvers,
});

register(store);

export default store;
