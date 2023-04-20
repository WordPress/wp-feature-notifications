// automatically get all the stories from the stories folder
const doc = ['../stories/**/*.@(mdx)'].filter(Boolean);
const stories = ['../stories/**/*.@(js|jsx|ts|tsx)'].filter(Boolean);

module.exports = {
  stories: [...doc, ...stories],
	addons: [
		'@storybook/addon-a11y',
		'@storybook/addon-links',
		'@storybook/addon-essentials',
		'@storybook/addon-interactions'
	],
	framework: {
		name: '@storybook/react-webpack5',
		options: {},
	},
	features: {
		babelModeV7: true,
		emotionAlias: false,
		storyStoreV7: true,
	},
	webpackFinal: async (config) => {
		// Add the scss loader
		config.module.rules.push({
			test: /\.scss$/,
			use: ['style-loader', 'css-loader', 'sass-loader'],
		});

		// Return the altered config
		return config;
	},
};
