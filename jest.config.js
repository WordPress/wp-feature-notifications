const jestConfig = {
	verbose: true,
	preset: '@wordpress/jest-preset-default',
	setupFilesAfterEnv: [ 'expect-puppeteer' ],
	projects: [
		{
			displayName: 'unit',
			testMatch: [ '<rootDir>/tests/jsunit/**/*.test.js' ],
		},
		{
			displayName: 'e2e',
			preset: 'jest-puppeteer',
			testMatch: [ '<rootDir>/tests/jse2e/**/*.test.js' ],
		},
	],
};

module.exports = jestConfig;
