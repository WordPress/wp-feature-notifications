import wpLogo from '../../images/WordPressLogo.svg';
import classNames from 'classnames';

/**
 * Renders an image or icon based on the type of notification
 *
 * @param {Object} props
 * @return {JSX} NoticeImage - the image or the icon wrapped into a div
 */
export function NoticeImage(props) {
	const image =
		props.image || (props.location === 'adminbar' && wpLogo) || null;

	return (
		<div
			className={classNames(
				'wp-notification-image',
				'wp-notification-' + props.location
			)}
		>
			<img src={image} alt={''} />
		</div>
	);
}

/**
 * It returns a div with a class name of `wp-notification-image` and `wp-notification-` plus the type of notification,
 * and a background color of the color passed in. Inside the div is a span with a class name of the icon passed in
 * The background color is set to props.color or undefined.
 * The div contains a span with a className of props.icon.
 *
 * @param {Object} props - The props object is a JavaScript object that contains all the properties that were passed to the component.
 * @return {JSX.Element} A div with a className of 'wp-notification-image' and 'wp-notification-' + props.type.
 */
export function NoticeIcon(props) {
	return (
		<div
			className={classNames(
				'wp-notification-image',
				'wp-notification-icon',
				'wp-notification-' + props.location
			)}
			style={{ background: props.color || undefined }}
		>
			<span className={props.icon}></span>
		</div>
	);
}
