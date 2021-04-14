<?php

interface WP_Notify_Message
	extends JsonSerializable,
			WP_Notify_Json_Unserializable {

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
