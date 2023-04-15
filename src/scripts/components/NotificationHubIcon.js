import { __ } from '@wordpress/i18n';
import { useShortcut } from '@wordpress/keyboard-shortcuts';
import classNames from 'classnames';

/**
 * Notification icon UI component
 *
 * @param {Object}    Props
 * @param {Function}  Props.toggle   - Toggle the drawer on and off
 * @param {boolean}   Props.isActive - the drawer active state
 * @param {string[]=} Props.classes  - the icon class name (defaults to [ 'dashicons-bell' ])
 * @return {JSX.Element} - the Notification icon
 */
export const NotificationHubIcon = ( {
	toggle,
	isActive,
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
			className={ 'ab-item' }
			aria-haspopup="menu"
			onClick={ () => toggle() }
		>
			<span
				className={ classNames( 'ab-icon', 'dashicons', ...classes ) }
				aria-hidden="true"
			></span>
			<span className={ 'ab-label' }>{ __( 'Notifications' ) }</span>
		</button>
	);
};
