/**
 * Notification UI components
 */
import { clearNotices, removeNotice } from '../reducer';

import { Component } from '@wordpress/element';
import { Button } from '@wordpress/components';
import { store } from '../wp-notify';
import { delay } from '../utils';

import Notice from './notification';

// The notification container class
export default class Notifications extends Component {
	state = {
		notifications: [],
	};

	/**
	 * Sets the initial state of the component and binds the updateNoticeState function to the component
	 *
	 * @function Object() { [native code] }
	 *
	 * @param {Object} props - The props passed to the component.
	 */
	constructor( props ) {
		super( props );
		this.location = this.props.location;
		this.updateNoticeState = this.updateNoticeState.bind( this );
		// watch for state updates
		store.subscribe( () => {
			this.setState( {
				notifications: [
					...store.getState().notifications[ this.location ],
				],
			} );
		} );
	}

	/**
	 * Updating the state of the notifications object.
	 *
	 * @param {number}           key
	 * @param {string}           location
	 * @param {{status: string}} props
	 */
	updateNoticeState = ( key, location, props ) => {
		const newState = this.state;
		newState.notifications[ key ] = {
			...newState.notifications[ key ],
			...props,
		};
		return this.setState( { newState } );
	};

	/**
	 * Returns a list of notices
	 * each notice is a component that has a key, an id, an image, an additional class name, and an onDismiss function
	 *
	 * @param {Array} notifications - An array of objects that contain the notice data.
	 *
	 * @return {Array} An array of Notice components.
	 */
	printNotices( notifications ) {
		return notifications.map( ( notify, k ) => (
			<Notice
				{ ...notify }
				key={ k }
				id={ k }
				image={ notify.image || notify.icon }
				additionalClassName={
					this.state.notifications[ k ].status || 'showed'
				}
				onDismiss={ () => {
					this.updateNoticeState( k, notify.location, {
						status: 'dismissing',
					} );
					delay( 100 ).then( () =>
						store.dispatch(
							removeNotice( {
								location: notify.location,
								key: k,
							} )
						)
					);
				} }
			/>
		) );
	}

	/**
	 * It takes a list of notifications, sorts them into two lists,
	 * one for current notifications and one for past notifications,
	 * and then renders a list of notifications for each list
	 *
	 * @param {Array} notifications
	 * @return {Array} A list of notifications split by current (the last 7 days) and past (before current)
	 */
	noticesList( notifications ) {
		if ( ! notifications.length ) return null;

		// TODO: i've faked the sorting option and whatever argument passed will render a list of notifications split by current (the last 7 days) and past (before current)
		const sortedNotifications = notifications.reduce(
			( [ current, past ], item ) => {
				return item.date >= Date.now() / 1000 - 3600 * 24 * 7
					? [ [ ...current, item ], past ]
					: [ current, [ ...past, item ] ];
			},
			[ [], [] ]
		);

		return sortedNotifications.map( ( list, index ) => (
			<section key={ index }>
				{ ! index && list ? (
					<header>
						<h2>{ list.length } unread notifications</h2>
						<Button
							id="clear-all-wp-notify-hub"
							className="wp-notification-action wp-notification-action-markread button-link"
							onClick={ () =>
								store.dispatch(
									clearNotices( this.props.location )
								)
							}
						>
							<span className="ab-icon dashicons-saved"></span>{ ' ' }
							Mark all as read
						</Button>
					</header>
				) : (
					<header>
						<h2>Older notifications</h2>
					</header>
				) }
				{ list ? this.printNotices( list ) : null }
			</section>
		) );
	}

	render() {
		return this.props.splitBy
			? this.noticesList( this.state.notifications ) || null
			: this.printNotices( this.state.notifications );
	}
}
