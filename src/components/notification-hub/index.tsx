import { useDispatch } from '@wordpress/data';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
	ShortcutProvider,
	store as keyboardShortcutsStore,
} from '@wordpress/keyboard-shortcuts';
import classNames from 'classnames';

import Drawer from '../drawer';

import HubIcon from './icon';

/**
 * The HTML rendered by this component is the same as the variable `notification_hub`
 * in`includes/load.php`. If the output of this component is modified that file must
 * also be updated.
 */

/**
 * The `NotificationHub` props type.
 */
type Props = {
	initialActive?: boolean;
};

/**
 * The notification hub component.
 *
 * @param props
 * @param props.initialActive Optionally initially force the hub into an active state.
 */
export default function NotificationHub( { initialActive = false }: Props ) {
	/** Drawer state */
	const [ isActive, setIsActive ] = useState( initialActive );
	const drawerRef = useRef< HTMLElement | null >( null );

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

	const handleOutsideClick = ( event: MouseEvent ) => {
		if (
			drawerRef.current &&
			! drawerRef.current.contains( event.target as Node )
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
				<HubIcon toggle={ toggleDrawer } isActive={ isActive } />
				<Drawer
					drawRef={ drawerRef }
					focus={ () => setIsActive( true ) }
					blur={ () => setIsActive( false ) }
				/>
			</div>
		</ShortcutProvider>
	);
}
