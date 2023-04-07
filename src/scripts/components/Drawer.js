import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

export const wpNotifyDrawer = () => {
	return (
		<div className={ 'wp-notification-hub-wrapper' }>
			<h2 className={ 'screen-reader-text' }>
				{ __( 'Notifications' ) }
			</h2>
			<div id={ 'wp-notify-adminbar' } />
		</div>
	);
};

export const wpNotifyHubIcon = () => {
	return (
		<div className={ 'ab-item ab-empty-item' } tabIndex="0">
			<span className={ 'ab-icon' } aria-hidden="true"></span>
			<span className={ 'ab-label' }>{ __( 'Notifications' ) }</span>
		</div>
	);
};

export default () => {
	const [ isActive, setIsActive ] = useState( false );

	const toggleDrawer = () => {
		setIsActive( ! isActive );
	};

	const handleIconKeyDown = ( ev ) => {
		if ( ( 'key' in ev && ev.key === 'Enter' ) || ev.key === ' ' ) {
			// Activate item on "Enter" or "Space" key press
			setIsActive( true );
		}
	};

	const handleDrawerKeyDown = ( ev ) => {
		if ( 'key' in ev && ( ev.key === 'Escape' || ev.key === 'Esc' ) ) {
			// Close the drawer on "Escape" or "Esc" key press
			setIsActive( false );
		}
	};

	return (
		<>
			<wpNotifyHubIcon
				onClick={ ( event ) => toggleDrawer( event ) }
				onKeyDown={ ( event ) => handleIconKeyDown( event ) }
			/>
			<aside id="wp-notify-hub" className={ isActive ? 'active' : null }>
				<wpNotifyDrawer
					onKeyDown={ ( event ) => handleDrawerKeyDown( event ) }
				/>
			</aside>
		</>
	);
};
