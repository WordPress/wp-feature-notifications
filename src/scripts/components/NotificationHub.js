import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import {
	ShortcutProvider,
	store as keyboardShortcutsStore,
} from '@wordpress/keyboard-shortcuts';
import { useDispatch } from '@wordpress/data';
import Drawer from './Drawer';
import NotificationHubIcon from './NotificationHubIcon';
import classNames from 'classnames';

export default () => {
	/** Drawer state */
	const [ isActive, setIsActive ] = useState( false );

	/** Register the keyboard shortcut(s) */
	const { registerShortcut } = useDispatch( keyboardShortcutsStore );
	useEffect( () => {
		registerShortcut( {
			name: 'wp-feature-notifications/close-drawer',
			category: 'wp-feature-notifications',
			description: __( 'Close the Notification drawer' ),
			keyCombination: {
				character: 'Escape',
			},
		} );
	} );

	function toggleDrawer() {
		setIsActive( ! isActive );
	}

	return (
		<ShortcutProvider
			className={ classNames( [
				'notifications',
				isActive ? ' active' : '',
			] ) }
		>
			<NotificationHubIcon
				toggle={ toggleDrawer }
				isActive={ isActive }
			/>
			<Drawer />
		</ShortcutProvider>
	);
};
