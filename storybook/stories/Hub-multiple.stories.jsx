import { useEffect, useRef } from '@wordpress/element';

/** the single notification component */
import { NoticesLoop } from '../scripts/components/NoticesLoop';

/** Backend style */
import '../../src/stories/assets/wp-core/admin-bar.css';
import '../../src/stories/assets/wp-core/admin-menu.css';
import '../../src/stories/assets/wp-core/buttons.css';
import '../../src/stories/assets/wp-core/common.css';
import '../../src/stories/assets/wp-core/dashboard.css';
import '../../src/stories/assets/wp-core/dashicons.css';
import '../../src/stories/assets/wp-core/edit.css';
import '../../src/stories/assets/wp-core/nav-menus.css';
import '../../src/stories/assets/wp-core/normalize.css';
import '../../src/stories/assets/wp-core/site-health.css';

/** Wp-notify style */
import '../styles/wp-notifications.scss';
import '@wordpress/components/build-style/style.css';

import jsonData from '../../includes/restapi/fake_api.json';
import { NoticeHubSectionHeader } from '../scripts/components/NoticeHubSectionHeader';
import { splitByDate } from '../scripts/utils/';
import { NoticeHubFooter } from '../scripts/components/NoticeHubFooter';

// filter out non adminbar notices
const adminBarNotices = jsonData.filter(
	( term ) =>
		! term.hasOwnProperty( 'context' ) || term.context === 'adminbar'
);

export default {
	title: 'Notification Hub/Multiple',
	component: NoticesLoop,
	parameters: {
		backgrounds: {
			default: 'WordPress',
			values: [ { name: 'WordPress', value: '#f0f0f1' } ],
		},
	},
	argTypes: {
		sortBy: {
			options: [ 'title', 'date', 'id' ],
			control: { type: 'radio' },
		},
	},
};

/**
 * Notification UI component
 *
 * @param {Object} args
 */
const Template = ( args ) => {
	const drawerRef = useRef( null );
	/**
	 * Fired each time you need to enable the sidebar
	 *
	 * @param {Event} e - the clicked bell button to enable the sidebar event
	 */
	const enableDrawer = ( e ) => {
		e.stopPropagation();
		e.currentTarget.classList.add( 'active' );
	};

	/**
	 * Fired to disable the sidebar
	 *
	 * @param {Event} e - the event that fires the close sidebar event
	 */
	const disableDrawer = ( e ) => {
		e.stopPropagation();
		e.currentTarget.classList.remove( 'active' );
	};

	/**
	 * Fired to close the sidebar with keys
	 *
	 * @param {Event} e - the event that fires the close sidebar event
	 */
	const disableDrawerOnKey = ( e ) => {
		if ( e.key === 'Escape' ) {
			disableDrawer( e );
		}
	};

	useEffect( () => {
		return args.forceEnableDrawer
			? drawerRef.current.classList.add( 'active' )
			: drawerRef.current.classList.remove( 'active' );
	}, [ args.forceEnableDrawer ] );

	return (
		<div id="wpcontent">
			<div id="wpadminbar" className="nojq">
				<div
					className="quicklinks"
					id="wp-toolbar"
					role="navigation"
					aria-label="Toolbar"
				>
					<ul
						id="wp-admin-bar-top-secondary"
						className="ab-top-secondary ab-top-menu"
					>
						{ /* eslint-disable-next-line jsx-a11y/no-noninteractive-element-interactions */ }
						<li
							key={ 0 }
							id="wp-admin-bar-wp-notify"
							ref={ drawerRef }
							onClick={ ( e ) => enableDrawer( e ) }
							onFocus={ ( e ) => enableDrawer( e ) }
							onBlur={ ( e ) => disableDrawer( e ) }
							onKeyDown={ ( e ) => disableDrawerOnKey( e ) }
						>
							<div className="ab-item ab-empty-item" tabIndex="0">
								<span
									className="ab-icon"
									aria-hidden="true"
								></span>
								<span className="ab-label">Notifications</span>
							</div>
							<aside
								id={ 'wp-notification-hub' }
								className={ 'active' }
							>
								<div
									className={ 'wp-notification-hub-wrapper' }
								>
									{ splitByDate(
										adminBarNotices,
										args.sortBy
									).map( ( list, index ) => (
										<section key={ index }>
											<NoticeHubSectionHeader
												context={ 'adminbar' }
												unreadCount={ list.length }
												isMain={ index === 0 } // the main section is the first one
											/>
											<NoticesLoop
												notices={ list }
												{ ...args }
											/>
										</section>
									) ) }
									<NoticeHubFooter />
								</div>
							</aside>
						</li>
					</ul>
				</div>
			</div>
			<p
				style={ {
					fontSize: '4rem',
					padding: '2rem',
				} }
			>
				Click the bell ➡️
			</p>
		</div>
	);
};

export const multiple = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

multiple.args = {
	sortBy: 'date',
	forceEnableDrawer: false,
};
