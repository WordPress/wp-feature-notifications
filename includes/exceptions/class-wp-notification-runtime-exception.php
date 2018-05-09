<?php

class WP_Notification_Runtime_Exception extends RuntimeException implements WP_Notification_Exception {

	public static function from_invalid_recipient_collection_addition( $recipient ) {
		$type = is_object( $recipient ) ? get_class( $recipient ) : gettype( $recipient );

		$message = sprintf( __(
			'Failed to add invalid recipient type "%s" to recipient collection, only implementations of interface "WP_Notification_Recipient" allowed.',
			'wp-notification-center'
		), $type );

		return new self( $message );
	}
}
