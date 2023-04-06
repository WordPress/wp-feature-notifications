<?php

class WP_Notify_Base_Message_Factory implements WP_Notify_Message_Factory {

	const TYPE_STANDARD = 'standard';

	/**
	 * Create a new instance of a notification message.
	 *
	 * @param mixed  $value Value of the message.
	 * @param string $type  Optional. Type of the message. Defaults to
	 *                      'standard'.
	 *
	 * @return WP_Notify_Message
	 *
	 * @throws WP_Notify_Invalid_Type If the message type was not valid.
	 */
	public function create( $value, $type = 'standard' ) {
		if ( ! $this->accepts( $type ) ) {
			throw WP_Notify_Invalid_Type::from_message_type( $type );
		}

		list( $type, $value ) = $this->validate( $type, $value );

		switch ( $type ) {
			case self::TYPE_STANDARD:
			default:
				return new WP_Notify_Base_Message( $value );
		}
	}

	/**
	 * Whether the factory accepts a given type for instantiation.
	 *
	 * @param string $type Type that should be instantiated.
	 *
	 * @return bool Whether the factory accepts the given type.
	 */
	public function accepts( $type ) {
		$accepted_types = array( self::TYPE_STANDARD );

		return in_array( $type, $accepted_types, true );
	}

	/**
	 * Validate provided arguments.
	 *
	 * @param string $type  Type of the message to create.
	 * @param mixed  $value Value of the message to create.
	 *
	 * @return array
	 */
	private function validate( $type, $value ) {
		// TODO: Validate arguments.

		return array( $type, $value );
	}
}
