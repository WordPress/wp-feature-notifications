<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Messages\Base_Message;

class Test_Base_Message extends TestCase {

	const SERIALIZED = 'C:20:"WPNotify_BaseMessage":14:{s:7:"Message";}';

	public function test_it_can_be_instantiated() {
		$testee = new Base_Message( 'Message' );
		$this->assertInstanceOf( '\WP\Notifications\Messages\Base_Message', $testee );
	}

	public function test_it_implements_the_interface() {
		$testee = new Base_Message( 'Message' );
		$this->assertInstanceOf( '\WP\Notifications\Messages\Message', $testee );
	}

	public function test_it_can_return_its_content() {
		$testee = new Base_Message( 'Message' );
		$this->assertEquals( 'Message', $testee->get_content() );
	}

	public function test_it_can_be_cast_to_string() {
		$testee = new Base_Message( 'Message' );
		$this->assertEquals( 'Message', (string) $testee );
	}

	public function test_it_can_be_json_encoded() {
		$testee = new Base_Message( 'Message' );
		$this->assertEquals(
			'"Message"',
			json_encode( $testee )
		);
	}

	public function test_it_can_be_instantiated_from_json() {
		$json   = '"Message"';
		$testee = Base_Message::json_unserialize( $json );
		$this->assertInstanceOf( '\WP\Notifications\Messages\Base_Message', $testee );
		$this->assertEquals( '\WP\Notifications\Messages\Message', $testee->get_content() );
	}
}
