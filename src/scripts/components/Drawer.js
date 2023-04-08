import { __ } from '@wordpress/i18n';
import { NoticesArea } from './NoticesArea';

export const Drawer = ( { focus, blur } ) => {
	return (
		<aside id="notification-hub" onFocus={ focus } onBlur={ blur }>
			<div className={ 'hub-wrapper' }>
				<h2 className={ 'screen-reader-text' }>
					{ __( 'Notifications' ) }
				</h2>
				<NoticesArea context={ 'adminbar' } />
			</div>
		</aside>
	);
};
