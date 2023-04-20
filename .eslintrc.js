/**
 * ESLint presets
 */
module.exports = {
	root: true,
	parser: '@typescript-eslint/parser',
	parserOptions: {
		project: [ './tsconfig.json', 'tsconfig.eslint.json' ],
	},
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	rules: {
		'import/order': [
			'error',
			{
				alphabetize: {
					order: 'asc',
					caseInsensitive: true,
				},
				'newlines-between': 'always',
				groups: [ 'builtin', 'external', 'parent', 'sibling', 'index' ],
				pathGroups: [
					{
						pattern: '@wordpress/**',
						group: 'external',
					},
				],
				pathGroupsExcludedImportTypes: [ 'builtin' ],
			},
		],
	},
	overrides: [
		{
			files: 'tests/**/*',
			rules: {
				'no-undef': 'off',
			},
		},
	],
	settings: {
		'import/parsers': {
			'@typescript-eslint/parser': [ '.js', '.jsx', '.ts', '.tsx' ],
		},
		'import/resolver': {
			typescript: {
				alwaysTryTypes: true,
				project: [ './tsconfig.json', 'tsconfig.eslint.json' ],
			},
		},
	},
	env: {
		browser: true,
		es2021: true,
		jest: true,
	},
};
