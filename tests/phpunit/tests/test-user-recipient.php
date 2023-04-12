<?php

namespace WP\Notifications\Tests;

use stdClass;

use WP\Notifications\Recipients;

class Test_User_Recipient extends TestCase {

	public function test_it_can_be_instantiated() {
		$testee = new Recipients\User( 1 );
		$this->assertInstanceOf( '\WP\Notifications\Recipients\User', $testee );
	}

	public function test_it_implements_the_recipient_interface() {
		$testee = new Recipients\User( 1 );
		$this->assertInstanceOf( '\WP\Notifications\Recipients\Recipient', $testee );
	}

	public function test_it_accepts_a_user_id_as_a_string() {
		$testee = new Recipients\User( '1' );
		$this->assertInstanceOf( '\WP\Notifications\Recipients\Recipient', $testee );
	}

	/** @dataProvider data_provider_it_throws_on_invalid_type */
	public function test_it_throws_on_invalid_type( $invalid_user_id ) {
		$this->expectException( '\WP\Notifications\Exceptions\Invalid_Recipient' );
		new Recipients\User( $invalid_user_id );
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
