/**
 * The WP Notification Feature default context.
 */
export const defaultContext = 'adminbar';

/**
 * The WP Notification Feature default contexts
 */
export const contexts = [ defaultContext, 'dashboard' ] as const;

/**
 * The url of the notifications settings page
 */
export const settingsPageUrl =
	// eslint-disable-next-line camelcase
	typeof window.wp_notifications_data !== 'undefined'
		? window.wp_notifications_data?.settingsPage
		: '';
