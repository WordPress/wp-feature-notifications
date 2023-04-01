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
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package wp-feature-notifications
 */

use WP\Notifications\REST;

if ( ! defined( 'WP_NOTIFICATION_CENTER_PLUGIN_VERSION' ) ) {
	define( 'WP_NOTIFICATION_CENTER_PLUGIN_VERSION', '0.0.1' );
}

if ( ! defined( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR' ) ) {
	define( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR', dirname( __FILE__ ) );
}

if ( ! defined( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR_URL' ) ) {
	define( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

require __DIR__ . '/vendor/autoload.php';

// Require interface/class declarations..
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/interface-wp-notify-exception.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notify-runtime-exception.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notify-invalid-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notify-failed-to-add-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notify-json-unserializable.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notify-status.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notify-notification.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notify-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notify-aggregate-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notify-base-notification.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/image/interface-wp-notify-image.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/image/class-wp-notify-base-image.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/senders/interface-wp-notify-sender.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/senders/class-wp-notify-base-sender.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/interface-wp-notify-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-recipient-collection.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-user-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-role-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/interface-wp-notify-recipient-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-base-recipient-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-aggregate-recipient-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/interface-wp-notify-message.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/class-wp-notify-base-message.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/interface-wp-notify-message-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/class-wp-notify-base-message-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/class-wp-notify-aggregate-message-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/persistence/interface-wp-notify-notification-repository.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/persistence/class-wp-notify-abstract-notification-repository.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/persistence/class-wp-notify-wpdb-notification-repository.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/demo.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/restapi/class-notification-controller.php';

// TODO: Standardise structure and/or autoloading.
new REST\Notification_Controller();
