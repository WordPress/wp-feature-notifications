<?php

/**
 * Plugin Name: WP Notify
 */

if ( ! defined( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR' ) ) {
	define( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR', dirname( __FILE__ ) );
}

// Require interface/class declarations..
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/Exception.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/RuntimeException.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/InvalidRecipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/FailedToAddRecipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/JsonUnserializable.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/Status.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/Notification.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/Factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/AggregateFactory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/BaseNotification.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/image/Image.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/image/BaseImage.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/senders/Sender.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/senders/BaseSender.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/Recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/RecipientCollection.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/UserRecipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/RoleRecipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/RecipientFactory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/BaseRecipientFactory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/AggregateRecipientFactory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/Message.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/BaseMessage.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/MessageFactory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/BaseMessageFactory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/AggregateMessageFactory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/persistence/NotificationRepository.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/persistence/AbstractNotificationRepository.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/persistence/WpdbNotificationRepository.php';
