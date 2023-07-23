// automatically get all the stories from the stories folder
const doc = ['../stories/**/*.@(mdx)'].filter(Boolean);
const stories = ['../stories/**/*.@(js|jsx|ts|tsx)'].filter(Boolean);
module.exports = {
  stories: [...doc, ...stories],
	addons: [
		'@storybook/addon-a11y',
		'@storybook/addon-links',
		'@storybook/addon-essentials',
		'@storybook/addon-interactions',
		'@storybook/addon-mdx-gfm'
	],
	framework: {
		name: '@storybook/react-webpack5',
		options: { lazyCompilation: true }
	},
	features: {
		babelModeV7: true,
		emotionAlias: false,
		storyStoreV7: true,
	},
	webpackFinal: async (config) => {
		// Add the ts loader
		config.module.rules.push(
			{
				test: /\.tsx?$/,
				use: [
					{
						loader: 'babel-loader',
						options: {
							presets: ["@wordpress/babel-preset-default"],
						},
					},
				],
				exclude: /node_modules/
			},
		);

		// Add the scss loader
		config.module.rules.push({
			test: /\.scss$/,
			use: ['style-loader', 'css-loader', 'sass-loader'],
		});

		return config;
	},
	docs: {
		autodocs: true
	}
};
