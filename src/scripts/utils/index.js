import { NOTIFY_NAMESPACE } from '../store/constants';
import { WEEK_IN_SECONDS } from '../components/NoticesArea';
import { dispatch } from '@wordpress/data';

/**
 * @typedef {import('../store').Notice} Notice
 */

/**
 * Delay returns a promise that resolves after the specified number of milliseconds.
 *
 * @param {number} ms - The number of milliseconds to delay.
 *
 * @return {Promise} - the resolution of the promise
 */
export const delay = ( ms ) => new Promise( ( f ) => setTimeout( f, ms ) );

/**
 * At the moment the function return the notifications if the split by isn't set to "date"
 *
 * @param {Notice[]} notifications The collection of notices to split.
 * @param {number}   limit         The date after which the notifications are considered to be old
 *
 * @return {Notice[][]} two list of Notifications, one for the new and one for the old
 */
export const splitByDate = (
	notifications,
	limit = Date.now() * 0.001 - WEEK_IN_SECONDS
) => {
	return notifications.reduce(
		( [ current, past ], item ) => {
			return item.date >= limit
				? [ [ ...current, item ], past ]
				: [ current, [ ...past, item ] ];
		},
		[ [], [] ]
	);
};

/**
 * It clears the notices in the selected context
 *
 * @param {string} context - The context of the notices. This is used to determine which notices to clear.
 */
export const clearNotifyDrawer = ( context ) => {
	dispatch( NOTIFY_NAMESPACE ).clear( context );
};
