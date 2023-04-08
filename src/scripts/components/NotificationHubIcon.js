import { __ } from '@wordpress/i18n';
import { useShortcut } from '@wordpress/keyboard-shortcuts';

export const NotificationHubIcon = ( { toggle, isActive } ) => {
	useShortcut( 'wp-feature-notifications/close-drawer', () => {
		if ( isActive ) toggle();
	} );

	return (
		<button className={ 'ab-item' } aria-haspopup="menu" onClick={ toggle }>
			<span className={ 'ab-icon' } aria-hidden="true"></span>
			<span className={ 'ab-label' }>{ __( 'Notifications' ) }</span>
		</button>
	);
};
