/**
 * Set WordPress presets
 */

const eslintConfig = {
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
};

eslintConfig.parserOptions = {
	ecmaVersion: 6,
	env: { es6: true },
	babelOptions: {
		presets: [ require.resolve( '@wordpress/babel-preset-default' ) ],
	},
};

module.exports = eslintConfig;
