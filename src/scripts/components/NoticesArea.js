import { comment, Icon } from '@wordpress/icons';
import { Button } from '@wordpress/components';
import { dispatch, useSelect } from '@wordpress/data';

import { NOTIFY_NAMESPACE } from '../store/constants';
import { delay } from '../utils/effects';

import { Notice } from './Notice';
import { clearNotifyDrawer } from '../utils/drawer';
import { updateNotice } from '../store/actions';

/**
 * WP-Notify toolbar in the secondary position of the admin bar
 * It watches for state updates and renders a <Notifications /> component with the updated state
 *
 * @param {Object} props
 * @return {JSX.Element} Notifications
 */
export const NoticesArea = (props) => {
	const { splitBy, context } = props;

	const notifications =
		useSelect(
			(select) => select(NOTIFY_NAMESPACE).getNotices(context),
			[]
		) || [];

	/**
	 * Sets the initial state of the component and binds the updateNoticeState function to the component
	 */
	//useEffect(() => {}, []);

	/**
	 * Updating the state of the notifications object.
	 *
	 * @param {number}           key
	 * @param {{status: string}} newProps
	 */
	const updateNoticeState = (key, newProps) => {
		return updateNotice({
			key: newProps,
		});
	};

	/**
	 * Returns a list of notices
	 * each notice is a component that has a key, an id, an image, an additional class name, and an onDismiss function
	 *
	 * @param {Array} notifyList - An array of objects that contain the notice data.
	 *
	 * @return {Array} An array of Notice components.
	 */
	const printNotices = (notifyList) => {
		return notifyList.map((notify, k) => (
			<Notice
				{...notify}
				key={k}
				id={k}
				onDismiss={() => {
					updateNoticeState(k, {
						status: 'dismissing',
					});
					delay(100).then(() =>
						dispatch('notifyStore').removeNotice(k)
					);
				}}
			/>
		));
	};

	/**
	 * It takes a list of notifications, sorts them into two lists,
	 * one for current notifications and one for past notifications,
	 * and then renders a list of notifications for each list
	 *
	 * @param {Array} notifyList
	 * @return {Array} A list of notifications split by current (the last 7 days) and past (before current)
	 */
	const noticesList = (notifyList) => {
		// TODO: Component for "Congratulations! You have read all the notifications" (an icon and with text/i18n)
		if (!notifyList.length)
			return (
				<div style={{ padding: '20px', textAlign: 'center' }}>
					<Icon icon={comment} size={96} />
					<p>empty</p>
				</div>
			);

		// TODO: i've faked the sorting option and whatever argument passed will render a list of notifications split by current (the last 7 days) and past (before current)
		const sortedNotifications = notifications.reduce(
			([current, past], item) => {
				return item.date >= Date.now() / 1000 - 3600 * 24 * 7
					? [[...current, item], past]
					: [current, [...past, item]];
			},
			[[], []]
		);

		return sortedNotifications.map((list, index) => (
			<section key={index}>
				{!index && list ? (
					<header>
						<h2>{list.length} unread notifications</h2>
						<Button
							id="clear-all-wp-notify-adminbar"
							className="wp-notification-action wp-notification-action-markread button-link"
							onClick={() =>
								clearNotifyDrawer(this.props.context)
							}
						>
							<span className="ab-icon dashicons-saved"></span>{' '}
							Mark all as read
						</Button>
					</header>
				) : (
					<header>
						<h2>Older notifications</h2>
					</header>
				)}
				{list ? printNotices(list) : null}
			</section>
		));
	};

	if (!notifications) return null;

	return splitBy
		? noticesList(notifications) || null
		: printNotices(notifications);
};
