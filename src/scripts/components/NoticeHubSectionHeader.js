import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';

import { clearNotificationsDrawer } from '../utils/';

/**
 * The section header for the notices section drawer.
 *
 * @param {Object}  props
 * @param {boolean} props.isMain
 * @param {number}  props.unreadCount
 * @param {string}  props.context
 * @return {JSX.Element} NoticeHubSectionHeader
 */
export const NoticeHubSectionHeader = ( { isMain, unreadCount, context } ) => {
	return isMain ? (
		<header>
			<h2>{ unreadCount } new notifications</h2>
			<Button
				className="wp-notifications-action mark-as-read button-link"
				onClick={ () => clearNotificationsDrawer( context ) }
				icon={ check }
				isSmall={ true }
				text={ __( 'Mark all as read' ) }
			/>
		</header>
	) : (
		<header>
			<h2>Older notifications</h2>
		</header>
	);
};
