<?php

class WP_Notify_Base_Sender implements WP_Notify_Sender, WP_Notify_Json_Unserializable {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var WP_Notify_Base_Image
	 */
	protected $image;

	/**
	 * WPNotify_BaseSender constructor.
	 *
	 * @param string                    $name
	 * @param WP_Notify_Base_Image|null $image
	 */
	public function __construct( $name, $image = null ) {
		$this->name  = $name;
		$this->image = $image;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {

		$data = array(
			'name' => $this->name,
		);

		if ( $this->image ) {
			$data[ get_class( $this->image ) ] = $this->image;
		}

		return $data;
	}

	/**
	 * Creates a new instance from JSON-encoded data.
	 *
	 * @param string $json JSON-encoded data to create the instance from.
	 *
	 * @return self
	 */
	public static function json_unserialize( $json ) {

		$data = json_decode( $json, true );

		reset( $data );
		$name = current( $data );

		next( $data );
		$class_name = key( $data );
		$image_data = current( $data );

		$image = null;

		if ( ! empty( $image_data ) && is_subclass_of( $class_name, 'WP_Notify_Image' ) ) {
			$image_reflection = new ReflectionClass( $class_name );
			$image            = $image_reflection->newInstanceArgs( array_values( $image_data ) );
		}

		return new self( $name, $image );
	}

	/**
	 * Gets the name of the sender
	 *
	 * @return string
	 */
	public function get_name() {

		return $this->name;
	}

	/**
	 * @return WP_Notify_Base_Image|null
	 */
	public function get_image() {
		return $this->image;
	}
}
