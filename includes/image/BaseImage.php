<?php

class WPNotify_BaseImage implements WPNotify_Image, WPNotify_JsonUnserializable {

	/**
	 * It can be and URL of an image file or and data/image source
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

		return array(
			'source' => $this->source,
			'alt'    => $this->alt
		);
	}

	public static function json_unserialize( $json ) {
		// TODO: Implement json_unserialize() method.
	}
}
