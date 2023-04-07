/**
 * ESLint presets
 */
module.exports = {
	root: true,
	parser: '@typescript-eslint/parser',
	parserOptions: {
		project: [ './jsconfig.json', 'jsconfig.eslint.json' ],
	},
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	settings: {
		'import/parsers': {
			'@typescript-eslint/parser': [ '.js', '.jsx', '.ts', '.tsx' ],
		},
		'import/resolver': {
			typescript: {
				alwaysTryTypes: true,
				project: [ './jsconfig.json', 'jsconfig.eslint.json' ],
			},
		},
	},
	env: {
		browser: true,
		es2021: true,
		jest: true,
	},
};
