<?php

class Test_WP_Notification_Role_Recipient extends WP_Notification_Center_TestCase {

	public function test_it_can_be_instantiated() {
		$object = new WP_Notification_Role_Recipient( 1 );
		$this->assertInstanceOf( 'WP_Notification_Role_Recipient', $object );
	}

	public function test_it_implements_the_interface() {
		$object = new WP_Notification_Role_Recipient( 1 );
		$this->assertInstanceOf( 'WP_Notification_Recipient', $object );
	}
}
