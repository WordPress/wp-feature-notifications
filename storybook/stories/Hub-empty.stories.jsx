/** the empty notification component */

import { Notice } from '../../src/scripts/components/Notice';
import { NoticeEmpty } from '../../src/scripts/components/NoticeEmpty';
import { __ } from '@wordpress/i18n';

export default {
	title: 'wp-feature-notifications/Notification Hub/Empty',
	component: Notice,
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
 * @param {Object} args - the sidebar template arguments
 */
const Template = ( args ) => {
	return (
		<aside id={ 'wp-notification-hub' } style={ { opacity: 1, right: 0 } }>
			<NoticeEmpty { ...args } />
		</aside>
	);
};

export const Empty = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

Empty.args = {
	size: 96,
	message: __( 'empty' ),
};
