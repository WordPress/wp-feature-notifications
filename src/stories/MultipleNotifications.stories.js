import React from '@wordpress/element';

/** the single notification component */
import Notice from '../scripts/components/Notice';

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

export default {
	title: 'MultipleNotifications/Dashboard',
	component: Notice,
	parameters: {
		backgrounds: {
			default: 'WordPress',
			values: [{ name: 'WordPress', value: '#f0f0f1' }],
		},
	},
};

/**
 * Single Notification UI component
 * https://github.com/WordPress/wp-feature-notifications/issues/16#issuecomment-896031592
 * https://github.com/WordPress/wp-feature-notifications/issues/37#issuecomment-896080025
 */

// More on component templates: https://storybook.js.org/docs/react/writing-stories/introduction#using-args
const MultipleNotificationsTemplate = (args) => (
	<>
		<div
			id="wpbody"
			style={{
				fontFamily:
					'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
			}}
		>
			<Notice {...args} />
			<Notice {...args} />
			<Notice {...args} />
		</div>
	</>
);

// More on args: https://storybook.js.org/docs/react/writing-stories/args
export const Multiple = MultipleNotificationsTemplate.bind({});
Multiple.args = {
	title: 'Multiple Notices Example',
	message: 'WP-Notify Test',
	location: 'dashboard',
	acceptMessage: 'Accept',
	acceptLink: '#',
	dismissLabel: 'Dismiss',
	dismissible: true,
	date: Date.now() / 1000,
};
