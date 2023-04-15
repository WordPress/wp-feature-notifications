import { __ } from '@wordpress/i18n';
import { NoticesArea } from './NoticesArea';
import { Resizable } from 're-resizable';
import { useState } from '@wordpress/element';

export const Drawer = ( { focus, blur, instance } ) => {
	const [ width, setWidth ] = useState( 320 );
	return (
		<aside
			id="notifications-hub"
			onFocus={ focus }
			onBlur={ blur }
			ref={ instance }
		>
			<Resizable
				size={ { width, height: '100%' } }
				enable={ { left: true } }
				onResizeStop={ ( e, direction, ref, d ) => {
					setWidth( width + d.width );
				} }
				minWidth={ 320 }
			>
				<div className={ 'hub-wrapper' }>
					<h2 className={ 'screen-reader-text' }>
						{ __( 'Notifications' ) }
					</h2>
					<NoticesArea context={ 'adminbar' } />
				</div>
			</Resizable>
		</aside>
	);
};
