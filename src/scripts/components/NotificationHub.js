import { useDispatch } from '@wordpress/data';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
	ShortcutProvider,
	store as keyboardShortcutsStore,
} from '@wordpress/keyboard-shortcuts';
import * as classNames from 'classnames';

import { Drawer } from './Drawer';
import { NotificationHubIcon } from './NotificationHubIcon';

/**
 * The HTML rendered by this component is the same as the variable `notification_hub`
 * in`includes/load.php`. If the output of this component is modified that file must
 * also be updated.
 */

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
		<ShortcutProvider className="ab-item ab-empty-item" tabIndex={ 0 }>
			<div
				className={ classNames( [
					'notifications',
					isActive ? 'active' : '',
				] ) }
			>
				<NotificationHubIcon
					toggle={ toggleDrawer }
					isActive={ isActive }
					hasUnread={ true }
				/>
				<Drawer
					instance={ drawerRef }
					focus={ () => setIsActive( true ) }
					blur={ () => setIsActive( false ) }
				/>
			</div>
		</ShortcutProvider>
	);
};
