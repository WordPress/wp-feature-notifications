/**
 * Find the context for the given notification key.
 *
 * @param {Object} notifications - The notifications object to search in
 * @param {number} id            - The notification id to search
 */
export function findContext( notifications, id ) {
	for ( const location in notifications ) {
		const found = notifications[ location ].find(
			( notification ) => notification.id === id
		);
		if ( found ) {
			return location;
		}
	}
}
