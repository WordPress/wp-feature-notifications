<?php

class WP_Notify_Invalid_Type extends WP_Notify_Runtime_Exception {

	public static function from_message_type( $type ) {
		$message = sprintf(
			/* translators: "%1$s" is a type of message. */
			__(
				'Could not create notification message for the invalid message type "%1$s".',
				'wp-notification-center'
			),
			$type
		);

		return new self( $message );
	}

	public static function from_recipient_type( $type ) {
		$message = sprintf(
			/* translators: "%1$s" is a type of recipient. */
			__(
				'Could not create notification recipient for the invalid recipient type "%1$s".',
				'wp-notification-center'
			),
			$type
		);

		return new self( $message );
	}
}
