/** the single notification component */

import { NotificationHub } from '../../src/scripts/components/NotificationHub';
import * as jsonData from '../../includes/restapi/fake_api.json';
import { dispatch } from '@wordpress/data';
import { NOTIFY_NAMESPACE } from '../../src/scripts/store/constants';

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
 * @typedef {import('../../src/scripts/store').Notice} Notice
 */
/**
 * Given a context, get the notifications for that context.
 *
 * @param {string} context The context to get the notifications for.
 * @param {number} count   The number of notifications.
 *
 * @return {Notice[]} The context notification objects.
 */
const getNotificationByType = ( context, count ) => {
	const notes = jsonData.filter( ( notice ) => notice.context === context );
	return notes.slice( 0, count );
};

/**
 * Notification UI component
 */
const Template = () => {
	dispatch( NOTIFY_NAMESPACE ).clear( 'adminbar' );

	dispatch( NOTIFY_NAMESPACE ).addNotice(
		...getNotificationByType( 'adminbar', 1 )
	);

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

export const Single = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

Single.args = {};
