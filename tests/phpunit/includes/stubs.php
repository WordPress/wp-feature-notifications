<?php

function __( $text, $domain ) {
	return $text;
}

function _doing_it_wrong( $function, $message ) {
	throw new Exception( "{$function} - {$message}" );
}

function apply_filters( $filter, $value ) {
	return $value;
}

class DummyMessage implements WPNotify_Message {

	public function serialize() { return ''; }

	public function unserialize( $serialized ) { }

	public function jsonSerialize() { return ''; }

	public static function json_unserialize( $json ) { return new self; }

	public function get_content() { return ''; }

	public function __toString() { return ''; }
}
