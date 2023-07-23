const path = require( 'path' );

const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		'wp-notifications': path.resolve(
			process.cwd(),
			`src/wp-notifications`
		),
	},
};
