<?php

namespace WP\Notifications\Tests;

use WP\Notifications;

class Test_Base_Notification extends TestCase {

	public function test_it_can_be_instantiated() {
		$sender_mock     = $this->createMock( '\WP\Notifications\Senders\Base_Sender' );
		$recipients_mock = $this->createMock( '\WP\Notifications\Recipients\Collection' );
		$testee          = new Notifications\Base_Notification(
			$sender_mock,
			$recipients_mock,
			new Dummy_Message()
		);
		$this->assertInstanceOf( '\WP\Notifications\Base_Notification', $testee );
	}

	public function test_it_implements_the_interface() {
		$sender_mock     = $this->createMock( '\WP\Notifications\Senders\Base_Sender' );
		$recipients_mock = $this->createMock( '\WP\Notifications\Recipients\Collection' );
		$testee          = new Notifications\Base_Notification(
			$sender_mock,
			$recipients_mock,
			new Dummy_Message()
		);
		$this->assertInstanceOf( '\WP\Notifications\Notification', $testee );
	}

	public function test_it_can_return_its_content() {
		$sender_mock     = $this->createMock( '\WP\Notifications\Senders\Base_Sender' );
		$recipients_mock = $this->createMock( '\WP\Notifications\Recipients\Collection' );
		$dummy_message   = new Dummy_Message();
		$testee          = new Notifications\Base_Notification(
			$sender_mock,
			$recipients_mock,
			$dummy_message
		);
		$this->assertEquals( $recipients_mock, $testee->get_recipients() );
		$this->assertEquals( $dummy_message, $testee->get_message() );
	}
}
