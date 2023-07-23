import { formatDate } from '../../../utils';

/**
 * The `NoticeMeta` props type.
 */
type Props = {
	date: Date;
	source: string;
};

/**
 * The notice metadata, for example the source of the notification or the date
 *
 * @param props
 * @param props.date   The date of the notification.
 * @param props.source The source of the notification.
 */
export default function NoticeMeta( { date, source }: Props ) {
	return (
		<p className="wp-notification-meta">
			<span className="name">{ source }</span> { '\u2022 ' }
			<span className="date">{ formatDate( date ) }</span>
		</p>
	);
}
