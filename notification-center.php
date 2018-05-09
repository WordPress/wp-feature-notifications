<?php

if ( ! defined( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR' ) ) {
	define( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR', dirname( __FILE__ ) );
}

// Require interface/class declarations..
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/interface-wp-notification-exception.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notification-runtime-exception.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notification-recipient.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notification-message.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notification.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notification-recipient-collection.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notification-base-message.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notification-base-notification.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notification-user-recipient.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notification-role-recipient.php';
require WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notification-message-factory.php';
