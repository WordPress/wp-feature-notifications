<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Base_Notification;
use WP\Notifications\Messages\Base_Message;
use WP\Notifications\Recipients\Recipient_Collection;
use WP\Notifications\Senders\Base_Sender;

class Test_Base_Notification extends TestCase {

	const JSON_SERIALIZED = '{"Recipient_Collection":[],"Base_Message":"Message","Base_Sender":{"name":"Name 1"}}';

	public function test_it_can_be_instantiated() {
		$sender_mock     = $this->createMock( '\WP\Notifications\Senders\Base_Sender' );
		$recipients_mock = $this->createMock( '\WP\Notifications\Recipients\Recipient_Collection' );
		$testee          = new Base_Notification(
			$sender_mock,
			$recipients_mock,
			new Dummy_Message()
		);
		$this->assertInstanceOf( '\WP\Notifications\Base_Notification', $testee );
	}

	public function test_it_implements_the_interface() {
		$sender_mock     = $this->createMock( '\WP\Notifications\Senders\Base_Sender' );
		$recipients_mock = $this->createMock( '\WP\Notifications\Recipients\Recipient_Collection' );
		$testee          = new Base_Notification(
			$sender_mock,
			$recipients_mock,
			new Dummy_Message()
		);
		$this->assertInstanceOf( '\WP\Notifications\Notification', $testee );
	}

	public function test_it_can_return_its_content() {
		$sender_mock     = $this->createMock( '\WP\Notifications\Senders\Base_Sender' );
		$recipients_mock = $this->createMock( '\WP\Notifications\Recipients\Recipient_Collection' );
		$dummy_message   = new Dummy_Message();
		$testee          = new Base_Notification(
			$sender_mock,
			$recipients_mock,
			$dummy_message
		);
		$this->assertEquals( $recipients_mock, $testee->get_recipients() );
		$this->assertEquals( $dummy_message, $testee->get_message() );
	}

	public function test_it_can_be_json_encoded() {
		$sender_mock      = new Base_Sender( 'Name 1' );
		$empty_recipients = new Recipient_Collection();
		$message          = new Base_Message( 'Message' );
		$testee           = new Base_Notification(
			$sender_mock,
			$empty_recipients,
			$message
		);
		$this->assertEquals(
			self::JSON_SERIALIZED,
			json_encode( $testee )
		);
	}

	// TODO The test below is problematic, the whole concept of serializing a message
	// which is to be directly initialized as a class is probably not the right direction
	// and also not how the json api schema would be structured.

	// public function test_it_can_be_instantiated_from_json() {
	// 	$testee = Base_Notification::json_unserialize( self::JSON_SERIALIZED );
	// 	$this->assertInstanceOf( '\WP\Notifications\Base_Notification', $testee );
	// 	$this->assertInstanceOf(
	// 		'\WP\Notifications\Recipients\Recipient_Collection',
	// 		$testee->get_recipients()
	// 	);
	// 	$this->assertInstanceOf(
	// 		'\WP\Notifications\Messages\Base_Message',
	// 		$testee->get_message()
	// 	);
	// 	$this->assertInstanceOf(
	// 		'\WP\Notifications\Senders\Base_Sender',
	// 		$testee->get_sender()
	// 	);
	// }
}
