<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Messages\Message;

class Dummy_Message implements Message {

	public function serialize() {
		return ''; }

	public function unserialize( $serialized ) { }

	public function jsonSerialize() {
		return ''; }

	public static function json_unserialize( $json ) {
		return new self; }

	public function get_content() {
		return ''; }

	public function __toString() {
		return ''; }
}
