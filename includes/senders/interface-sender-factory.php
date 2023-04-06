<?php

namespace WP\Notifications\Senders;

interface Sender_Factory {

	/**
	 * Create a new instance of notification sender
	 *
	 * @param string $name
	 *
	 * @return Sender
	 */
	public function create( $name );
}
