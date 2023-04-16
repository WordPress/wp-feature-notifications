/** the single notification component */

import { NotificationHub } from '../../src/scripts/components/NotificationHub';
import { dispatch } from '@wordpress/data';
import { STORE_NAMESPACE } from '../../src/scripts/constants';

export default {
	title: 'wp-feature-notifications/Notification Hub/Single',
	component: NotificationHub,
	parameters: {
		backgrounds: {
			default: 'WordPress',
			values: [ { name: 'WordPress', value: '#f0f0f1' } ],
		},
	},
};

/**
 * Notification UI component
 *
 * @param {Object} args The notice controls.
 */
const Template = ( args ) => {
	dispatch( STORE_NAMESPACE ).clear( 'adminbar' );

	dispatch( STORE_NAMESPACE ).addNotice( {
		...args,
		context: 'adminbar',
	} );

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
						<li id="wp-admin-bar-wp-notifications-hub">
							<NotificationHub initialActive={ true } />
						</li>
					</ul>
				</div>
			</div>
		</div>
	);
};

export const single = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args
single.args = {
	id: 15,
	title: 'Try this new Notification feature',
	source: '#WP-Notify',
	date: new Date(),
	message:
		'👋 Hello from the WP Feature Notifications team! Thank you for testing out the plugin. You might want to give it a try so click on the bell icon on the right side of the adminbar.',
	dismissible: true,
};
