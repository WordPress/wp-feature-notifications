<?php

/**
 * Plugin Name: WP Notify
 */

if ( ! defined( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR' ) ) {
	define( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR', dirname( __FILE__ ) );
}

// Require interface/class declarations..
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/interface-wp-notify-exception.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notify-runtime-exception.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notify-invalid-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notify-failed-to-add-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notify-json-unserializable.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notify-status.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/notifications/interface-wp-notify-notification.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notify-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notify-aggregate-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/notifications/class-wp-notify-base-notification.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR .
    '/includes/notifications/class-wp-notify-notification-decorator.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/notifications/class-wp-notify-notification-title.php';
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
