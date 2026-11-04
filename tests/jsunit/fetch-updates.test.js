/**
 * @jest-environment jsdom
 */

/**
 * Integration tests covering the `fetchUpdates` thunk and the store resolver.
 *
 * These reproduce bugs 1 and 7 from issue #619: the old generator resolver returned
 * `hydrate()` instead of yielding it, so nothing ever reached the store, and the
 * `fetchUpdates` selector existed only to trigger that resolver.
 */

jest.mock( '@wordpress/api-fetch', () => ( {
	__esModule: true,
	default: jest.fn(),
} ) );

import apiFetch from '@wordpress/api-fetch';
import { dispatch, select } from '@wordpress/data';

import store from '../../src/store';

describe( 'fetchUpdates thunk', () => {
	beforeEach( () => {
		apiFetch.mockReset();
	} );

	it( 'fetches, normalises and hydrates notices into the store', async () => {
		apiFetch.mockResolvedValue( [
			{
				id: 1,
				context: 'adminbar',
				message: 'A message',
				created_at: '2024-01-02T03:04:05+00:00',
				is_dismissible: true,
			},
		] );

		await dispatch( store ).fetchUpdates();

		expect( apiFetch ).toHaveBeenCalledWith( {
			path: '/wp-notifications/v1/notifications/',
		} );

		const notices = select( store ).getNotices( 'adminbar' );

		expect( notices ).toHaveLength( 1 );
		expect( notices[ 0 ].message ).toBe( 'A message' );
		// The API sends created_at; the store must expose a Date.
		expect( notices[ 0 ].date ).toBeInstanceOf( Date );
		expect( notices[ 0 ].dismissible ).toBe( true );
	} );

	it( 'returns the normalised notices from the thunk', async () => {
		apiFetch.mockResolvedValue( [ { id: 2, message: 'Second' } ] );

		const result = await dispatch( store ).fetchUpdates();

		expect( result ).toHaveLength( 1 );
		expect( result[ 0 ].id ).toBe( 2 );
	} );

	it( 'de-duplicates concurrent fetches into a single request', async () => {
		apiFetch.mockResolvedValue( [ { id: 3, message: 'Dedupe' } ] );

		await Promise.all( [
			dispatch( store ).fetchUpdates(),
			dispatch( store ).fetchUpdates(),
		] );

		expect( apiFetch ).toHaveBeenCalledTimes( 1 );
	} );
} );

describe( 'getNotices resolver', () => {
	it( 'fetches notices the first time a context is selected', async () => {
		apiFetch.mockResolvedValue( [
			{ id: 5, context: 'dashboard', message: 'Resolved' },
		] );

		// Selecting an unresolved context triggers the resolver.
		select( store ).getNotices( 'dashboard' );

		// Let the resolver's async thunk settle.
		await new Promise( ( resolve ) => setTimeout( resolve, 0 ) );

		expect( apiFetch ).toHaveBeenCalled();

		const notices = select( store ).getNotices( 'dashboard' );
		expect( notices ).toHaveLength( 1 );
		expect( notices[ 0 ].message ).toBe( 'Resolved' );
	} );
} );
