<?php

class WPNotify_BaseSender implements WPNotify_Sender, WPNotify_JsonUnserializable {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var WPNotify_BaseImage
	 */
	protected $image;

	/**
	 * WPNotify_BaseSender constructor.
	 *
	 * @param string                  $name
	 * @param WPNotify_BaseImage|null $image
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

		return array_filter( $data );
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

		if ( 'WPNotify_BaseImage' === $class_name && ! empty( $image_data ) ) {
			$image_reflection = new ReflectionClass( 'WPNotify_BaseImage' );
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
	 * @return WPNotify_BaseImage|null
	 */
	public function get_image() {
		return $this->image;
	}
}
