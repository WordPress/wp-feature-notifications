/** the single notification component */
import { NotificationHub } from '../../src/scripts/components/NotificationHub';

export default {
	title: 'wp-feature-notifications/Notification Hub/Multiple',
	component: NotificationHub,
	parameters: {
		backgrounds: {
			default: 'WordPress',
			values: [ { name: 'WordPress', value: '#f0f0f1' } ],
		},
	},
	argTypes: {
		sortBy: {
			options: [ 'title', 'date', 'id' ],
			control: { type: 'radio' },
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

export const multiple = Template.bind( {} );
// More on args: https://storybook.js.org/docs/react/writing-stories/args

multiple.args = {};
