import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import { defaultContext } from '../store/constants';
import { NoticeEmpty } from './NoticeEmpty';
import { NoticeHubSectionHeader } from './NoticeHubSectionHeader';
import { NoticesLoop } from './NoticesLoop';
import { getSorted } from '../utils/';
import { NoticeHubFooter } from './NoticeHubFooter';
import store from '../store';

export const WEEK_IN_SECONDS = 1000 - 3600 * 24 * 7;

/**
 * @typedef {import('../store').Notice} Notice
 * @typedef {import('../store').NoticeStore} NoticeStore
 * @typedef {import('../utils/index').SortBy} SortBy
 */

/**
 * @typedef {Object} Props
 * @property {string=}   context       Optional notices context to render.
 * @property {Notice[]=} notifications The collection of notices to render.
 * @property {SortBy=}   splitBy       Optionally split notices base on criteria.
 */

/**
 * WP Notification Feature toolbar in the secondary position of the admin bar
 * It watches for state updates and renders a <Notifications /> component with the updated state
 *
 * @param {Props} props
 * @return {JSX.Element} Notifications
 */
export const NoticesArea = ( props ) => {
	let { notifications, splitBy, context = defaultContext } = props;

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
		if ( ! notifications.length ) {
			return <NoticeEmpty size={ 96 } message={ __( 'empty' ) } />;
		}

		/** split the notifications by date */
		const sorted = getSorted( notifications, splitBy );

		return (
			<>
				{ sorted.map( ( list, index ) => (
					<section key={ index }>
						<NoticeHubSectionHeader
							context={ context }
							unreadCount={ list.length }
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
