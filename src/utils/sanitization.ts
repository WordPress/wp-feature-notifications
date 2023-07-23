import { escapeHTML } from '@wordpress/escape-html';

/**
 * It takes a string and returns a sanitized version of that string.
 *
 * @param string - The text to be purified.
 * @return The sanitized string.
 */
export const purify = ( string: string ) => {
	return {
		__html: escapeHTML( string ) as string,
	};
};
