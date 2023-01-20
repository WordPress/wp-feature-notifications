const stories = ['../src/stories/**/*.@(js|tsx|mdx)'].filter(Boolean);

module.exports = {
	core: {
		builder: 'webpack5',
	},
	stories,
	addons: [
		'@storybook/addon-actions',
		'@storybook/addon-essentials',
		'@storybook/addon-interactions',
		'@storybook/addon-links',
	],
	framework: '@storybook/react',
	features: {
		babelModeV7: true,
		emotionAlias: false
	},
};
