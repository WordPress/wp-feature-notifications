<?php

class WPNotify_InvalidRecipient extends WPNotify_RuntimeException {

	public static function from_invalid_user_id( $user_id ) {
		$type  = is_object( $user_id ) ? get_class( $user_id ) : gettype( $user_id );
		$value = is_numeric( $user_id ) ? (int) $user_id : '<non-numeric>';

		$message = sprintf(
			__(
				'Notification user recipient of type "%1$s" and value "%2$s" did not validate as a valid user ID.',
				'wp-notification-center'
			),
			$type,
			$value
		);

		return new self( $message );
	}
}
