/** the single notification component */
import { Notice } from '../../src/scripts/components/Notice';

/** Backend style */
import './assets/wp-core/admin-bar.css';
import './assets/wp-core/admin-menu.css';
import './assets/wp-core/buttons.css';
import './assets/wp-core/common.css';
import './assets/wp-core/dashboard.css';
import './assets/wp-core/dashicons.css';
import './assets/wp-core/edit.css';
import './assets/wp-core/nav-menus.css';
import './assets/wp-core/normalize.css';
import './assets/wp-core/site-health.css';

/** Wp-notify style */
import '../styles/wp-notify.scss';
import jsonData from '../../includes/restapi/fake_api.json';
import { NoticesLoop } from '../../src/scripts/components/NoticesLoop';
import { getSorted } from '../../src/scripts/utils/drawer';

// filter out non dashboard notices
const adminBarNotices = jsonData.filter(
	( term ) => term.hasOwnProperty( 'context' ) && term.context === 'dashboard'
);

export default {
	title: 'wp-feature-notifications/Dashboard/Multiple',
	component: Notice,
	parameters: {
		backgrounds: {
			default: 'WordPress',
			values: [ { name: 'WordPress', value: '#f0f0f1' } ],
		},
	},
};

/**
 * Single Notification UI component
 * https://github.com/WordPress/wp-feature-notifications/issues/16#issuecomment-896031592
 * https://github.com/WordPress/wp-feature-notifications/issues/37#issuecomment-896080025
 */

// More on component templates: https://storybook.js.org/docs/react/writing-stories/introduction#using-args
const MultipleNotificationsTemplate = ( args ) => (
	<>
		<div
			id="wpbody"
			style={ {
				fontFamily:
					'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
			} }
		>
			{ getSorted( adminBarNotices ).map( ( list, index ) => (
				<NoticesLoop
					key={ index }
					notices={ list }
					sortBy={ undefined }
					context={ 'dashboard' }
					{ ...args }
				/>
			) ) }
		</div>
	</>
);

// More on args: https://storybook.js.org/docs/react/writing-stories/args
export const Multiple = MultipleNotificationsTemplate.bind( {} );
Multiple.args = {};
