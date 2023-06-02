/** the single notification component */
import { dispatch } from '@wordpress/data';

import { NotificationHub } from '../../src/scripts/components/NotificationHub';
import { STORE_NAMESPACE } from '../../src/scripts/constants';
import jsonData from '../fake_api.json';

export default {
	title: 'wp-feature-notifications/Notification Hub/Multiple',
	component: NotificationHub,
	parameters: {
		backgrounds: {
			default: 'WordPress',
			values: [ { name: 'WordPress', value: '#f0f0f1' } ],
		},
	},
};

/**
 * Notification HUB component
 */
const Template = () => {
	dispatch( STORE_NAMESPACE ).clear( 'adminbar' );

	jsonData.forEach( ( notice ) => {
		dispatch( STORE_NAMESPACE ).addNotice( {
			...notice,
			context: 'adminbar',
			date: new Date( notice.date ),
		} );
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

export const multiple = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

multiple.args = {};
