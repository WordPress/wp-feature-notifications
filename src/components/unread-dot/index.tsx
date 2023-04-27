import classNames from 'classnames';

/**
 * The `UnreadDot` props type.
 */
type Props = {
	isActive: boolean;
};

/**
 * Notification icon unread dot UI component.
 *
 * @param props
 * @param props.isActive Predicate of whether the unread dot is in an
 *                       active state.
 */
export default function UnreadDot( { isActive }: Props ) {
	return (
		<span
			className={ classNames( [
				'unread-dot',
				isActive ? 'has-unread' : '',
			] ) }
		></span>
	);
}
