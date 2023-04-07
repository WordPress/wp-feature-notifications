<?php

namespace WP\Notifications\Image;

class Base_Image implements Image {

	/**
	 * It can be and URL of an image file or and data/image source
	 *
	 * @var string source for "src" attribute of an <img/> html node
	 */
	protected $source;

	/**
	 * @var string for alternative text of an <img/> html node
	 */
	protected $alt = '';

	/**
	 * BaseImage constructor.
	 *
	 * @param string $source
	 * @param string $alt
	 */
	public function __construct( $source, $alt = '' ) {
		$this->source = $source;
		$this->alt    = $alt;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {

		$data = array();

		if ( ! empty( $this->get_source() ) ) {
			$data['source'] = $this->get_source();
		}

		if ( ! empty( $this->get_alt() ) ) {
			$data['alt'] = $this->get_alt();
		}

		return $data;
	}

	/**
	 * Source of the image to be used on src attribute of <img/>
	 *
	 * @return string
	 */
	public function get_source() {
		return $this->source;
	}

	/**
	 * Alternative text to be used on alt attribute of <img/>
	 *
	 * @return string
	 */
	public function get_alt() {
		return $this->alt;
	}

	/**
	 * Instantiates a BaseImage based on a JSON string
	 *
	 * @param string $json
	 *
	 * @return Json_Unserializable
	 */
	public static function json_unserialize( $json ) {

		$data = json_decode( $json, true );

		$source = ! empty( $data['source'] ) ? $data['source'] : '';
		$alt    = ! empty( $data['alt'] ) ? $data['alt'] : '';

		return new self( $source, $alt );
	}
}
