<?php

namespace WP\Notifications\Recipients;

interface Recipient_Factory {

	/**
	 * Create a new instance of a notification recipient.
	 *
	 * @param mixed  $value Value of the recipient.
	 * @param string $type  Optional. Type of the recipient. Defaults to 'user'.
	 *
	 * @return Recipient
	 */
	public function create( $value, $type = 'user' );

	/**
	 * Whether the factory accepts a given type for instantiation.
	 *
	 * @param string $type Type that should be instantiated.
	 *
	 * @return bool Whether the factory accepts the given type.
	 */
	public function accepts( $type );
}
