<?php

namespace WP\Notifications\Exceptions;

class Failed_To_Add_Recipient extends Runtime_Exception {

	public static function from_invalid_recipient(
		$recipient
	) {
		$type = is_object( $recipient ) ? get_class( $recipient ) : gettype( $recipient );

		$message = sprintf(
			/* translators: "%s" is a type of recipient. */
			__(
				'Failed to add invalid recipient type "%s" to recipient collection, only implementations of interface "Recipient" allowed.',
				'wp-notification-center'
			),
			$type
		);

		return new self( $message );
	}
}
