import { Button } from '@wordpress/components';
import { clearNotifyDrawer } from '../utils/drawer';

/**
 * The section header for the notices section drawer.
 *
 * @param  isMain.isMain
 * @param  isMain
 * @param  unreadCount
 * @param  location
 *
 * @param  isMain.unreadCount
 * @param  isMain.location
 * @return {JSX.Element}
 * @function Object() { [native code] }
 */
export const NoticeSectionHeader = ({ isMain, unreadCount, location }) => {
	return isMain ? (
		<header>
			<h2>{unreadCount} unread notifications</h2>
			<Button
				id="clear-all-wp-notify-adminbar"
				className="wp-notification-action wp-notification-action-markread button-link"
				onClick={() => clearNotifyDrawer(location)}
			>
				<span className="ab-icon dashicons-saved"></span> Mark all as
				read
			</Button>
		</header>
	) : (
		<header>
			<h2>Older notifications</h2>
		</header>
	);
};
