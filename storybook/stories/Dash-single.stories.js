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
import '@wordpress/components/build-style/style.css';

export default {
	title: 'wp-feature-notifications/Dashboard/Single',
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
const Template = ( args ) => (
	<div
		id="wpbody"
		style={ {
			fontFamily:
				'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
		} }
	>
		<div id="wp-notify-dashboard-notices" className="wrap">
			<Notice { ...args } context={ 'dashboard' } />
		</div>
	</div>
);

export const single = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args
single.args = {
	title: 'Notice Example',
	message:
		'Notice message. This is a simple example and will be shown in the dashboard',
	date: Date.now(),
	dismissible: true,
	action: {
		acceptMessage: 'Accept',
		acceptLink: '#',
		dismissLabel: 'Dismiss',
	},
};
