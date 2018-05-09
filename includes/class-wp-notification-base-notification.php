<?php

class WP_Notification_Base_Notification implements WP_Notification {

	/**
	 * Collection of notification recipients.
	 *
	 * @var WP_Notification_Recipient_Collection
	 */
	protected $recipients;

	/**
	 * Notification message.
	 *
	 * @var WP_Notification_Message
	 */
	protected $message;

	/**
	 * Get the recipients for the notification.
	 *
	 * @return WP_Notification_Recipient_Collection Notification recipients.
	 */
	public function get_recipients() {
		return $this->recipients;
	}

	/**
	 * Get the message for the notification.
	 *
	 * @return WP_Notification_Message Notification message.
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * String representation of object
	 *
	 * @link  http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 * @since 5.1.0
	 */
	public function serialize() {
		return serialize( array(
			$this->recipients,
			$this->message,
		) );
	}

	/**
	 * Unserialize the notification.
	 *
	 * @param string $serialized The string representation of the notification.
	 *
	 * @return void
	 */
	public function unserialize( $serialized ) {
		list( $this->recipients, $this->message ) = unserialize(
			$serialized,
			$this->get_object_whitelist()
		);
	}

	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	public function jsonSerialize() {
		return wp_json_encode( array(
			'recipients' => $this->recipients,
			'message'    => $this->message,
		) );
	}

	/**
	 * Get the list of objects that can be unserialized from notification data.
	 *
	 * @return array Array of fully qualified class names.
	 */
	protected function get_object_whitelist() {
		$whitelist = apply_filters(
			'wp_notification_message_allowed_classes',
			array()
		);

		// Don't allow disabling the whitelist.
		if ( true === $whitelist ) {
			_doing_it_wrong(
				'wp_notification_message_allowed_classes',
				__(
					'Setting the "unserialize" whitelist to "true" is not allowed for security reasons.',
					'wp-notification-center'
				)
			);
		}

		// Only return arrays for consistency reasons.
		if ( false === $whitelist ) {
			return array();
		}

		return (array) $whitelist;
	}
}
