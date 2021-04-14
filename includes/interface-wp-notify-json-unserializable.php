<?php

interface WP_Notify_Json_Unserializable {

	/**
	 * Creates a new instance from JSON-encoded data.
	 *
	 * @param string $json JSON-encoded data to create the instance from.
	 *
	 * @return self
	 */
	public static function json_unserialize( $json );
}
