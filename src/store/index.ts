import { createReduxStore, register } from '@wordpress/data';

import { STORE_NAMESPACE } from '../constants';
import type { Notice } from '../types';

import * as actions from './actions';
import * as controls from './controls';
import reducer from './reducer';
import * as resolvers from './resolvers';
import * as selectors from './selectors';

/**
 * The notifications redux store type.
 */
export type State = Record< string, Notice[] >;

type ValuesOf<
	T extends Record< string, unknown >,
	K extends keyof T = keyof T
> = T[ K ];

/**
 * The actions of the notifications redux reducer.
 */
export type Action = ReturnType< ValuesOf< typeof actions > >;

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
		controls,
		resolvers,
	}
);

register( store );

export type NoticeStore = typeof store;
export default store;
