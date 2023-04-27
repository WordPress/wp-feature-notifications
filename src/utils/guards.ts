import type { DashiconsIcon, ImageIcon, SvgIcon } from '../types';

/**
 * @param icon The icon to guard as a Dashicons icon.
 * @return Whether or not the icon is an Dashicons icon.
 */
export const isDashiconsIcon = ( icon: unknown ): icon is DashiconsIcon => {
	return typeof ( icon as DashiconsIcon )?.dashicons === 'string';
};

/**
 * @param icon The icon to guard as a image icon.
 * @return Whether or not the icon is an image icon.
 */
export const isImageIcon = ( icon: unknown ): icon is ImageIcon => {
	return typeof ( icon as ImageIcon )?.src === 'string';
};

/**
 * @param icon The icon to guard as a SVG icon.
 * @return Whether or not the icon is an SVG icon.
 */
export const isSvgIcon = ( icon: unknown ): icon is SvgIcon => {
	return typeof ( icon as SvgIcon )?.svg === 'string';
};
