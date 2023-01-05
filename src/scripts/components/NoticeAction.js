import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Renders an image or icon based on the type of notification
 *
 * @param {Object} props
 * @return {JSX.Element} NoticeImage - the image or the icon wrapped into a div
 */
const NoticeActions = (props) => {
	const {
		context,
		dismissible,
		acceptLink,
		acceptMessage,
		onDismiss,
		dismissLabel,
	} = props;

	if (context !== 'dashboard') {
		return acceptMessage ? (
			<Button
				variant="link"
				// TODO: at the moment it is only possible to use a link but it would not be complex to extend this functionality
				onClick={() => (window.location.href = acceptLink)}
				className={'wp-notification-action'}
			>
				{acceptMessage}
			</Button>
		) : null;
	}

	return (
		<div className="wp-notification-actions-meta">
			<Button
				variant="primary"
				className="button button-primary wp-notification-hub-trigger"
				onClick={() => (window.location.href = acceptLink)}
			>
				{acceptMessage || __('Accept')}
			</Button>
			{dismissible && (
				<Button
					variant="link"
					className={'button button-link wp-notification-hub-dismiss'}
					onClick={onDismiss}
					icon={'no-alt'}
				>
					{dismissLabel || __('Dismiss')}
				</Button>
			)}
		</div>
	);
}

export { NoticeActions }
