/** the single notification component */

/** Backend style */
import '../../src/stories/assets/wp-core/admin-bar.css';
import '../../src/stories/assets/wp-core/admin-menu.css';
import '../../src/stories/assets/wp-core/buttons.css';
import '../../src/stories/assets/wp-core/common.css';
import '../../src/stories/assets/wp-core/dashboard.css';
import '../../src/stories/assets/wp-core/dashicons.css';
import '../../src/stories/assets/wp-core/edit.css';
import '../../src/stories/assets/wp-core/nav-menus.css';
import '../../src/stories/assets/wp-core/normalize.css';
import '../../src/stories/assets/wp-core/site-health.css';

/** Wp-notify style */
import '../styles/wp-notify.scss';
import '@wordpress/components/build-style/style.css';

import { Notice } from '../scripts/components/Notice';

export default {
	title: 'Notification Hub/Single',
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
	date: Date.now(),
	action: {
		acceptMessage: 'Aknowledge',
		acceptLink: '#',
	},
};
