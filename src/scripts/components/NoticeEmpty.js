import { comment, Icon } from '@wordpress/icons';

/**
 * @param {Object} props
 * @param {number} props.size    The size of the icon.
 * @param {string} props.message The message of the notification.
 */
export const NoticeEmpty = ( props ) => {
	return (
		<div
			style={ { padding: '20px', textAlign: 'center' } }
			className={ 'is-empty' }
		>
			<Icon icon={ comment } size={ props.size } />
			<p>{ props.message }</p>
		</div>
	);
};
