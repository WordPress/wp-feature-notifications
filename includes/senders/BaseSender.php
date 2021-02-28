<?php

class WPNotify_BaseSender implements WPNotify_Sender, WPNotify_JsonUnserializable {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * WPNotify_BaseSender constructor.
	 *
	 * @param string $name
	 */
	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return array(
			'name' => $this->name,
		);
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

		$name = ! empty( $data['name'] ) ? $data['name'] : '';

		return new self( $name );
	}

	/**
	 * @return string
	 */
	public function get_name() {

		return $this->name;
	}
}
