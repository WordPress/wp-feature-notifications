/**
 * The notice action type
 */
export type NoticeAction = {
	/**
	 * The url of the action.
	 */
	acceptLink?: string;

	/**
	 * The label of the action.
	 */
	acceptMessage?: string;

	/**
	 * The label of the dismiss action.
	 */
	dismissLabel?: string;

	/**
	 *  Predicate of whether the notification can be dismissed.
	 */
	dismissible?: boolean;
};

/**
 * The Dashicons icon type.
 */
export type DashiconsIcon = {
	/**
	 * The Dashicons slug of the icon.
	 */
	dashicons: string;
};

/**
 * The image icon type.
 */
export type ImageIcon = {
	/**
	 * The url of the image icon.
	 */
	src: string;
};

/**
 * The SVG icon type.
 */
export type SvgIcon = {
	/**
	 * The SVG markup of the icon.
	 */
	svg: string;
};

/**
 * The notification icon type.
 */
export type NoticeIcon = DashiconsIcon | ImageIcon | SvgIcon;

/**
 * The notification status type.
 */
export type NoticeStatus = 'undisplayed' | 'displayed' | 'dismissed' | 'new';

/**
 * The notification type.
 */
export type Notice = {
	/**
	 * The optional action associated to the notification.
	 */
	action?: NoticeAction;

	/**
	 * The rendering context of the notification.
	 */
	context?: string;

	/**
	 * The date from which the notification was emitted.
	 */
	date: Date;

	/**
	 * The label of the dismiss action.
	 */
	dismissLabel?: string;

	/**
	 * Predicate of whether the notification can be dismissed.
	 */
	dismissible?: boolean;

	/**
	 * The notification status type.
	 */
	icon?: NoticeIcon;

	/**
	 * The database id of the notification message.
	 */
	id: number;

	/**
	 * The message content of the notification.
	 */
	message: string;

	/**
	 * The severity of the notification.
	 */
	severity?: string;

	/**
	 * The source of the notification.
	 */
	source?: string;

	/**
	 * The status of the notification.
	 */
	status?: NoticeStatus;

	/**
	 * The title of the notification message.
	 */
	title?: string;
};
