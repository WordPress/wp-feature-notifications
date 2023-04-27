import { __ } from '@wordpress/i18n';
import { useShortcut } from '@wordpress/keyboard-shortcuts';
import classNames from 'classnames';

/**
 * The HTML rendered by this component is the same as the variable `notification_hub_icon`
 * in`includes/load.php`. If the output of this component is modified that file must
 * also be updated.
 */

/**
 * Notification icon UI component
 *
 * @param {Object}    Props
 * @param {Function}  Props.toggle    Toggle the drawer on and off.
 * @param {boolean}   Props.isActive  Predicate of whether the drawer is in an active state.
 * @param {boolean=}  Props.hasUnread Predicate of whether there are unread notifications.
 * @param {string[]=} Props.classes   The icon class names (defaults to [ 'dashicons-bell' ])
 * @return {JSX.Element} - the Notification icon
 */
export const NotificationHubIcon = ( {
	toggle,
	isActive,
	hasUnread = false,
	classes = [ 'dashicons-bell' ],
} ) => {
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
			<span
				className={ classNames(
					'unread-dot',
					hasUnread ? 'has-unread' : ''
				) }
			></span>
		</button>
	);
};
