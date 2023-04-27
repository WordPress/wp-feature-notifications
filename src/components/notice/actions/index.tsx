import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import { defaultContext } from '../../../store/constants';
import type { NoticeAction } from '../../../types';

/**
 * The `NoticeActions` props type.
 */
type Props = {
	action: NoticeAction;
	context: string;
	onDismiss: Function;
};

/**
 * Renders an image or icon based on the type of notification
 *
 * @param param
 * @param param.action    The action properties.
 * @param param.context   The notification context.
 * @param param.onDismiss The callback to be called when the notice is dismissed
 */
export default function NoticeActions( { action, context, onDismiss }: Props ) {
	const {
		acceptLink = '#',
		acceptMessage = __( 'Accept' ),
		dismissLabel = __( 'Dismiss' ),
		dismissible = false,
	} = action;

	if ( context === defaultContext ) {
		return (
			<div className="wp-notification-actions">
				<Button
					variant={ 'link' }
					// TODO: at the moment it is only possible to use a link but it would not be complex to extend this functionality
					onClick={ () => ( window.location.href = acceptLink ) }
					className={ 'wp-notification-action' }
				>
					{ acceptMessage }
				</Button>
				{ dismissible ? (
					<Button
						variant={ 'link' }
						onClick={ () => onDismiss() }
						className={ 'wp-notification-action' }
					>
						{ dismissLabel }
					</Button>
				) : null }
			</div>
		);
	}

	return (
		<div className="wp-notification-actions">
			<Button
				variant={ 'primary' }
				className={ 'button-primary wp-notification-action' }
				onClick={ () => ( window.location.href = acceptLink ) }
			>
				{ acceptMessage }
			</Button>
			{ dismissible ? (
				<Button
					variant={ 'tertiary' }
					className={ 'button-tertiary wp-notification-action' }
					onClick={ () => onDismiss() }
					icon={ 'no-alt' }
				>
					{ dismissLabel }
				</Button>
			) : null }
		</div>
	);
}
