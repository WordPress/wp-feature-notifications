// @ts-ignore
import wpLogo from '../../images/WordPressLogo.svg';
import classNames from 'classnames';
import { defaultContext } from '../store/constants';
import { purify } from '../utils/sanitization';
import { isDashiconsIcon, isSvgIcon } from '../utils/guards';

/**
 * @typedef {import('../store').NoticeIcon} NoticeIcon
 */

/**
 * It returns a div with a class name of `wp-notification-image` and `wp-notification-` plus the type of image passed in
 * an image or a svg element depending on the type of image passed in
 *
 * @param {Object}     props          - The props object is a JavaScript object that contains all the properties that were passed to the component.
 * @param {NoticeIcon} props.icon     - the Object containing the notification icon properties
 * @param {string}     props.context  - the location of the notification
 * @param {string=}    props.severity
 * @return {JSX.Element} A div with a className of 'wp-notification-image' and 'wp-notification-' + props.type.
 */
export const NoticeIcon = ( { icon, severity, context = defaultContext } ) => {
	/** build the notice container css classes definition */
	const classes = classNames(
		'wp-notification-image',
		! isDashiconsIcon( icon ) || 'wp-notification-icon',
		'wp-notification-' + ( context || 'adminbar' )
	);

	let image;

	// TODO: maybe is better to have a default definition like {type: "svg"} ?
	if ( isSvgIcon( icon ) ) {
		/** Since we don't want to double wrap svg's we need to return the div immediately */
		return (
			<div
				className={ classes }
				dangerouslySetInnerHTML={ purify( icon?.svg ) }
			/>
		);
	} else if ( isDashiconsIcon( icon ) ) {
		image = (
			<span
				className={ classNames(
					! severity || 'severity-' + severity,
					'ab-icon',
					'dashicons-' + icon.dashicons
				) }
			></span>
		);
	} else {
		image = (
			<figure>
				<img src={ icon?.src || wpLogo } alt={ '' } />
			</figure>
		);
	}

	/** and then finally we can return the image / icon wrapped into a container */
	return <div className={ classes }>{ image }</div>;
};
