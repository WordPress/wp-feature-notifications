/** the single notification component */
import { NotificationHub } from '../../src/scripts/components/NotificationHub';
import { dispatch } from '@wordpress/data';
import { NOTIFY_NAMESPACE } from '../../src/scripts/store/constants';
import * as noticeFakeStore from './assets/fake_api.json';

dispatch( NOTIFY_NAMESPACE ).clear( 'adminbar' );

noticeFakeStore.forEach( ( notice ) => {
	dispatch( NOTIFY_NAMESPACE ).addNotice( {
		...notice,
		context: 'adminbar',
	} );
} );

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

export const multiple = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

multiple.args = {};
