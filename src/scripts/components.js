/**
 * Notification UI components
 */
import { removeNotice } from './reducer';

const { __ } = wp.i18n;
import { Component } from '@wordpress/element';
import moment from 'moment';
import { store } from './wp-notify';
import { delay } from './utils';

import wpLogo from '../images/WordPressLogo.svg';

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
						key={ k }
						image={ notify.image }
						title={ notify.title }
						message={ notify.message }
						acceptMessage={ notify.acceptMessage }
						acceptLink={ notify.acceptLink }
						dismissLabel={ notify.dismissLabel }
						source={ notify.source }
						date={ notify.date }
						location={ notify.location }
						dismissible={ notify.dismissible }
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

// Notice class method.
class Notice extends Component {
	// the default props
	static defaultProps = {
		key: false,
		title: undefined,
		message: undefined,
		acceptMessage: __( 'Accept' ),
		acceptLink: '#',
		dismissLabel: __( 'dismiss' ),
		source: 'WordPress',
		date: __( 'Just now' ),
		dismissible: false,
		onDismiss: () => delay( 100 ).then( this.unmount() ),
	};

	render() {
		let classes = 'wp-notification wp-notice-' + this.props.id;
		classes += this.props.dismissible ? ' is-dismissible' : '';
		classes += this.props.severity ? ' is-' + this.props.severity : '';

		return (
			<div className={ classes }>
				<div className="wp-notification-wrap">
					<h3 className="wp-notification-title">
						{ this.props.title }
					</h3>
					<p
						dangerouslySetInnerHTML={ {
							__html: this.props.message,
						} }
					/>
					{ this.props.location === 'dashboard' ? (
						<div className="wp-notification-actions-wrap">
							<a
								className="button button-primary wp-notification-hub-trigger"
								href={ this.props.acceptLink }
							>
								{ this.props.acceptMessage }
							</a>
							{ this.props.dismissible && (
								<button
									className="button button-link wp-notification-hub-dismiss"
									onClick={ this.props.onDismiss }
								>
									<span className="dashicons dashicons-no-alt"></span>
									{ this.props.dismissLabel }
								</button>
							) }
						</div>
					) : (
						<a
							href={ this.props.acceptLink }
							className={ 'wp-notification-action' }
						>
							{ this.props.acceptMessage }
						</a>
					) }
					<p className="wp-notification-source">
						<span className="name">{ this.props.source }</span>{ ' ' }
						{ '\u2022 ' }
						<span className="date">
							{ moment.unix( this.props.date ).fromNow() }
						</span>
					</p>
				</div>
				{ ( this.props.image ||
					( ! this.props.image &&
						this.props.location !== 'dashboard' ) ) && (
					<div className="wp-notification-image">
						<img
							src={ this.props.image || wpLogo }
							alt={ this.props.title + ' image' }
						/>
					</div>
				) }
			</div>
		);
	}
}
