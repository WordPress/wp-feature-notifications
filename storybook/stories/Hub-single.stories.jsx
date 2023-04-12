/** the single notification component */

import { Notice } from '../../src/scripts/components/Notice';

export default {
	title: 'wp-feature-notifications/Notification Hub/Single',
	component: Notice,
	parameters: {
		backgrounds: {
			default: 'WordPress',
			values: [ { name: 'WordPress', value: '#f0f0f1' } ],
		},
	},
	argTypes: {
		date: {
			control: {
				type: 'date',
			},
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
			<Notice { ...args } context={ 'adminbar' } />
		</aside>
	);
};

export const Single = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

Single.args = {
	title: 'Notice Example',
	message:
		'Notice message. This is a simple example and will be shown in the admin bar.',
	date: Math.abs( Date.now() * 0.001 ),
	action: {
		acceptMessage: 'Aknowledge',
		acceptLink: '#',
	},
};
