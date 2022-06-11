/**
 * Notification UI components
 */
import { removeNotice } from '../reducer';

import { Component } from '@wordpress/element';
import { store } from '../wp-notify';
import { delay } from '../utils';

import Notice from './notification';

// The notification container class
export default class Notifications extends Component {
	state = {
		notifications: [],
	};

	constructor( props ) {
		super( props );
		this.location = this.props.location;
		// watch for state updates
		store.subscribe( () => {
			this.setState( {
				notifications: [
					...store.getState().notifications[ this.location ],
				],
			} );
		} );
	}

	render() {
		return this.state.notifications.length
			? this.state.notifications.map( ( notify, k ) => (
					<Notice
						{ ...notify }
						key={ k }
						id={ k }
						image={ notify.image || notify.icon }
						onDismiss={ () =>
							delay( 100 ).then( () =>
								store.dispatch(
									removeNotice( {
										location: notify.location,
										key: k,
									} )
								)
							)
						}
					/>
			  ) )
			: null;
	}
}
