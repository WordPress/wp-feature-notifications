<?php

class WPNotify_FailedToAddRecipient extends WPNotify_RuntimeException {

	public static function from_invalid_recipient(
		$recipient
	) {
		$type = is_object( $recipient ) ? get_class( $recipient ) : gettype( $recipient );

		$message = sprintf( __(
			'Failed to add invalid recipient type "%s" to recipient collection, only implementations of interface "WPNotify_Recipient" allowed.',
			'wp-notification-center'
		), $type );

		return new self( $message );
	}
}
