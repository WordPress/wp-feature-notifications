import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { defaultContext } from '../store/constants';

/**
 * @typedef {Object} Action
 * @property {string=}  acceptLink    The url of the action.
 * @property {string=}  acceptMessage The label of the action.
 * @property {string=}  dismissLabel  The label of the dismiss action.
 * @property {boolean=} dismissible   Predicate of whether the notification can be dismissed.
 */

/**
 * Renders an image or icon based on the type of notification
 *
 * @param {Object}   param
 * @param {Action}   param.action
 * @param {string}   param.context
 * @param {Function} param.onDismiss - callback to be called when the notice is dismissed
 * @return {JSX.Element} NoticeImage - the image or the icon wrapped into a div
 */
export const NoticeActions = ( { action, context, onDismiss } ) => {
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
};
