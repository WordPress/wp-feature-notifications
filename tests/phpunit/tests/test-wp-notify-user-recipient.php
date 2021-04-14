<?php

class Test_WP_Notify_User_Recipient extends WPNotify_TestCase {

	public function test_it_can_be_instantiated() {
		$testee = new WP_Notify_User_Recipient( 1 );
		$this->assertInstanceOf( 'WP_Notify_User_Recipient', $testee );
	}

	public function test_it_implements_the_recipient_interface() {
		$testee = new WP_Notify_User_Recipient( 1 );
		$this->assertInstanceOf( 'WP_Notify_Recipient', $testee );
	}

	public function test_it_accepts_a_user_id_as_a_string() {
		$testee = new WP_Notify_User_Recipient( '1' );
		$this->assertInstanceOf( 'WP_Notify_Recipient', $testee );
	}

	/** @dataProvider data_provider_it_throws_on_invalid_type */
	public function test_it_throws_on_invalid_type( $invalid_user_id ) {
		$this->expectException('WP_Notify_Invalid_Recipient');
		new WP_Notify_User_Recipient( $invalid_user_id );
	}

	public function data_provider_it_throws_on_invalid_type() {
		return array(
			array( null ),
			array( - 1 ),
			array( 0 ),
			array( 'invalid' ),
			array( new stdClass ),
			array( array( 1, 2 ) ),
		);
	}
}
