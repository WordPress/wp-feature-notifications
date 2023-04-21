const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		'wp-notifications': path.resolve(
			process.cwd(),
			`src/wp-notifications`
		),
	},
};
