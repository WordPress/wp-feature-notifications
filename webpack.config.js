const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: { 'wp-notify': path.resolve( process.cwd(), `src/wp-notify.js` ) },
};
