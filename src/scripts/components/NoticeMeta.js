import { dateI18n } from '@wordpress/date';

/**
 * The notice metadata, for example the source of the notification or the date
 *
 * @param {Object} props
 * @param {string} props.date   The date of the notification.
 * @param {string} props.source The source of the notification.
 */
export const NoticeMeta = ( { date, source } ) => (
	<p className="wp-notification-meta">
		<span className="name">{ source }</span> { '\u2022 ' }
		<span className="date">
			{ dateI18n( 'l jS F Y - h:i A', date, true ) }
		</span>
	</p>
);
