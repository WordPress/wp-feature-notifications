<?php

interface WP_Notification extends Serializable, JsonSerializable {

	/**
	 * Get the recipients for the notification.
	 *
	 * @return WP_Notification_Recipient_Collection Notification recipients.
	 */
	public function get_recipients();

	/**
	 * Get the message for the notification.
	 *
	 * @return WP_Notification_Message Notification message.
	 */
	public function get_message();
}
