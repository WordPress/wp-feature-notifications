import { createReduxStore, register } from '@wordpress/data';

import { STORE_NAMESPACE } from '../constants';

import * as actions from './actions';
import * as controls from './controls';
import reducer from './reducer';
import * as resolvers from './resolvers';
import * as selectors from './selectors';

/**
 *
 * @typedef {import('@wordpress/data/build-types/types').ReduxStoreConfig<State, typeof actions, typeof selectors>} StoreConfig
 * @typedef {import('@wordpress/data/build-types/types').StoreDescriptor<StoreConfig>} NoticeStore
 */

/**
 * @template {Record<string, unknown>} T
 * @template {keyof T} K
 * @typedef {T[K]} ValuesOf
 */

/**
 * @typedef {ReturnType<ValuesOf<typeof actions, keyof actions>>} Action
 */

/**
 *
 * @typedef {Object} DashiconsIcon The Dashicons icon type.
 * @property {string} dashicons The Dashicons slug of the icon.
 */

/**
 * @typedef {Object} ImageIcon The image icon type.
 * @property {string} src The url of the image icon.
 */

/**
 * @typedef {Object} SvgIcon The SVG icon type.
 * @property {string} svg The SVG markup of the icon.
 */

/**
 * @typedef {DashiconsIcon|ImageIcon|SvgIcon} NoticeIcon The notification icon type.
 */

/**
 * @typedef {Object} NoticeAction The notification action type.
 * @property {string} acceptLink    The url of the action.
 * @property {string} acceptMessage The label of the action.
 */

/**
 * @typedef {'undisplayed'|'displayed'|'dismissed'|'new'} NoticeStatus The notification status type.
 */

/**
 * @typedef {Object} Notice The notification type.
 * @property {NoticeAction=} action       The optional action associated to the notification.
 * @property {string=}       context      The rendering context of the notification.
 * @property {Date}          date         The date from which the notification was emitted.
 * @property {string=}       dismissLabel The label of the dismiss action.
 * @property {boolean=}      dismissible  Predicate of whether the notification can be dismissed.
 * @property {NoticeIcon=}   icon         The optional icon.
 * @property {number}        id           The database id of the notification message.
 * @property {string=}       message      The message content of the notification.
 * @property {string=}       severity     The severity of the notification.
 * @property {string=}       source       The source of the notification.
 * @property {NoticeStatus=} status       The status of the notification.
 * @property {string}        title        The title of the notification message.
 */

/**
 * @typedef {Record<string, Notice[]>} State The notifications redux store type.
 */

/**
 * Creating a store for the redux state.
 *
 * A Redux store that lets you read the state, dispatch actions and subscribe to changes.
 */
const store = /** @type {NoticeStore} */ (
	createReduxStore( STORE_NAMESPACE, {
		reducer,
		actions,
		selectors,
		controls,
		resolvers,
	} )
);

register( store );

export default store;
