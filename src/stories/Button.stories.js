import React from '@wordpress/element';
import '@wordpress/components/build-style/style.css';

/**
 * WordPress dependencies
 */
import { more } from '@wordpress/icons';

import { Button } from '@wordpress/components';

// More on default export: https://storybook.js.org/docs/react/writing-stories/introduction#default-export
export default {
	title: 'Example/Button',
	component: Button,
	// More on argTypes: https://storybook.js.org/docs/react/api/argtypes
};

// More on component templates: https://storybook.js.org/docs/react/writing-stories/introduction#using-args
const Template = (args) => <Button {...args}>{args.label || 'clickme'}</Button>;

export const Primary = Template.bind({});
// More on args: https://storybook.js.org/docs/react/writing-stories/args
Primary.args = {
	variant: 'primary', // The accepted values are 'primary' (the primary button styles), 'secondary' (the default button styles), 'tertiary' (the text-based button styles), and 'link' (the link button styles).
	label: 'Button',
};

export const pressed = () => {
	const label = 'isPressed';
	return <Button isPressed>{label}</Button>;
};

export const buttons = () => {
	return (
		<div style={{ padding: '20px' }}>
			<h2>Small Buttons</h2>
			<div className="story-buttons-container">
				<Button isSmall>Button</Button>
				<Button variant="primary" isSmall>
					Primary Button
				</Button>
				<Button variant="secondary" isSmall>
					Secondary Button
				</Button>
				<Button variant="tertiary" isSmall>
					Tertiary Button
				</Button>
				<Button isSmall icon={more} />
				<Button isSmall variant="primary" icon={more} />
				<Button isSmall variant="secondary" icon={more} />
				<Button isSmall variant="tertiary" icon={more} />
				<Button isSmall variant="primary" icon={more}>
					Icon & Text
				</Button>
			</div>

			<h2>Regular Buttons</h2>
			<div className="story-buttons-container">
				<Button>Button</Button>
				<Button variant="primary">Primary Button</Button>
				<Button variant="secondary">Secondary Button</Button>
				<Button variant="tertiary">Tertiary Button</Button>
				<Button icon={more} />
				<Button variant="primary" icon={more} />
				<Button variant="secondary" icon={more} />
				<Button variant="tertiary" icon={more} />
				<Button variant="primary" icon={more}>
					Icon & Text
				</Button>
			</div>
		</div>
	);
};
