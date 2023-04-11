<?php

namespace WP\Notifications\Messages;

interface Factory {

	/**
	 * Create a new instance of a notification message.
	 *
	 * @param mixed  $value Value of the message.
	 * @param string $type  Optional. Type of the message. Defaults to
	 *                      'standard'.
	 *
	 * @return Message
	 */
	public function create( $value, $type = 'standard' );

	/**
	 * Whether the factory accepts a given type for instantiation.
	 *
	 * @param string $type Type that should be instantiated.
	 *
	 * @return bool Whether the factory accepts the given type.
	 */
	public function accepts( $type );
}
