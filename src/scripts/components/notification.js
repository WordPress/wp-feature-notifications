/**
 * Single Notification UI component
 */
const { __ } = wp.i18n;
import { Component } from '@wordpress/element';
import moment from 'moment';
import { delay } from '../utils';

import NoticeImage from './noticeImage';

// Notice class method.
export default class Notice extends Component {
	// the default props
	static defaultProps = {
		title: undefined,
		message: undefined,
		acceptMessage: __( 'Accept' ),
		acceptLink: '#',
		dismissLabel: __( 'dismiss' ),
		source: 'WordPress',
		dismissible: false,
		onDismiss: () => delay( 100 ).then( this.unmount() ),
	};

	render() {
		let classes = 'wp-notification wp-notice-' + this.props.id;
		classes += this.props.dismissible ? ' is-dismissible' : '';
		classes += this.props.severity ? ' ' + this.props.severity : null;
		classes += this.props.unread ? ' unread' : '';

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
						this.props.dismissible && (
							<a
								href={ this.props.acceptLink }
								className={ 'wp-notification-action' }
							>
								{ this.props.acceptMessage }
							</a>
						)
					) }
					<p className="wp-notification-source">
						<span className="name">{ this.props.source }</span>{ ' ' }
						{ '\u2022 ' }
						<span className="date">
							{ moment.unix( this.props.date ).fromNow() }
						</span>
					</p>
				</div>
				<NoticeImage
					type={ this.props.icon ? 'icon' : 'image' }
					severity={ this.props.severity }
					location={ this.props.location }
					image={ this.props.image }
					alt={
						( this.props.location || this.props.title ) + 'image'
					}
				/>
			</div>
		);
	}
}
