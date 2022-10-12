/**
 * Single Notification UI component
 * https://github.com/WordPress/wp-feature-notifications/issues/16#issuecomment-896031592
 * https://github.com/WordPress/wp-feature-notifications/issues/37#issuecomment-896080025
 */

// Register components
import { Component } from '@wordpress/element';
import { NoticeImage, NoticeIcon } from './NoticeImage';
import NoticeActions from './NoticeAction';

// Import utilities
import PropTypes from 'prop-types';
import classnames from 'classnames';
import { purify } from '../utils/sanitization';
import moment from 'moment';

/**
 * It renders a single notice
 *
 * @return {JSX.Element} Notice - the single notice
 */
export default class Notice extends Component {
	static propTypes = {
		id: PropTypes.number,
		title: PropTypes.string || undefined,
		image: PropTypes.string || undefined,
		icon: PropTypes.string || undefined,
		iconBackgroundColor: PropTypes.string || undefined,
		status: PropTypes.string || undefined,
		source: PropTypes.string,
		date: PropTypes.number,
		message: PropTypes.string || undefined,
		acceptMessage: PropTypes.string,
		acceptLink: PropTypes.string,
		dismissLabel: PropTypes.string,
		severity: PropTypes.string,
		unread: PropTypes.bool,
		dismissible: PropTypes.bool,
		onDismiss: PropTypes.func,
	};

	/**
	 * Setting the default props for the component.
	 *
	 * @param {PropTypes} props
	 */
	constructor(props) {
		super(props);
		this.id = this.props.id;
		this.title = this.props.title || undefined;
		this.message = this.props.message || undefined;
		this.source = this.props.source || 'WordPress';
		this.location = this.props.location || 'adminbar';
		this.severity = this.props.severity;
		this.date = this.props.date || Date.now() / 1000;

		this.action = {
			acceptMessage: this.props.acceptMessage || undefined,
			acceptLink: this.props.acceptLink || '#',
			dismissLabel: this.props.dismissLabel || undefined,
			dismissible: this.props.dismissible || undefined,
			onDismiss: this.props.onDismiss,
		};

		this.figure = {
			image: this.props.image || undefined,
			icon: this.props.icon || undefined,
			iconBackgroundColor: this.props.iconBackgroundColor,
		};
	}

	render() {
		return (
			<div
				className={classnames(
					'wp-notification',
					'wp-notice-' + this.id,
					this.action.dismissible,
					this.severity || null,
					this.unread ? 'unread' : null,
					this.props.status
				)}
				tabIndex="0"
			>
				<div className="wp-notification-wrap">
					<h3 className="wp-notification-title">{this.title}</h3>
					<p dangerouslySetInnerHTML={purify(this.message)} />
					<NoticeActions
						location={this.location}
						action={this.action}
					/>
					<p className="wp-notification-source">
						<span className="name">{this.source}</span> {'\u2022 '}
						<span className="date">
							{moment.unix(this.date).fromNow()}
						</span>
					</p>
				</div>
				{this.figure.icon ? (
					<NoticeIcon
						location={this.location}
						severity={this.severity}
						color={this.figure.iconBackgroundColor}
						icon={this.figure.icon}
					/>
				) : (
					<NoticeImage
						location={this.location}
						image={this.figure.image}
					/>
				)}
			</div>
		);
	}
}
