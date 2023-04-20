/** the single notification component */
import { Notice } from '../../src/scripts/components/Notice';

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
		<div id="wp-notifications-dashboard-notices" className="wrap">
			<Notice { ...args } context={ 'dashboard' } />
		</div>
	</div>
);

export const single = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args
single.args = {
	id: 15,
	title: 'Try this new Notification feature',
	source: '#WP-Notify',
	date: '2023-04-15T19:35:56',
	message:
		'👋 Hello from the WP Feature Notifications team! Thank you for testing out the plugin. You might want to give it a try so click on the bell icon on the right side of the adminbar.',
	dismissible: true,
};
