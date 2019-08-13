<?php

interface WPNotify_Message
	extends JsonSerializable,
	        WPNotify_JsonUnserializable {

	/**
	 * Get the message content.
	 *
	 * @return string Message content.
	 */
	public function get_content();

	/**
	 * Convert the message object into a string representing its content.
	 *
	 * @return string Message content as a string.
	 */
	public function __toString();
}
