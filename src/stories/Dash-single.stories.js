/** the single notification component */
import { Notice } from '../scripts/components/Notice';

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
import '../../build/wp-notify.css';
import '@wordpress/components/build-style/style.css';

export default {
	title: 'Dashboard/Single',
	component: Notice,
	parameters: {
		backgrounds: {
			default: 'WordPress',
			values: [ { name: 'WordPress', value: '#f0f0f1' } ],
		},
	},
	argTypes: {
		date: {
			control: {
				type: 'date',
			},
		},
		action: {
			table: { expanded: true },
			acceptMessage: {
				control: {
					type: 'checkbox',
				},
			},
			acceptLink: {
				control: {
					type: 'checkbox',
				},
			},
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
