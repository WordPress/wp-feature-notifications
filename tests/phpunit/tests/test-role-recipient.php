<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Recipients\Role_Recipient;

class Test_Role_Recipient extends TestCase {

	public function test_it_can_be_instantiated() {
		$testee = new Role_Recipient( 1 );
		$this->assertInstanceOf( '\WP\Notifications\Recipients\Role_Recipient', $testee );
	}

	public function test_it_implements_the_interface() {
		$testee = new Role_Recipient( 1 );
		$this->assertInstanceOf( '\WP\Notifications\Recipients\Recipient', $testee );
	}
}
