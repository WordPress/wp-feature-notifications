/**
 * @typedef {import('../store').DashiconsIcon} DashiconsIcon
 * @typedef {import('../store').ImageIcon} ImageIcon
 * @typedef {import('../store').SvgIcon} SvgIcon
 * @typedef {import('../store').NoticeIcon} NoticeIcon
 */

/**
 * @param {Object} icon The icon to guard as a Dashicons icon.
 * @return {icon is DashiconsIcon} Whether or not the icon is an Dashicons icon.
 */
export const isDashiconsIcon = ( icon ) => {
	return typeof icon?.dashicons === 'string';
};

/**
 * @param {Object} icon The icon to guard as a image icon.
 * @return {icon is ImageIcon} Whether or not the icon is an image icon.
 */
export const isImageIcon = ( icon ) => {
	return typeof icon?.src === 'string';
};

/**
 * @param {Object} icon The icon to guard as a SVG icon.
 * @return {icon is SvgIcon} Whether or not the icon is an SVG icon.
 */
export const isSvgIcon = ( icon ) => {
	return typeof icon?.svg === 'string';
};
