import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { check } from '@wordpress/icons';

import { clearNotificationsDrawer } from '../../../utils';

/**
 * The `NoticeAreaSectionHeader` props type.
 */
type Props = {
	isMain: boolean;
	unreadCount: number;
	context: string;
};

/**
 * The section header for the notices section drawer.
 *
 * @param props
 * @param props.isMain
 * @param props.unreadCount
 * @param props.context
 */
export default function NoticeAreaSectionHeader( {
	isMain,
	unreadCount,
	context,
}: Props ) {
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
}
