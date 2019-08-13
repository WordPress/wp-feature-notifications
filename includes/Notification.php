<?php

interface WPNotify_Notification extends JsonSerializable, WPNotify_JsonUnserializable {

	/**
	 * Get the ID of the notification.
	 *
	 * @return int ID of the notification, -1 if none was attributed yet.
	 */
	public function get_id();

	/**
	 * Get the timestamp of the notification.
	 *
	 * @return int Timestamp of the notification.
	 */
	public function get_timestamp();

	/**
	 * Get the sender of the notification.
	 *
	 * @return WPNotify_Sender Sender of the notification.
	 */
	public function get_sender();

	/**
	 * Get the recipients for the notification.
	 *
	 * @return WPNotify_RecipientCollection Notification recipients.
	 */
	public function get_recipients();

	/**
	 * Get the message for the notification.
	 *
	 * @return WPNotify_Message Notification message.
	 */
	public function get_message();
}
