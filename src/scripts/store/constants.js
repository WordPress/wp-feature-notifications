/**
 *  @member {string} NOTIFY_NAMESPACE WP-Notify namespace
 */
export const NOTIFY_NAMESPACE = 'core/wp-notify';

/**
 *  @member {string} API_PATH WP-Notify rest api path
 */
export const API_PATH = '/wp/v2/notifications/';

/**
 *  @member {string} defaultContext WP-Notify default context
 */
export const defaultContext = 'adminbar';

/**
 *  @member {Object} context WP-Notify default contexts
 */
export const contexts = [ defaultContext, 'dashboard' ];

/**
 *  @member {string} the url of the notifications settings page
 */
export const settingsPageUrl =
	// eslint-disable-next-line camelcase
	typeof window.wp_notify_data !== 'undefined'
		? window.wp_notify_data?.settingsPage
		: '';
