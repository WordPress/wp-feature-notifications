<?php

class WP_Notify_Action_Link implements JsonSerializable, WP_Notify_Json_Unserializable {

	/**
	 * WP_Notify_Action_Link constructor.
	 *
	 * @param string $url
	 * @param string $text
	 */
	public function __construct( $url, $text ) {
		$this->url  = $url;
		$this->text = $text;
	}

	public function jsonSerialize() {
		return array(
			'url'  => $this->url,
			'text' => $this->text,
		);
	}

	public static function json_unserialize( $json ) {
		$data = json_decode( $json );
		return new WP_Notify_Action_Link( $data->url, $data->text );
	}
}
