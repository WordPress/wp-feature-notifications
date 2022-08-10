/**
 * Single Notification UI component
 * https://github.com/WordPress/wp-feature-notifications/issues/16#issuecomment-896031592
 * https://github.com/WordPress/wp-feature-notifications/issues/37#issuecomment-896080025
 */
import { __ } from '@wordpress/i18n';

// Register components
import { Component } from '@wordpress/element';
import { Button } from '@wordpress/components';
import NoticeImage from './noticeImage';

import moment from 'moment';
import { delay } from '../utils';

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
		onDismiss: () => async () => {
			delay( 100 ).then( this.unmount() );
		},
	};

	render() {
		const classes = [
			'wp-notification',
			'wp-notice-' + this.props.id,
			this.props.dismissible ? 'is-dismissible' : null,
			this.props.severity || null,
			this.props.unread ? 'unread' : null,
			this.props.additionalClassName,
		].filter( Boolean );

		return (
			<div className={ classes.join( ' ' ) } tabIndex="0">
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
							<Button
								variant="primary"
								className="button button-primary wp-notification-hub-trigger"
								onClick={ () =>
									( window.location.href =
										this.props.acceptLink )
								}
							>
								{ this.props.acceptMessage }
							</Button>
							{ this.props.dismissible && (
								<Button
									variant="link"
									className={
										'button button-link wp-notification-hub-dismiss'
									}
									onClick={ this.props.onDismiss }
									icon={ 'no-alt' }
								>
									{ this.props.dismissLabel }
								</Button>
							) }
						</div>
					) : (
						this.props.dismissible && (
							<Button
								variant="link"
								onClick={ () =>
									( window.location.href =
										this.props.acceptLink )
								}
								className={ 'wp-notification-action' }
							>
								{ this.props.acceptMessage }
							</Button>
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
