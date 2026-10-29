/**
 * @jest-environment jsdom
 */

/**
 * Unit tests covering the notifications store reducer, normalisation and utils.
 *
 * Each test maps to a bug described in issue #619.
 */

import { normalizeNotice } from '../../src/store/normalize';
import reducer from '../../src/store/reducer';
import { findContext } from '../../src/store/utils';

/**
 * Build a minimal notice object for the reducer tests.
 *
 * @param {number} id      The notice id.
 * @param {string} context The notice context.
 *
 * @return {Object} A notice object.
 */
const notice = ( id, context = 'adminbar' ) => ( {
	id,
	context,
	message: 'Hello',
	date: new Date(),
} );

describe( 'notifications reducer', () => {
	it( 'registers a new context without clobbering existing notices', () => {
		const state = { adminbar: [ notice( 1 ) ] };

		const next = reducer( state, {
			type: 'REGISTER_CONTEXT',
			context: 'dashboard',
		} );

		expect( next.dashboard ).toEqual( [] );
		expect( next.adminbar ).toBe( state.adminbar );
	} );

	it( 'returns the same state when registering an existing context', () => {
		const state = { adminbar: [] };

		expect(
			reducer( state, { type: 'REGISTER_CONTEXT', context: 'adminbar' } )
		).toBe( state );
	} );

	// Bug 3: HYDRATE spread a possibly-missing key.
	it( 'hydrates into an unregistered context without throwing', () => {
		const next = reducer(
			{},
			{
				type: 'HYDRATE',
				payload: [ notice( 1, 'dashboard' ) ],
			}
		);

		expect( next.dashboard ).toHaveLength( 1 );
	} );

	it( 'falls back to the default context when hydrating without one', () => {
		const next = reducer(
			{},
			{
				type: 'HYDRATE',
				payload: [ notice( 1, undefined ) ],
			}
		);

		expect( next.adminbar ).toHaveLength( 1 );
	} );

	it( 'does not duplicate a notice hydrated twice', () => {
		const state = { adminbar: [ notice( 1 ) ] };

		const next = reducer( state, {
			type: 'HYDRATE',
			payload: [ notice( 1 ) ],
		} );

		expect( next.adminbar ).toHaveLength( 1 );
	} );

	// Bug 3: ADD spread a possibly-missing key.
	it( 'adds into an unregistered context without throwing', () => {
		const next = reducer(
			{},
			{
				type: 'ADD',
				payload: notice( 1, 'dashboard' ),
			}
		);

		expect( next.dashboard ).toHaveLength( 1 );
	} );

	// Bug 4: findContext returned undefined and DELETE indexed state[ undefined ].
	it( 'ignores a DELETE of an unknown id instead of throwing', () => {
		const state = { adminbar: [ notice( 1 ) ] };

		expect( reducer( state, { type: 'DELETE', id: 999 } ) ).toBe( state );
	} );

	it( 'removes a known id', () => {
		const state = { adminbar: [ notice( 1 ), notice( 2 ) ] };

		const next = reducer( state, { type: 'DELETE', id: 1 } );

		expect( next.adminbar ).toHaveLength( 1 );
		expect( next.adminbar[ 0 ].id ).toBe( 2 );
	} );

	// Bug 4: UPDATE indexed state[ undefined ].
	it( 'ignores an UPDATE of an unknown id instead of throwing', () => {
		const state = { adminbar: [ notice( 1 ) ] };

		expect(
			reducer( state, {
				type: 'UPDATE',
				payload: { id: 999, status: 'dismissed' },
			} )
		).toBe( state );
	} );

	// Bug 5: CLEAR mutated the previous state before spreading.
	it( 'does not mutate the previous state on CLEAR', () => {
		const state = { adminbar: [ notice( 1 ) ] };

		const next = reducer( state, { type: 'CLEAR', context: 'adminbar' } );

		expect( next.adminbar ).toEqual( [] );
		expect( state.adminbar ).toHaveLength( 1 );
	} );
} );

describe( 'findContext', () => {
	it( 'returns the context holding the id', () => {
		expect( findContext( { adminbar: [ notice( 1 ) ] }, 1 ) ).toBe(
			'adminbar'
		);
	} );

	it( 'returns undefined for an unknown id', () => {
		expect(
			findContext( { adminbar: [ notice( 1 ) ] }, 2 )
		).toBeUndefined();
	} );
} );

describe( 'normalizeNotice', () => {
	// Bug 6: the client read notice.date, the API sends created_at.
	it( 'maps created_at to a Date', () => {
		const result = normalizeNotice( {
			id: 1,
			created_at: '2024-01-02T03:04:05+00:00',
		} );

		expect( result.date ).toBeInstanceOf( Date );
		expect( result.date.toISOString() ).toBe( '2024-01-02T03:04:05.000Z' );
	} );

	it( 'defaults a missing date to now', () => {
		expect( normalizeNotice( { id: 2 } ).date ).toBeInstanceOf( Date );
	} );

	it( 'maps the snake_case REST fields to the Notice shape', () => {
		const result = normalizeNotice( {
			id: 3,
			is_dismissible: true,
			channel_title: 'My Plugin',
			dismiss_label: 'Dismiss',
			accept_label: 'Open',
			accept_link: 'https://example.com',
			icon: 'info',
			message: 'A message',
			title: 'A title',
			status: 'new',
		} );

		expect( result.dismissible ).toBe( true );
		expect( result.source ).toBe( 'My Plugin' );
		expect( result.dismissLabel ).toBe( 'Dismiss' );
		expect( result.icon ).toEqual( { dashicons: 'info' } );
		expect( result.action ).toEqual( {
			acceptLink: 'https://example.com',
			acceptMessage: 'Open',
			dismissLabel: 'Dismiss',
			dismissible: true,
		} );
	} );

	it( 'ignores an icon that is not a string or a known icon object', () => {
		expect( normalizeNotice( { id: 4, icon: 42 } ).icon ).toBeUndefined();
	} );
} );
