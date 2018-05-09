<?php

class Test_WP_Notification_Base_Notification extends WP_Notification_Center_TestCase {

	public function test_it_can_be_instantiated() {
		$object = new WP_Notification_Base_Notification();
		$this->assertInstanceOf( 'WP_Notification_Base_Notification', $object );
	}

	public function test_it_implements_the_interface() {
		$object = new WP_Notification_Base_Notification();
		$this->assertInstanceOf( 'WP_Notification', $object );
	}
}
