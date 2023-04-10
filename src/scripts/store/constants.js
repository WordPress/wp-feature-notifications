/**
 *  @member {string} NOTIFY_NAMESPACE WP Notification Feature namespace
 */
export const NOTIFY_NAMESPACE = 'core/wp-notify';

/**
 *  @member {string} API_PATH WP Notification Feature rest api path
 */
export const API_PATH = '/wp/v2/notifications/';

/**
 *  @member {string} defaultContext WP Notification Feature default context
 */
export const defaultContext = 'adminbar';

/**
 *  @member {Object} context WP Notification Feature default contexts
 */
export const contexts = [ defaultContext, 'dashboard' ];

/**
 *  @member {string} the url of the notifications settings page
 */
export const settingsPageUrl =
	// eslint-disable-next-line camelcase
	typeof window.wp_notifications_data !== 'undefined'
		? window.wp_notifications_data?.settingsPage
		: '';
