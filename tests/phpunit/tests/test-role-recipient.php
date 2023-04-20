<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Recipients;

class Test_Role_Recipient extends TestCase {

	public function test_it_can_be_instantiated() {
		$testee = new Recipients\Role( 1 );
		$this->assertInstanceOf( '\WP\Notifications\Recipients\Role', $testee );
	}

	public function test_it_implements_the_interface() {
		$testee = new Recipients\Role( 1 );
		$this->assertInstanceOf( '\WP\Notifications\Recipients\Recipient', $testee );
	}
}
