import DOMPurify from 'dompurify';

/**
 * It takes a string and returns a sanitized version of that string.
 *
 * @param {string} string  - The text to be purified.
 * @param {Object} options - https://github.com/cure53/DOMPurify#can-i-configure-dompurify
 */
export const purify = (string, options = {}) => {
	return {
		__html: DOMPurify.sanitize(string, options),
	};
};
