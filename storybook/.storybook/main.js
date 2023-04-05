const stories = ['../stories/**/*.@(js|tsx|mdx)'].filter(Boolean);
module.exports = {
	stories,
	addons: ['@storybook/addon-essentials', '@storybook/addon-interactions', '@storybook/addon-links', '@storybook/addon-mdx-gfm'],
	framework: {
		name: '@storybook/react-webpack5',
		options: {},
	},

};
