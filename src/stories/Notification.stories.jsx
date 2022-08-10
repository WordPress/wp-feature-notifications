import React from '@wordpress/element';
import Notice from '../scripts/components/notification';
import '../../build/wp-notify.css';
import '@wordpress/components/build-style/style.css';

export default {
	title: 'Notification/Dashboard',
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
			<Notice { ...args } />
		</div>
	</div>
);

export const Simple = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args
Simple.args = {
	title: 'Notice Example',
	message: 'ciao',
	location: 'dashboard',
	acceptMessage: 'OK',
	acceptLink: '',
	dismissLabel: 'bye',
	dismissible: true,
	date: Date.now() / 1000,
};
