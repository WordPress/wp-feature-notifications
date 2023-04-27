import { comment, Icon } from '@wordpress/icons';

/**
 * The `NoticeEmpty` props type.
 */
type Props = {
	message: string;
	size: number;
};

/**
 * @param props
 * @param props.message The message of the notification.
 * @param props.size    The size of the icon.
 */
export default function NoticeEmpty( { message, size }: Props ) {
	return (
		<section
			className={ 'is-empty' }
			style={ { padding: '20px', textAlign: 'center' } }
		>
			<Icon icon={ comment } size={ size } />
			<p>{ message }</p>
		</section>
	);
}
