<?php

final class WPNotify_BaseRecipientFactory implements WPNotify_RecipientFactory {

	const TYPE_USER = 'user';
	const TYPE_ROLE = 'role';

	/**
	 * Create a new instance of a notification recipient.
	 *
	 * @param mixed  $value Value of the recipient.
	 * @param string $type  Optional. Type of the recipient. Defaults to 'user'.
	 *
	 * @return WPNotify_Recipient
	 *
	 * @throws WPNotify_InvalidType If the recipient type was not valid.
	 */
	public function create( $value, $type = self::TYPE_USER ) {
		if ( ! $this->accepts( $type ) ) {
			throw WPNotify_InvalidType::from_recipient_type( $type );
		}

		list( $type, $value ) = $this->validate( $type, $value );

		$class = $this->get_implementation_for_type( $type );

		return new $class( $value );
	}

	/**
	 * Whether the factory accepts a given type for instantiation.
	 *
	 * @param string $type Type that should be instantiated.
	 *
	 * @return bool Whether the factory accepts the given type.
	 */
	public function accepts( $type ) {
		return in_array( $type, $this->get_accepted_types(), true );
	}

	/**
	 * Get the corresponding implementation class for a given type.
	 *
	 * @param string $type Type to get the implementation class for.
	 *
	 * @return string Implementation class.
	 * @throws WPNotify_InvalidType If the recipient type was not valid.
	 */
	public function get_implementation_for_type( $type ) {
		if ( ! $this->accepts( $type ) ) {
			throw WPNotify_InvalidType::from_recipient_type( $type );
		}

		$mappings = $this->get_type_mappings();

		return $mappings[ $type ];
	}

	/**
	 * Validate provided arguments.
	 *
	 * @param string $type  Type of the recipient to create.
	 * @param mixed  $value Value of the recipient to create.
	 *
	 * @return array Validated arguments.
	 */
	private function validate( $type, $value ) {
		// TODO: Validate arguments.

		return array( $type, $value );
	}

	/**
	 * Get an array of type to class mappings.
	 *
	 * @return array
	 */
	private function get_type_mappings() {
		return apply_filters(
			'wp_notify_recipient_type_mappings',
			array(
				self::TYPE_USER => 'WPNotify_UserRecipient',
				self::TYPE_ROLE => 'WPNotify_RoleRecipient',
			)
		);
	}

	/**
	 * Get an array of accepted type identifiers.
	 *
	 * @return array
	 */
	private function get_accepted_types() {
		return array_keys( $this->get_type_mappings() );
	}
}
