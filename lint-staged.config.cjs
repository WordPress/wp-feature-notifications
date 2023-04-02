module.exports = {
	'**/*.(ts|tsx)': () => 'pnpm check',
	'**/*.(json|yml|yaml)': (filenames) => {
		const files = filenames.join(' ');
		return [`prettier --write ${files}`];
	},
	'**/*.(js|jsx|cjs|mjs|ts|tsx)': (filenames) => {
		const files = filenames.join(' ');
		return [`eslint --ext .js,.jsx,.cjs,.mjs,.ts,.tsx --fix ${files}`];
	},
	'**/*.php': (filenames) => {
		const files = filenames.join(' ');
		return [
			`php vendor/bin/phpcbf --standard=phpcs.xml.dist -s --report=summary ${files}`,
		];
	},
};
