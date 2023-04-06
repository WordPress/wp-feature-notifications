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

if ( ! defined( 'WP_FEATURE_NOTIFICATION_PLUGIN_VERSION' ) ) {
	define( 'WP_FEATURE_NOTIFICATION_PLUGIN_VERSION', '0.0.1' );
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
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-recipient-collection.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-user-recipient.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-role-recipient.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/interface-recipient-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-base-recipient-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/recipients/class-aggregate-recipient-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/interface-message.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/class-base-message.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/interface-message-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/class-base-message-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/messages/class-aggregate-message-factory.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/persistence/interface-notification-repository.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/persistence/class-abstract-notification-repository.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/persistence/class-wpdb-notification-repository.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/demo.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/restapi/class-notification-controller.php';

new REST\Notification_Controller();
