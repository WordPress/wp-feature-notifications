// @ts-ignore
import * as classNames from 'classnames';

import { defaultContext } from '../../../store/constants';
import type { NoticeIcon } from '../../../types';
import { isDashiconsIcon, isImageIcon, isSvgIcon } from '../../../utils/guards';
import { purify } from '../../../utils/sanitization';

/**
 * The `NoticeIcon` props type.
 */
type Props = {
	context?: string;
	icon: NoticeIcon;
	severity?: string;
};

/**
 * It returns a div with a class name of `wp-notification-image` and `wp-notification-`
 * plus the type of image passed in an image or a svg element depending on the type
 * of image passed in.
 *
 * @param props
 * @param props.context  The display context of the notification.
 * @param props.icon     The icon of the notification.
 * @param props.severity The severity of the notification.
 */
export default function NoticeIcon( {
	context = defaultContext,
	icon,
	severity,
}: Props ) {
	/** build the notice container css classes definition */
	const classes = classNames(
		'wp-notification-image',
		! isDashiconsIcon( icon ) || 'wp-notification-icon',
		'wp-notification-' + ( context || 'adminbar' )
	);

	let image: JSX.Element;

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
	} else if ( isImageIcon( icon ) ) {
		image = (
			<figure>
				<img src={ icon.src } alt={ '' } />
			</figure>
		);
	} else {
		return null;
	}

	/** and then finally we can return the image / icon wrapped into a container */
	return <div className={ classes }>{ image }</div>;
}
