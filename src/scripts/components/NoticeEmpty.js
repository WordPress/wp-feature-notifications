import { comment, Icon } from '@wordpress/icons';

/**
 * @param {Object} props
 * @param {number} props.size    The size of the icon.
 * @param {string} props.message The message of the notification.
 */
export const NoticeEmpty = ( props ) => {
	return (
		<section
			className={ 'is-empty' }
			style={ { padding: '20px', textAlign: 'center' } }
		>
			<Icon icon={ comment } size={ props.size } />
			<p>{ props.message }</p>
		</section>
	);
};
