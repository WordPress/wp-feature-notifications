/* eslint-disable no-console */

const fs = require( 'node:fs/promises' );
const path = require( 'node:path' );

const { format, resolveConfig } = require( 'prettier' );

const TWO_WEEKS_IN_SECONDS = 60 * 60 * 24 * 14;

const inputPath = path.join( __dirname, 'scaffold-data.json' );
const outputPath = path.join( __dirname, '../', 'tmp', 'demo-data.json' );

main().catch( ( error ) => {
	throw error;
} );

async function main() {
	const prettierOptions = {
		...( await resolveConfig( process.cwd() ) ),
		parser: 'json',
	};
	const raw = await fs.readFile( inputPath, { encoding: 'utf-8' } );
	const { notices } = JSON.parse( raw );
	// notices.sort( ( a, b ) =>  b.id - a.id); // ascending order by id
	const count = notices.length;
	const start = Date.now() - TWO_WEEKS_IN_SECONDS * 1000;
	const dates = randomDates( new Date( start ), count );
	const result = [];
	for ( let i = 0; i < count; i++ ) {
		const notice = notices[ i ];
		const date = dates[ i ];
		result.push( { ...notice, date } );
	}
	const stringified = JSON.stringify( result, null, 2 );
	await fs.writeFile( outputPath, format( stringified, prettierOptions ) );
}

/**
 * Generate a set of random datetime between a starting date and now.
 *
 * TODO check what the correct format of the notice `data` property, is it seconds?
 *
 * @param {Date}   start The minimum value of the set of random dates.
 * @param {number} count The number of dates to generate.
 * @return {number[]} A set of random datetime.
 */
function randomDates( start, count ) {
	const min = start.getTime();
	const max = Date.now();
	if ( min >= max )
		throw new Error(
			'The starting datetime must be less than the current time.'
		);
	const diff = max - min;
	const result = [];
	for ( let i = 0; i < count; i++ ) {
		const random = Math.random() * diff;
		result.push( min + random );
	}
	result.sort( ( a, b ) => b - a ); // descending order
	return result.map( ( x ) => Math.floor( x / 1000 ) ); // convert to seconds
}
