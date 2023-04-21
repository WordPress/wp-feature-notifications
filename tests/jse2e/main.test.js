/**
 * Load utilities from the e2e-test-utils package.
 */
import { visitAdminPage } from '@wordpress/e2e-test-utils';

// Name of the test suite.
describe( 'Hello World', () => {
	// Flow being tested.
	// Ideally each flow is independent and can be run separately.
	it( 'Should load properly', async () => {
		// Navigate the admin and performs tasks
		// Use Puppeteer APIs to interact with mouse, keyboard...
		await visitAdminPage( '/' );

		// Assertions
		const nodes = await page.$x(
			'//h2[contains(text(), "Welcome to WordPress!")]'
		);
		expect( nodes.length ).not.toEqual( 0 );
	}, 10000 );
} );
