<?php
/**
 * Plugin Name:       WP Feature Notifications
 * Plugin URI:        https://github.com/WordPress/wp-feature-notifications
 * Description:       A new (better) way to manage and deliver WordPress notifications to the relevant audience.
 * Version:           0.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.4
 * Author:            WP Feature Notifications Contributors
 * Text Domain:       wp-feature-notifications
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package wp-feature-notifications
 */

namespace WP\Notifications;

use WP\Notifications\REST;

define( 'WP_NOTIFICATIONS_LOG_PREFIX', 'WP_NOTIFICATIONS: ' );

if ( ! defined( 'WP_FEATURE_NOTIFICATION_PLUGIN_VERSION' ) ) {
	define( 'WP_FEATURE_NOTIFICATION_PLUGIN_VERSION', '0.0.1' );
}

if ( ! defined( 'WP_FEATURE_NOTIFICATION_VERSION' ) ) {
	define( 'WP_FEATURE_NOTIFICATION_VERSION', '1' );
}

if ( ! defined( 'WP_FEATURE_NOTIFICATION_PLUGIN_DIR' ) ) {
	define( 'WP_FEATURE_NOTIFICATION_PLUGIN_DIR', dirname( __FILE__ ) );
}

if ( ! defined( 'WP_FEATURE_NOTIFICATION_PLUGIN_DIR_URL' ) ) {
	define( 'WP_FEATURE_NOTIFICATION_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

// Require interface/class declarations..
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/exceptions/interface-exception.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/exceptions/class-runtime-exception.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/exceptions/class-invalid-recipient.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/exceptions/class-failed-to-add-recipient.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/interface-json-unserializable.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/interface-status.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/interface-notification.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-aggregate-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-base-notification.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/image/interface-image.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/image/class-base-image.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/senders/interface-sender.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/senders/class-base-sender.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/interface-recipient.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-collection.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-user.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-role.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/interface-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-base-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-aggregate-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/interface-message.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/class-base-message.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/interface-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/class-base-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/class-aggregate-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/persistence/interface-notification-repository.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/persistence/class-abstract-notification-repository.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/persistence/class-wpdb-notification-repository.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/demo.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/restapi/class-notification-controller.php';

new REST\Notification_Controller();

/**
 * Activation hook function of the WP Notification plugin.
 *
 * @return void
 *
 * @package wp-feature-notifications
 */
function wp_notifications_activation_hook() {
	require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-activator.php';
	Activator::activate();
}

register_activation_hook( __FILE__, 'wp_notifications_activation_hook' );

/**
 * Uninstall hook function of the WP Notification plugin.
 *
 * @return void
 *
 * @package wp-feature-notifications
 */
function wp_notifications_uninstall_hook() {
	require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-uninstaller.php';
	Uninstaller::uninstall();
}

register_uninstall_hook( __FILE__, 'wp_notifications_uninstall_hook' );

new REST\Notification_Controller();
