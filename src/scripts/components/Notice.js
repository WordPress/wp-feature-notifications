/**
 * Single Notification UI component
 * https://github.com/WordPress/wp-feature-notifications/issues/16#issuecomment-896031592
 * https://github.com/WordPress/wp-feature-notifications/issues/37#issuecomment-896080025
 */

// Register components
import { NoticeIcon } from './NoticeImage';
import { NoticeActions } from './NoticeAction';

// Import utilities
import classnames from 'classnames';
import { purify } from '../utils/sanitization';
import moment from 'moment';
import { __ } from '@wordpress/i18n';
import { defaultContext, NOTIFY_NAMESPACE } from '../store/constants';
import { delay } from '../utils/effects';
import { dispatch } from '@wordpress/data';

/**
 * It renders a single notice
 *
 * @param {Object} props
 * @return {JSX.Element} Notice - the single notice
 */
export const Notice = (props) => {
	const {
		id,
		title,
		status,
		context = defaultContext,
		source = 'WordPress',
		date = __('Just now'),
		message,
		severity,
		dismissible,
		unread,
	} = props;

	/**
	 * Dismiss the target notification
	 *
	 * @param  notifyID
	 */
	function dismissNotice() {
		dispatch(NOTIFY_NAMESPACE).updateNotice({
			id,
			status: 'dismissing',
		});
		delay(100).then(dispatch(NOTIFY_NAMESPACE).removeNotice(id));
	}

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
				<NoticeActions
					action={props?.action ?? {}}
					onDismiss={dismissNotice}
					context={context}
				/>
				<p className="wp-notification-source">
					<span className="name">{source}</span> {'\u2022 '}
					<span className="date">{moment.unix(date).fromNow()}</span>
				</p>
			</div>

			<NoticeIcon {...props} />
		</div>
	);
};
