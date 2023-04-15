import { __ } from '@wordpress/i18n';
import { useEffect, useRef, useState } from '@wordpress/element';
import {
	ShortcutProvider,
	store as keyboardShortcutsStore,
} from '@wordpress/keyboard-shortcuts';
import { useDispatch } from '@wordpress/data';
import { Drawer } from './Drawer';
import { NotificationHubIcon } from './NotificationHubIcon';
import * as classNames from 'classnames';

/**
 * The notification hub component.
 *
 * @param {Object}   props               Properties
 * @param {boolean=} props.initialActive Optionally initially force the hub into an active state.
 */
export const NotificationHub = ( { initialActive = false } ) => {
	/** Drawer state */
	const [ isActive, setIsActive ] = useState( initialActive );
	const drawerRef = useRef( null );

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

	const toggleDrawer = () => {
		setIsActive( ( prev ) => ! prev );
	};

	const handleOutsideClick = ( event ) => {
		if (
			drawerRef.current &&
			! drawerRef.current.contains( event.target )
		) {
			setIsActive( false );
		}
	};

	useEffect( () => {
		if ( isActive ) {
			document.addEventListener( 'mousedown', handleOutsideClick );
			return () => {
				document.removeEventListener( 'mousedown', handleOutsideClick );
			};
		}
	}, [ isActive ] );

	return (
		<ShortcutProvider
			className={ classNames( [
				'notifications',
				isActive ? 'active' : '',
			] ) }
		>
			<NotificationHubIcon
				toggle={ toggleDrawer }
				isActive={ isActive }
			/>
			<Drawer
				instance={ drawerRef }
				focus={ () => setIsActive( true ) }
				blur={ () => setIsActive( false ) }
			/>
		</ShortcutProvider>
	);
};
