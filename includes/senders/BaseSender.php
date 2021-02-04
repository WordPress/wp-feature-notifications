<?php

class WPNotify_BaseSender implements WPNotify_Sender {

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
}
