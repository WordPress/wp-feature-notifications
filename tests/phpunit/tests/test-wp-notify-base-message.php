<?php

class Test_WP_Notify_Base_Message extends WP_Notify_TestCase {

	const SERIALIZED = 'C:20:"WPNotify_BaseMessage":14:{s:7:"Message";}';

	public function test_it_can_be_instantiated() {
		$testee = new WP_Notify_Base_Message( 'Message' );
		$this->assertInstanceOf( 'WP_Notify_Base_Message', $testee );
	}

	public function test_it_implements_the_interface() {
		$testee = new WP_Notify_Base_Message( 'Message' );
		$this->assertInstanceOf( 'WP_Notify_Message', $testee );
	}

	public function test_it_can_return_its_content() {
		$testee = new WP_Notify_Base_Message( 'Message' );
		$this->assertEquals( 'Message', $testee->get_content() );
	}

	public function test_it_can_be_cast_to_string() {
		$testee = new WP_Notify_Base_Message( 'Message' );
		$this->assertEquals( 'Message', (string) $testee );
	}

	public function test_it_can_be_json_encoded() {
		$testee = new WP_Notify_Base_Message( 'Message' );
		$this->assertEquals(
			'"Message"',
			json_encode( $testee )
		);
	}

	public function test_it_can_be_instantiated_from_json() {
		$json   = '"Message"';
		$testee = WP_Notify_Base_Message::json_unserialize( $json );
		$this->assertInstanceOf( 'WP_Notify_Base_Message', $testee );
		$this->assertEquals( 'Message', $testee->get_content() );
	}
}
