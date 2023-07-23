import type { State } from './index';

/**
 * Find the context for the given notification key.
 *
 * @param notifications - The notifications object to search in
 * @param id            - The notification id to search
 */
export function findContext( notifications: State, id: number ) {
	for ( const location in notifications ) {
		const found = notifications[ location ].find(
			( notification ) => notification.id === id
		);
		if ( found ) {
			return location;
		}
	}
}
