<?php

interface WP_Notify_Sender_Factory {

	/**
	 * Create a new instance of notification sender
	 *
	 * @param string $name
	 *
	 * @return WP_Notify_Sender
	 */
	public function create( $name );
}
