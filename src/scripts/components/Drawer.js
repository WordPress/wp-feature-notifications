import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useShortcut } from '@wordpress/keyboard-shortcuts';
import { Resizable } from 're-resizable';

import { HUB_WIDTH } from '../constants';

import { NoticesArea } from './NoticesArea';

/**
 * A resizable drawer React component for displaying notifications. The returned
 * `Drawer` component is an `aside` element containing a `Resizable` component and
 * `NoticesArea` component. The `Resizable` component provides UI controls to resize
 * the width of the `Drawer` by dragging its left edge. The `NoticesArea` component
 * displays notifications in the `Drawer`.
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
	const [ width, setWidth ] = useState( /** @type {number} */ ( HUB_WIDTH ) );

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
				onResizeStop={ ( _e, _direction, _ref, d ) => {
					setWidth( width + d.width );
				} }
				maxWidth={ '100vw' }
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
