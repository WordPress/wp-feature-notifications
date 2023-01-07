import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';
import { clearNotifyDrawer } from '../utils/drawer';

/**
 * The section header for the notices section drawer.
 *
 * @param  props
 * @param  props.isMain
 *
 * @param  props.unreadCount
 * @param  props.location
 * @param  props.context
 * @return {JSX.Element}
 * @function Object() { [native code] }
 */
export const NoticeSectionHeader = ({ isMain, unreadCount, context }) => {
	return isMain ? (
		<header>
			<h2>{unreadCount} unread notifications</h2>
			<Button
				id="clear-all-wp-notify-adminbar"
				className="wp-notification-action wp-notification-action-markread button-link"
				onClick={() => clearNotifyDrawer(context)}
				icon={check}
				isSmall={true}
				text={__('Mark all as read')}
			/>
		</header>
	) : (
		<header>
			<h2>Older notifications</h2>
		</header>
	);
};
