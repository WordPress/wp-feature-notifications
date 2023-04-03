import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { defaultContext } from '../store/constants';

/**
 * Renders an image or icon based on the type of notification
 *
 * @param {Object}  param
 * @param {Object}  param.action
 * @param {string}  param.context
 * @param {string}  param.onDismiss   - callback to be called when the notice is dismissed
 * @param {boolean} param.dismissible - whether the notice is dismissible or not
 * @return {JSX.Element} NoticeImage - the image or the icon wrapped into a div
 */
const NoticeActions = ( {
	action,
	context,
	dismissible = false,
	onDismiss,
} ) => {
	const {
		acceptLink = '#',
		acceptMessage = __( 'Accept' ),
		dismissLabel = __( 'Dismiss' ),
	} = action;

	if ( context === defaultContext ) {
		return acceptMessage ? (
			<Button
				variant={ 'link' }
				// TODO: at the moment it is only possible to use a link but it would not be complex to extend this functionality
				onClick={ () => ( window.location.href = acceptLink ) }
				className={ 'wp-notification-action' }
			>
				{ acceptMessage }
			</Button>
		) : null;
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
			{ dismissible && (
				<Button
					variant={ 'tertiary' }
					className={ 'button-tertiary wp-notification-action' }
					onClick={ () => onDismiss() }
					icon={ 'no-alt' }
				>
					{ dismissLabel }
				</Button>
			) }
		</div>
	);
};

export { NoticeActions };
