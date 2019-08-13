<?php

class Test_WPNotify_RoleRecipient extends WPNotify_TestCase {

	public function test_it_can_be_instantiated() {
		$testee = new WPNotify_RoleRecipient( 1 );
		$this->assertInstanceOf( 'WPNotify_RoleRecipient', $testee );
	}

	public function test_it_implements_the_interface() {
		$testee = new WPNotify_RoleRecipient( 1 );
		$this->assertInstanceOf( 'WPNotify_Recipient', $testee );
	}
}
