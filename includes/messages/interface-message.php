<?php

namespace WP\Notifications\Messages;

use JsonSerializable;

use WP\Notifications;

interface Message
	extends JsonSerializable,
	Notifications\Json_Unserializable {

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
