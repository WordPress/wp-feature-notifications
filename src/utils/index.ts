import { dispatch } from '@wordpress/data';
import { dateI18n } from '@wordpress/date';

import { WEEK_IN_MILLISECONDS, STORE_NAMESPACE } from '../constants';
import type { Notice } from '../types';

/**
 * Delay returns a promise that resolves after the specified number of milliseconds.
 *
 * @param ms The number of milliseconds to delay.
 *
 * @return The resolution of the promise
 */
export const delay = ( ms: number ) =>
	new Promise( ( f ) => setTimeout( f, ms ) );

/**
 * At the moment the function return the notifications if the split by isn't set to "date"
 *
 * @param notifications The collection of notices to split.
 * @param limit         The date after which the notifications are considered to be old
 *
 * @return Two list of Notifications, one for the new and one for the old
 */
export const splitByDate = (
	notifications: Notice[],
	limit = Date.now() - WEEK_IN_MILLISECONDS
): [ Notice[], Notice[] ] => {
	return notifications.reduce(
		( [ current, past ], notice ) => {
			return notice.date.getTime() >= limit
				? [ [ ...current, notice ], past ]
				: [ current, [ ...past, notice ] ];
		},
		[ [], [] ]
	);
};

/**
 * Convert a `Date` to an integer of seconds
 *
 * @param date The date to convert.
 *
 * @return The integer value of the `date` in seconds.
 */
export const dateToSeconds = ( date: Date ) => {
	return Math.floor( date.getTime() * 0.001 );
};

/**
 * The current date time in seconds.
 *
 * @return The integer value of the `date` in seconds.
 */
export const nowInSeconds = () => {
	return Math.floor( Date.now() * 0.001 );
};

/**
 * Format the date from epoch to human-readable format.
 *
 * @param date The date to convert in epoch format.
 *
 * @return The date in human-readable format.
 */
export const formatDate = ( date: Date ) => {
	return dateI18n( 'l jS F Y - h:i A', date, true );
};

/**
 * It clears the notices in the selected context
 *
 * @param context - The context of the notices. This is used to determine which notices to clear.
 */
export const clearNotificationsDrawer = ( context: string ) => {
	dispatch( STORE_NAMESPACE ).clear( context );
};
