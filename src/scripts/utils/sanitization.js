import { escapeHTML } from '@wordpress/escape-html';

/**
 * It takes a string and returns a sanitized version of that string.
 *
 * @param {string} string - The text to be purified.
 * @return {{ __html: string }} The sanitized string.
 */
export const purify = ( string ) => {
	return {
		__html: escapeHTML( string ),
	};
};
