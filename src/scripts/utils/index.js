import { NOTIFY_NAMESPACE } from '../store/constants';
import { WEEK_IN_SECONDS } from '../components/NoticesArea';
import { dispatch } from '@wordpress/data';
import { dateI18n } from '@wordpress/date';

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
 * @return {[Notice[], Notice[]]} two list of Notifications, one for the new and one for the old
 */
export const splitByDate = (
	notifications,
	limit = nowInSeconds() - WEEK_IN_SECONDS
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
 * Convert a `Date` to an integer of seconds
 *
 * @param {Date} date The date to convert.
 *
 * @return {number} The integer value of the `date` in seconds.
 */
export const dateToSeconds = ( date ) => {
	return Math.floor( date.getTime() * 0.001 );
};

/**
 * The current date time in seconds.
 *
 * @return {number} The integer value of the `date` in seconds.
 */
export const nowInSeconds = () => {
	return Math.floor( Date.now() * 0.001 );
};

/**
 * Format the date from epoch to human-readable format.
 *
 * @param {number} date The date to convert in epoch format.
 *
 * @return {string} The date in human-readable format.
 */
export const formatDate = ( date ) => {
	return dateI18n( 'l jS F Y - h:i A', new Date( date ), true );
};

/**
 * It clears the notices in the selected context
 *
 * @param {string} context - The context of the notices. This is used to determine which notices to clear.
 */
export const clearNotifyDrawer = ( context ) => {
	dispatch( NOTIFY_NAMESPACE ).clear( context );
};
