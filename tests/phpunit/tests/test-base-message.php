<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Messages;

class Test_Base_Message extends TestCase {

	public function test_it_can_be_instantiated() {
		$testee = new Messages\Base_Message( 'Message' );
		$this->assertInstanceOf( '\WP\Notifications\Messages\Base_Message', $testee );
	}

	public function test_it_implements_the_interface() {
		$testee = new Messages\Base_Message( 'Message' );
		$this->assertInstanceOf( '\WP\Notifications\Messages\Message', $testee );
	}

	public function test_it_can_return_its_content() {
		$testee = new Messages\Base_Message( 'Message' );
		$this->assertEquals( 'Message', $testee->get_content() );
	}

	public function test_it_can_be_cast_to_string() {
		$testee = new Messages\Base_Message( 'Message' );
		$this->assertEquals( 'Message', (string) $testee );
	}
}
