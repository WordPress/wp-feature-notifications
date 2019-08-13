<?php

class WPNotify_InvalidType extends WPNotify_RuntimeException {

	public static function from_message_type( $type ) {
		$message = sprintf(
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
			__(
				'Could not create notification recipient for the invalid recipient type "%1$s".',
				'wp-notification-center'
			),
			$type
		);

		return new self( $message );
	}
}
