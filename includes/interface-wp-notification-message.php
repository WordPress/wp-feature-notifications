<?php

interface WP_Notification_Message extends Serializable, JsonSerializable {

	/**
	 * Render the notification message.
	 *
	 * @param array $context Optional. Context data to use for rendering.
	 *
	 * @return string Rendered output.
	 */
	public function render( $context = array() );

}
