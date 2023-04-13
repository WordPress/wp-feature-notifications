/** Wp-notify style */
import * as jsonData from '../../includes/restapi/fake_api.json';
import { NoticesLoop } from '../../src/scripts/components/NoticesLoop';

// filter out non dashboard notices
const adminBarNotices = jsonData
	.filter( ( notice ) => notice.id && notice.id >= 10 )
	.map(
		( notice ) =>
			( notice = {
				...notice,
				context: 'dashboard',
			} )
	);

export default {
	title: 'wp-feature-notifications/Dashboard/Multiple',
	component: NoticesLoop,
	parameters: {
		backgrounds: {
			default: 'WordPress',
			values: [ { name: 'WordPress', value: '#f0f0f1' } ],
		},
	},
};

/**
 * Single Notification UI component
 * https://github.com/WordPress/wp-feature-notifications/issues/16#issuecomment-896031592
 * https://github.com/WordPress/wp-feature-notifications/issues/37#issuecomment-896080025
 */

// More on component templates: https://storybook.js.org/docs/react/writing-stories/introduction#using-args
const MultipleNotificationsTemplate = ( args ) => (
	<>
		<div
			id="wpbody"
			style={ {
				fontFamily:
					'-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
			} }
		>
			<NoticesLoop
				notices={ adminBarNotices }
				context={ 'dashboard' }
				{ ...args }
			/>
		</div>
	</>
);

// More on args: https://storybook.js.org/docs/react/writing-stories/args
export const Multiple = MultipleNotificationsTemplate;
