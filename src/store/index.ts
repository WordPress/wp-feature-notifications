import { createReduxStore, register } from '@wordpress/data';

import { STORE_NAMESPACE } from '../constants';
import type { Notice } from '../types';

import * as actions from './actions';
import reducer from './reducer';
import * as selectors from './selectors';

/**
 * The notifications redux store type.
 */
export type State = Record< string, Notice[] >;

/**
 * The plain (non-thunk) actions handled by the notifications reducer.
 */
export type Action =
	| ReturnType< typeof actions.registerContext >
	| ReturnType< typeof actions.hydrate >
	| ReturnType< typeof actions.clear >
	| ReturnType< typeof actions.addNotice >
	| ReturnType< typeof actions.removeNotice >
	| ReturnType< typeof actions.updateNotice >;

/**
 * Resolvers fetch the notices the first time a context is read.
 *
 * Implemented as a thunk so no separate controls/resolvers indirection is needed.
 */
const resolvers = {
	getNotices:
		() =>
		async ( { dispatch }: actions.ThunkArgs ) => {
			await dispatch.fetchUpdates();
		},
};

/**
 * Creating a store for the redux state.
 *
 * A Redux store that lets you read the state, dispatch actions and subscribe to changes.
 */
const store = createReduxStore< State, typeof actions, typeof selectors >(
	STORE_NAMESPACE,
	{
		reducer,
		actions,
		selectors,
		resolvers,
	}
);

register( store );

export type NoticeStore = typeof store;
export default store;
