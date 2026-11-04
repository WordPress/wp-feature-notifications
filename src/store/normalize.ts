import type {
	DashiconsIcon,
	ImageIcon,
	Notice,
	NoticeAction,
	NoticeIcon,
	NoticeStatus,
	SvgIcon,
} from '../types';

/**
 * The shape of a notification as returned by the `wp-notifications/v1` REST API.
 *
 * Field names are snake_case, while the client `Notice` type is camelCase, so the
 * payload has to be normalised before it enters the store.
 */
export type RestNotification = {
	accept_label?: string;
	accept_link?: string;
	channel_name?: string;
	channel_title?: string;
	context?: string;
	created_at?: string | null;
	dismiss_label?: string;
	dismissed_at?: string | null;
	displayed_at?: string | null;
	expires_at?: string | null;
	icon?: string | { dashicons?: string; src?: string; svg?: string } | null;
	id: number;
	is_dismissible?: boolean;
	message?: string;
	severity?: string | null;
	status?: string;
	title?: string;
	user_id?: number;
};

/**
 * Normalise the many possible REST icon shapes to the `NoticeIcon` union.
 *
 * The REST schema exposes `icon` as a dashicon slug string, but this also accepts an
 * already-structured icon object so the mapper keeps working if the payload changes.
 *
 * @param icon The icon value from the REST payload.
 *
 * @return The normalised icon, or undefined when no usable icon was provided.
 */
const normalizeIcon = (
	icon: RestNotification[ 'icon' ]
): NoticeIcon | undefined => {
	if ( typeof icon === 'string' ) {
		return { dashicons: icon } as DashiconsIcon;
	}

	if ( icon && typeof icon === 'object' ) {
		if ( typeof icon.dashicons === 'string' ) {
			return { dashicons: icon.dashicons } as DashiconsIcon;
		}
		if ( typeof icon.src === 'string' ) {
			return { src: icon.src } as ImageIcon;
		}
		if ( typeof icon.svg === 'string' ) {
			return { svg: icon.svg } as SvgIcon;
		}
	}

	return undefined;
};

/**
 * Normalise the flat REST action fields into a `NoticeAction`.
 *
 * @param raw The raw REST notification.
 *
 * @return The normalised action, or undefined when the notification has no action.
 */
const normalizeAction = ( raw: RestNotification ): NoticeAction | undefined => {
	if ( ! raw.accept_link && ! raw.accept_label && ! raw.dismiss_label ) {
		return undefined;
	}

	return {
		acceptLink: raw.accept_link,
		acceptMessage: raw.accept_label,
		dismissLabel: raw.dismiss_label,
		dismissible: raw.is_dismissible,
	};
};

/**
 * Normalise a single notification from the REST payload to the store `Notice` shape.
 *
 * @param raw The raw REST notification.
 *
 * @return The normalised notice.
 */
export const normalizeNotice = ( raw: RestNotification ): Notice => {
	const notice: Notice = {
		id: raw.id,
		message: raw.message ?? '',
		date: raw.created_at ? new Date( raw.created_at ) : new Date(),
	};

	if ( raw.title !== undefined ) {
		notice.title = raw.title;
	}
	if ( raw.context !== undefined ) {
		notice.context = raw.context;
	}
	if ( raw.status !== undefined ) {
		notice.status = raw.status as NoticeStatus;
	}
	if ( raw.severity !== undefined && raw.severity !== null ) {
		notice.severity = raw.severity;
	}
	if ( raw.channel_title !== undefined ) {
		notice.source = raw.channel_title;
	}
	if ( raw.is_dismissible !== undefined ) {
		notice.dismissible = raw.is_dismissible;
	}
	if ( raw.dismiss_label !== undefined ) {
		notice.dismissLabel = raw.dismiss_label;
	}

	const icon = normalizeIcon( raw.icon );
	if ( icon ) {
		notice.icon = icon;
	}

	const action = normalizeAction( raw );
	if ( action ) {
		notice.action = action;
	}

	return notice;
};

/**
 * Normalise a list of notifications returned by the REST API.
 *
 * @param raw The raw REST payload.
 *
 * @return The normalised notices, or an empty array when nothing usable was received.
 */
export const normalizeNotices = (
	raw: RestNotification[] | null | undefined
): Notice[] => {
	return Array.isArray( raw ) ? raw.map( normalizeNotice ) : [];
};
