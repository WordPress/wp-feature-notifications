<?php

class WP_Notification_Base_Message implements WP_Notification_Message {

	protected $message;

	public function __construct( $message ) {
		$this->message = $message;
	}

	/**
	 * Render the notification message.
	 *
	 * @param array $context Optional. Context data to use for rendering.
	 *
	 * @return string Rendered output.
	 */
	public function render( $context = array() ) {
		return $this->message;
	}

	public function serialize() {
		return serialize( $this );
	}

	public function unserialize( $serialized ) {
		return unserialize( $serialized, $this->get_object_whitelist() );
	}

	public function jsonSerialize() {
		return wp_json_encode( $this );
	}

	protected function get_object_whitelist() {
		return apply_filters( 'wp_notification_message_allowed_classes',
			array() );
	}
}
