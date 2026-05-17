import wpConfig from '@wordpress/scripts/config/eslint.config.cjs';

/**
 * ESLint presets
 */
export default [
	...wpConfig,
	{
		files: [ '**/*.js', '**/*.jsx', '**/*.ts', '**/*.tsx' ],
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
		rules: {
			'import/order': [
				'error',
				{
					alphabetize: {
						order: 'asc',
						caseInsensitive: true,
					},
					'newlines-between': 'always',
					groups: [
						'builtin',
						'external',
						'parent',
						'sibling',
						'index',
					],
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
	},
	{
		files: [ 'tests/**/*' ],
		rules: {
			'no-undef': 'off',
		},
	},
];
