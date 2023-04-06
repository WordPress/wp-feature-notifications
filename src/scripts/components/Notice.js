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
import { defaultContext, NOTIFY_NAMESPACE } from '../store/constants';
import { dispatch } from '@wordpress/data';

export const NoticeMeta = ( { date, source } ) => (
	<p className="wp-notification-meta">
		<span className="name">{ source }</span> { '\u2022 ' }
		<span className="date">{ moment( date ).fromNow() }</span>
	</p>
);

/**
 * It renders a single notice
 *
 * @param {Object} props
 * @return {JSX.Element} Notice - the single notice
 */
export const Notice = ( props ) => {
	const {
		id,
		title,
		status,
		context = defaultContext,
		source = 'WordPress',
		date = Date.now() * 0.001,
		message,
		severity,
		action,
	} = props;

	/**
	 * Dismiss the target notification
	 */
	function dismissNotice() {
		dispatch( NOTIFY_NAMESPACE ).updateNotice( {
			id,
			status: 'dismissing',
		} );
		dispatch( NOTIFY_NAMESPACE ).removeNotice( id );
	}

	return (
		<div
			className={ classnames(
				'wp-notification',
				'wp-notice-' + id,
				action?.dismissible ? 'dismissible' : null,
				severity ? severity : null,
				action?.unread ? 'unread' : null,
				status
			) }
		>
			<div className="wp-notification-wrap">
				<h3 className="wp-notification-title">{ title }</h3>
				{ message ? (
					<p dangerouslySetInnerHTML={ purify( message ) }></p>
				) : null }
				<NoticeActions
					action={ action ?? {} }
					onDismiss={ dismissNotice }
					context={ context }
				/>
				<NoticeMeta date={ date * 1000 } source={ source } />
			</div>

			<NoticeIcon { ...props } />
		</div>
	);
};
