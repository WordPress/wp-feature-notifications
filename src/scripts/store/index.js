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
 * @typedef { import('node_modules/@wordpress/data/build-types/index.d.ts').createReduxStore } createReduxStore
 * @typedef { import('node_modules/@wordpress/data/build-types/index.d.ts').register } register
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
