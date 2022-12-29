/* global pagenow, wp_notify_data */

import './utils/metaBox';

/**
 * @module wpNotify
 *
 * @description add some demo notifications to the dashboard
 *
 * @example if you need to enable the notification outside dashboard and wpNotify setting page
 *
 * @type {wp.notify} notification module
 * @property {Function} {fetch} - fetch for new notifications
 */
if (
	pagenow &&
	(pagenow === 'settings_page_wp-notify' || pagenow === 'dashboard')
) {
	// eslint-disable-next-line camelcase
	wp.notify.fetch();
}
