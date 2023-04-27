import type { Notice } from '../../types';
import NoticeComponent from '../notice';

/**
 * The ` NoticeLoop` props type.
 */
type Props = {
	notices: Notice[];
};

/**
 * Returns a list of notices
 * each notice is a component that has a key, an id, an image, an additional class
 * name, and an onDismiss function
 *
 * @param prop
 * @param prop.notices An array of objects that contain the notification data.
 *
 * @return An array of Notice components.
 */
export default function NoticesLoop( { notices }: Props ) {
	return (
		<>
			{ notices.map( ( notice ) => (
				<NoticeComponent { ...notice } key={ notice.id } />
			) ) }
		</>
	);
}
