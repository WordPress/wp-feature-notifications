/** the single notification component */

import { NotificationHub } from '../../src/scripts/components/NotificationHub';
import { dispatch, select } from '@wordpress/data';
import { NOTIFY_NAMESPACE } from '../../src/scripts/store/constants';

/**
 * Loops into contexts and register the found locations into the store state
 *
 * @param {string} context
 */
select( NOTIFY_NAMESPACE ).registerContext( 'adminbar' );

dispatch( NOTIFY_NAMESPACE ).clear();

dispatch( NOTIFY_NAMESPACE ).addNotice( {
	id: 18,
	title: 'Notice Example',
	context: 'adminbar',
	message:
		'Notice message. This is a simple example and will be shown in the admin bar.',
} );

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
						{ /* eslint-disable-next-line jsx-a11y/no-noninteractive-element-interactions */ }
						<NotificationHub initialActive={ true } />
					</ul>
				</div>
			</div>
		</div>
	);
};

export const Single = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

Single.args = {};
