<?php

class WPNotify_BaseMessage implements WPNotify_Message {

	/**
	 * Message content.
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * Instantiates a WPNotify_BaseMessage object.
	 *
	 * @param string $message Message content.
	 */
	public function __construct( $message ) {
		$this->message = $message;
	}

	/**
	 * Get the message content.
	 *
	 * @return string Message content.
	 */
	public function get_content() {
		return (string) $this->message;
	}

	/**
	 * Convert the message object into a string representing its content.
	 *
	 * @return string Message content as a string.
	 */
	public function __toString() {
		return $this->get_content();
	}

	/**
	 * Specifies data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode, which is a
	 *               value of any type other than a resource.
	 */
	public function jsonSerialize() {
		return $this->message;
	}

	/**
	 * Creates a new instance from JSON-encoded data.
	 *
	 * @param string $json JSON-encoded data to create the instance from.
	 *
	 * @return self
	 */
	public static function json_unserialize( $json ) {
		$message = json_decode( $json );

		return new self( $message );
	}
}
