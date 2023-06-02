import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import store from '../store';
import { defaultContext } from '../store/constants';
import { splitByDate } from '../utils/';

import { NoticeEmpty } from './NoticeEmpty';
import { NoticeHubFooter } from './NoticeHubFooter';
import { NoticeHubSectionHeader } from './NoticeHubSectionHeader';
import { NoticesLoop } from './NoticesLoop';

export const WEEK_IN_SECONDS = 3600 * 24 * 7;

/**
 * @typedef {import('../store').Notice} Notice
 * @typedef {import('../store').NoticeStore} NoticeStore
 * @typedef {import('../utils/index').splitByDate} sortedByDate
 */

/**
 * @typedef {Object} Props
 * @property {string=}   context       Optional notices context to render.
 * @property {Notice[]=} notifications The collection of notices to render.
 */

/**
 * WP Notification Feature toolbar in the secondary position of the admin bar
 * It watches for state updates and renders a <Notifications /> component with the updated state
 *
 * @param {Props} props
 * @return {JSX.Element} Notifications
 */
export const NoticesArea = ( props ) => {
	let { notifications, context = defaultContext } = props;

	/*
	 * Todo: this method should supply to rest api the user data, current page, moreover the request args may be added (notice per page, notice filters and sort)
	 */
	notifications = useSelect(
		( select ) => select( store ).getNotices( context ),
		[]
	);

	/**
	 * if the context is the adminbar we need to render a list of notifications with the recent notifications and the old notifications
	 */
	if ( context === defaultContext ) {
		/** Returns the empty notice banner whenever the number of notices is 0 */
		if ( ! notifications?.length ) {
			return <NoticeEmpty size={ 96 } message={ __( 'empty' ) } />;
		}

		/** split the notifications by date */
		const sorted = splitByDate( notifications );

		return (
			<>
				{ sorted.map( ( list, index ) => (
					<section
						key={ index }
						className={
							'wp-notifications-hub-section section-' + index
						}
					>
						<NoticeHubSectionHeader
							context={ context }
							unreadCount={
								notifications?.filter(
									( notice ) => notice.status === 'new'
								).length || 0
							}
							isMain={ index === 0 } // the main section is the first one
						/>
						<NoticesLoop notices={ list } />
					</section>
				) ) }
				<NoticeHubFooter />
			</>
		);
	}

	/**
	 * if the context is NOT the adminbar we need to render a simple list of notifications
	 */
	return <NoticesLoop notices={ notifications } />;
};
