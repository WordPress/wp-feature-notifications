/** the empty notification component */

/** Backend style */
import './assets/wp-core/admin-bar.css';
import './assets/wp-core/admin-menu.css';
import './assets/wp-core/buttons.css';
import './assets/wp-core/common.css';
import './assets/wp-core/dashboard.css';
import './assets/wp-core/dashicons.css';
import './assets/wp-core/edit.css';
import './assets/wp-core/nav-menus.css';
import './assets/wp-core/normalize.css';
import './assets/wp-core/site-health.css';

/** Wp-notify style */
import '../../build/wp-notify.css';
import '@wordpress/components/build-style/style.css';

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
 * @param args
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
