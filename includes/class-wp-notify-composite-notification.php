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
		$class        = __CLASS__;
		$reflection   = new ReflectionClass( $class );
		$valid_fields = $reflection->getConstants();
		if ( ! in_array( $name, $valid_fields, true ) ) {
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

		foreach ( json_decode( $json ) as $name => $value ) {
			if ( WP_Notify_Composite_Notification::FIELD_TITLE === $name ) {
				$composite_notification->add_field( $name, $value );
			} elseif ( WP_Notify_Composite_Notification::FIELD_IMAGE === $name ) {
				$composite_notification->add_field( $name, WP_Notify_Base_Image::json_unserialize( json_encode( $value ) ) );
			} elseif ( WP_Notify_Composite_Notification::FIELD_ACTION_LINK === $name ) {
				$composite_notification->add_field( $name, WP_Notify_Action_Link::json_unserialize( json_encode( $value ) ) );
			}
		}

		return $composite_notification;
	}
}
