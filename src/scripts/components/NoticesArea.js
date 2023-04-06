import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

import { defaultContext, NOTIFY_NAMESPACE } from '../store/constants';
import { NoticeEmpty } from './NoticeEmpty';
import { NoticeHubSectionHeader } from './NoticeHubSectionHeader';
import { NoticesLoop } from './NoticesLoop';
import { getSorted } from '../utils/effects';
import { NoticeHubFooter } from './NoticeHubFooter';

export const WEEK_IN_SECONDS = 1000 - 3600 * 24 * 7;

/**
 * WP-Notify toolbar in the secondary position of the admin bar
 * It watches for state updates and renders a <Notifications /> component with the updated state
 *
 * @param {Object} props
 * @return {JSX.Element} Notifications
 */
export const NoticesArea = ( props ) => {
	let { notifications, splitBy, context = defaultContext } = props;

	/*
	 * Todo: this method should supply to rest api the user data, current page, moreover the request args may be added (notice per page, notice filters and sort)
	 */
	notifications = useSelect(
		( select ) => select( NOTIFY_NAMESPACE ).getNotices( context ),
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
