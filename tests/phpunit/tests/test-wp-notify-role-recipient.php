<?php

class Test_WP_Notify_Role_Recipient extends WPNotify_TestCase {

	public function test_it_can_be_instantiated() {
		$testee = new WP_Notify_Role_Recipient( 1 );
		$this->assertInstanceOf( 'WP_Notify_Role_Recipient', $testee );
	}

	public function test_it_implements_the_interface() {
		$testee = new WP_Notify_Role_Recipient( 1 );
		$this->assertInstanceOf( 'WP_Notify_Recipient', $testee );
	}
}
