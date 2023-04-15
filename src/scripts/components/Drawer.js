import { __ } from '@wordpress/i18n';
import { NoticesArea } from './NoticesArea';
import { Resizable } from 're-resizable';
import { useState } from '@wordpress/element';
import { useShortcut } from '@wordpress/keyboard-shortcuts';
import { HUB_WIDTH } from '../store/constants';

/**
 * This is a React component that renders a resizable drawer for displaying notifications.
 * The `Drawer` component is being returned, which is an `aside` element containing a `Resizable` component and a `NoticesArea` component.
 * The `Resizable` component allows the user to resize the width of the `Drawer` by dragging its left edge.
 * The `NoticesArea` component displays notifications in the `Drawer`.
 *
 * @param {Object}               props
 * @param {(event: any) => void} props.focus    The `onFocus` event listener for the drawer.
 * @param {(event: any) => void} props.blur     The `onBlur` event listener for the drawer.
 * @param {Object}               props.instance The `Resizable` instance.
 */
export const Drawer = ( { focus, blur, instance } ) => {
	/**
	 * Enables the shortcut to close the drawer with the escape key
	 */
	useShortcut( 'wp-feature-notifications/close-drawer', () => blur );
	const [ width, setWidth ] = useState( HUB_WIDTH );

	return (
		<aside
			id="wp-notifications-hub"
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
				minWidth={ HUB_WIDTH }
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
