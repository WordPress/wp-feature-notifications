/**
 * Single Notification UI component
 * https://github.com/WordPress/wp-feature-notifications/issues/16#issuecomment-896031592
 * https://github.com/WordPress/wp-feature-notifications/issues/37#issuecomment-896080025
 */

// Register components
import { NoticeIcon } from './NoticeImage';
import { NoticeActions } from './NoticeAction';

// Import utilities
// @ts-ignore
import classnames from 'classnames';
import { purify } from '../utils/sanitization';
import { defaultContext, NOTIFY_NAMESPACE } from '../store/constants';
import { dispatch } from '@wordpress/data';
import { NoticeMeta } from './NoticeMeta';

/**
 * @typedef {import('../store').Notice} Notice
 */

/**
 * It renders a single notice
 *
 * @param {Notice} props
 * @return {JSX.Element} Notice - the single notice
 */
export const Notice = ( props ) => {
	const {
		action,
		context = defaultContext,
		date = Date.now() * 0.001,
		dismissLabel,
		dismissible,
		icon,
		id,
		message,
		severity,
		source = 'WordPress',
		status,
		title,
		unread,
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
				dismissible ? 'dismissible' : null,
				severity ? severity : null,
				unread ? 'unread' : null,
				status
			) }
		>
			<div className="wp-notification-wrap">
				<h3 className="wp-notification-title">{ title }</h3>
				{ message ? (
					<p dangerouslySetInnerHTML={ purify( message ) }></p>
				) : null }
				<NoticeActions
					action={ { ...action, dismissLabel, dismissible } }
					onDismiss={ dismissNotice }
					context={ context }
				/>
				<NoticeMeta date={ date * 1000 } source={ source } />
			</div>

			<NoticeIcon
				icon={ icon }
				context={ context }
				severity={ severity }
			/>
		</div>
	);
};
