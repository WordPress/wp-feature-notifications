<?php

/**
 * Class WP_Notify_Composite_Notification
 *
 * Allows a notification to be extended with optional fields
 */
class WP_Notify_Composite_Notification extends WP_Notify_Base_Notification {

	const FIELD_TITLE       = 'title';
	const FIELD_IMAGE       = 'WP_Notify_Base_Image';
	const FIELD_ACTION_LINK = 'WP_Notify_Action_Link';

	/**
	 * Associative array, keys must match the class FIELD_* constants.
	 *
	 * @var array
	 */
	private $additional_fields = array();

	/**
	 * @param WP_Notify_Notification $notification
	 * @return WP_Notify_Composite_Notification
	 */
	public static function from_notification( $notification ) {
		return new self(
			$notification->get_sender(),
			$notification->get_recipients(),
			$notification->get_message(),
			$notification->get_timestamp(),
			$notification->get_id()
		);
	}

	public function add_field( $name, $value ) {
		$class = __CLASS__;
		if ( ! in_array( $name, WP_Notify_Composite_Notification::get_valid_fields(), true ) ) {
			throw new InvalidArgumentException( "'{$name}' is not a valid {$class} additional field type." );
		}

		$this->additional_fields[ $name ] = $value;
	}

	public function add_fields( $fields ) {
		foreach ( $fields as $name => $value ) {
			$this->add_field( $name, $value );
		}
	}

	public function get_field( $name ) {
		if ( array_key_exists( $name, $this->additional_fields ) ) {
			return $this->additional_fields[ $name ];
		}
	}

	public function jsonSerialize() {
		return array_merge(
			parent::jsonSerialize(),
			$this->additional_fields
		);
	}

	public static function json_unserialize( $json ) {
		$composite_notification = self::from_notification( parent::json_unserialize( $json ) );

		foreach ( array_intersect_key( json_decode( $json, true ), array_fill_keys( self::get_valid_fields(), null ) ) as $name => $value ) {
			if ( is_subclass_of( $name, WP_Notify_Json_Unserializable::class ) ) {
				$value = call_user_func( array( $name, 'json_unserialize' ), json_encode( $value ) );
			}

			$composite_notification->add_field( $name, $value );
		}

		return $composite_notification;
	}

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	protected static function get_valid_fields(): array {
		$reflection   = new ReflectionClass( __CLASS__ );
		$valid_fields = $reflection->getConstants();
		return $valid_fields;
	}
}
