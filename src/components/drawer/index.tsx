import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useShortcut } from '@wordpress/keyboard-shortcuts';
import { Resizable } from 're-resizable';
import type { FocusEventHandler, MutableRefObject } from 'react';

import { HUB_WIDTH } from '../../constants';
import NoticesArea from '../notice-area';

/**
 * The `Drawer` props type.
 */
type Props = {
	blur: FocusEventHandler< HTMLElement >;
	focus: FocusEventHandler< HTMLElement >;
	drawRef: MutableRefObject< HTMLElement >;
};

/**
 * A resizable drawer React component for displaying notifications. The returned
 * `Drawer` component is an `aside` element containing a `Resizable` component and
 * `NoticesArea` component. The `Resizable` component provides UI controls to resize
 * the width of the `Drawer` by dragging its left edge. The `NoticesArea` component
 * displays notifications in the `Drawer`.
 *
 * @param props
 * @param props.focus   The `onFocus` event listener for the drawer.
 * @param props.blur    The `onBlur` event listener for the drawer.
 * @param props.drawRef The reference to the drawer element.
 */
export default function Drawer( { focus, blur, drawRef }: Props ) {
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
			ref={ drawRef }
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
}
