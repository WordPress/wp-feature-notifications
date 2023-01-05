/**
 * Single Notification UI component
 * https://github.com/WordPress/wp-feature-notifications/issues/16#issuecomment-896031592
 * https://github.com/WordPress/wp-feature-notifications/issues/37#issuecomment-896080025
 */

// Register components
import { NoticeImage, NoticeIcon } from './NoticeImage';
import { NoticeActions } from './NoticeAction';

// Import utilities
import classnames from 'classnames';
import { purify } from '../utils/sanitization';
import moment from 'moment';

/**
 * It renders a single notice
 *
 * @param  props
 * @return {JSX.Element} Notice - the single notice
 */
export const Notice = (props) => {
	const {
		id,
		context,
		title,
		image,
		icon,
		figure,
		action,
		iconBackgroundColor,
		status,
		source,
		date,
		message,
		acceptMessage,
		acceptLink,
		dismissLabel,
		severity,
		unread,
		dismissible = false,
		onDismiss,
	} = props;

	return (
		<div
			className={classnames(
				'wp-notification',
				'wp-notice-' + id,
				dismissible,
				severity || null,
				unread ? 'unread' : null,
				status
			)}
			tabIndex="0"
		>
			<div className="wp-notification-wrap">
				<h3 className="wp-notification-title">{title}</h3>
				<p dangerouslySetInnerHTML={purify(message)} />
				<NoticeActions {...props} />
				<p className="wp-notification-source">
					<span className="name">{source}</span> {'\u2022 '}
					<span className="date">{moment.unix(date).fromNow()}</span>
				</p>
			</div>
			{icon ? (
				<NoticeIcon
					context={context}
					severity={severity}
					color={iconBackgroundColor}
					icon={icon}
				/>
			) : (
				<NoticeImage context={context} image={image} />
			)}
		</div>
	);
};
