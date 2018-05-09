<?php

class Test_WP_Notification_Base_Message extends WP_Notification_Center_TestCase {

	public function test_it_can_be_instantiated() {
		$object = new WP_Notification_Base_Message( 'Message' );
		$this->assertInstanceOf( 'WP_Notification_Base_Message', $object );
	}

	public function test_it_implements_the_interface() {
		$object = new WP_Notification_Base_Message( 'Message' );
		$this->assertInstanceOf( 'WP_Notification_Message', $object );
	}
}
