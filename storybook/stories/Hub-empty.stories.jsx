/** the empty notification component */

import { __ } from '@wordpress/i18n';
import { NotificationHub } from '../../src/scripts/components/NotificationHub';
import { dispatch, select } from '@wordpress/data';
import { NOTIFY_NAMESPACE } from '../../src/scripts/store/constants';

select( NOTIFY_NAMESPACE ).registerContext( 'adminbar' );

dispatch( NOTIFY_NAMESPACE ).clear();

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
						<NotificationHub />
					</ul>
				</div>
			</div>
			<p
				style={ {
					fontSize: '4rem',
					padding: '2rem',
				} }
			>
				Click the bell ➡️
			</p>
		</div>
	);
};

export const Empty = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

Empty.args = {
	size: 96,
	message: __( 'empty' ),
};
