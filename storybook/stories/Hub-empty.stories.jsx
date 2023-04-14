/** the empty notification component */

import { __ } from '@wordpress/i18n';
import { NotificationHub } from '../../src/scripts/components/NotificationHub';
import { dispatch } from '@wordpress/data';
import { NOTIFY_NAMESPACE } from '../../src/scripts/store/constants';

export default {
	title: 'wp-feature-notifications/Notification Hub/Empty',
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
 */
const Template = () => {
	dispatch( NOTIFY_NAMESPACE ).clear( 'adminbar' );

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
						<li id="wp-admin-bar-wp-notification-hub">
							<NotificationHub initialActive={ true } />
						</li>
					</ul>
				</div>
			</div>
		</div>
	);
};

export const Empty = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

Empty.args = {
	size: 96,
	message: __( 'empty' ),
};
