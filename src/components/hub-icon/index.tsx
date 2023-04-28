import { __ } from '@wordpress/i18n';
import { useShortcut } from '@wordpress/keyboard-shortcuts';
import classNames from 'classnames';

import UnreadDot from '../unread-dot';

/**
 * The HTML rendered by this component is the same as the variable `notification_hub_icon`
 * in`includes/load.php`. If the output of this component is modified that file must
 * also be updated.
 */

/**
 * The `HubIcon` props type.
 */
type Props = {
	classes?: string[];
	hasUnread?: boolean;
	isActive: boolean;
	toggle: Function;
};

/**
 * Notification icon UI component.
 *
 * @param props
 * @param props.toggle    Toggle the drawer on and off.
 * @param props.isActive  Predicate of whether the drawer is in an active state.
 * @param props.hasUnread Predicate of whether there are unread notifications.
 * @param props.classes   The icon class names (defaults to [ 'dashicons-bell' ])
 */
export default function HubIcon( {
	toggle,
	isActive,
	hasUnread = false,
	classes = [ 'dashicons-bell' ],
}: Props ) {
	/**
	 * Enables the shortcut to close the drawer with the escape key
	 */
	useShortcut( 'wp-feature-notifications/close-drawer', () => {
		if ( isActive ) toggle();
	} );

	return (
		<button
			className={ 'hub-icon' }
			aria-haspopup="menu"
			onClick={ () => toggle() }
		>
			<span
				className={ classNames( 'ab-icon', 'dashicons', ...classes ) }
				aria-hidden="true"
			></span>
			<span className="ab-label screen-reader-text">
				{ __( 'Notifications' ) }
			</span>
			<UnreadDot isActive={ hasUnread } />
		</button>
	);
}
