<?php

class Test_WPNotify_BaseMessage extends WPNotify_TestCase {

	const SERIALIZED = 'C:20:"WPNotify_BaseMessage":14:{s:7:"Message";}';

	public function test_it_can_be_instantiated() {
		$testee = new WPNotify_BaseMessage( 'Message' );
		$this->assertInstanceOf( 'WPNotify_BaseMessage', $testee );
	}

	public function test_it_implements_the_interface() {
		$testee = new WPNotify_BaseMessage( 'Message' );
		$this->assertInstanceOf( 'WPNotify_Message', $testee );
	}

	public function test_it_can_return_its_content() {
		$testee = new WPNotify_BaseMessage( 'Message' );
		$this->assertEquals( 'Message', $testee->get_content() );
	}

	public function test_it_can_be_cast_to_string() {
		$testee = new WPNotify_BaseMessage( 'Message' );
		$this->assertEquals( 'Message', (string) $testee );
	}

	public function test_it_can_be_json_encoded() {
		$testee = new WPNotify_BaseMessage( 'Message' );
		$this->assertEquals(
			'"Message"',
			json_encode( $testee )
		);
	}

	public function test_it_can_be_instantiated_from_json() {
		$json   = '"Message"';
		$testee = WPNotify_BaseMessage::json_unserialize( $json );
		$this->assertInstanceOf( 'WPNotify_BaseMessage', $testee );
		$this->assertEquals( 'Message', $testee->get_content() );
	}
}
