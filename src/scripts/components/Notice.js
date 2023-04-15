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
import { STORE_NAMESPACE } from '../constants';
import { purify } from '../utils/sanitization';
import { defaultContext } from '../store/constants';
import { dispatch } from '@wordpress/data';
import { NoticeMeta } from './NoticeMeta';
import { delay } from '../utils';

/**
 * @typedef {import('../store').Notice} Notice
 */

/**
 * This is a functional component in JavaScript that defines the UI for a single notification.
 * It takes in a set of props, destructures them, and uses them to render the notification with the appropriate title, message, icon, and actions.
 * It also includes a function to dismiss the notification when the user clicks on the dismiss button.
 *
 * @param {Notice} props
 * @return {JSX.Element} Notice The single notice.
 */
export const Notice = ( props ) => {
	const {
		action,
		context = defaultContext,
		date = new Date(),
		dismissLabel,
		dismissible,
		icon,
		id,
		message,
		severity,
		source = 'WordPress',
		status,
		title,
	} = props;

	/**
	 * Dismiss the target notification
	 */
	function dismissNotice() {
		dispatch( STORE_NAMESPACE ).updateNotice( {
			id,
			status: 'dismissed',
		} );
		// TODO missing exit animation
		delay( 500 ).then( () =>
			dispatch( STORE_NAMESPACE ).removeNotice( id )
		);
	}

	return (
		<div
			className={ classnames(
				'wp-notification',
				'wp-notice-' + id,
				dismissible ? 'dismissible' : null,
				severity ? severity : null,
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
				<NoticeMeta date={ date } source={ source } />
			</div>

			<NoticeIcon
				icon={ icon }
				context={ context }
				severity={ severity }
			/>
		</div>
	);
};
