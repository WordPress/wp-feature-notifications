import { WEEK_IN_SECONDS } from '../components/NoticesArea';

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
 * @param {Array}  notifications
 * @param {string} by
 *
 * @return {Array} two list of Notifications, one for the new and one for the old
 */
export const getSorted = ( notifications, by = 'date' ) => {
	const Limit = by === 'date' ? Date.now() - WEEK_IN_SECONDS : false;
	if ( Limit ) {
		return notifications.reduce(
			( [ current, past ], item ) => {
				return item.date >= Limit
					? [ [ ...current, item ], past ]
					: [ current, [ ...past, item ] ];
			},
			[ [], [] ]
		);
	}
	return notifications;
};
