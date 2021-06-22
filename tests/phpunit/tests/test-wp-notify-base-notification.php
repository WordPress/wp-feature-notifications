<?php

class Test_WP_Notify_Base_Notification extends WP_Notify_TestCase {

	const JSON_SERIALIZED = '{"WP_Notify_Recipient_Collection":[],"WP_Notify_Base_Message":"Message","WP_Notify_Base_Sender":{"name":"Name 1"}}';

	public function test_it_can_be_instantiated() {
		$sender_mock     = $this->createMock( 'WP_Notify_Base_Sender' );
		$recipients_mock = $this->createMock( 'WP_Notify_Recipient_Collection' );
		$testee          = new WP_Notify_Base_Notification(
			$sender_mock,
			$recipients_mock,
			new Dummy_Message()
		);
		$this->assertInstanceOf( 'WP_Notify_Base_Notification', $testee );
	}

	public function test_it_implements_the_interface() {
		$sender_mock     = $this->createMock( 'WP_Notify_Base_Sender' );
		$recipients_mock = $this->createMock( 'WP_Notify_Recipient_Collection' );
		$testee          = new WP_Notify_Base_Notification(
			$sender_mock,
			$recipients_mock,
			new Dummy_Message()
		);
		$this->assertInstanceOf( 'WP_Notify_Notification', $testee );
	}

	public function test_it_can_return_its_content() {
		$sender_mock     = $this->createMock( 'WP_Notify_Base_Sender' );
		$recipients_mock = $this->createMock( 'WP_Notify_Recipient_Collection' );
		$dummy_message   = new Dummy_Message();
		$testee          = new WP_Notify_Base_Notification(
			$sender_mock,
			$recipients_mock,
			$dummy_message
		);
		$this->assertEquals( $recipients_mock, $testee->get_recipients() );
		$this->assertEquals( $dummy_message, $testee->get_message() );
	}

	public function test_it_can_be_json_encoded() {
		$sender_mock      = new WP_Notify_Base_Sender( 'Name 1' );
		$empty_recipients = new WP_Notify_Recipient_Collection();
		$message          = new WP_Notify_Base_Message( 'Message' );
		$testee           = new WP_Notify_Base_Notification(
			$sender_mock,
			$empty_recipients,
			$message
		);
		$this->assertEquals(
			self::JSON_SERIALIZED,
			json_encode( $testee )
		);
	}

	public function test_it_can_be_instantiated_from_json() {
		$testee = WP_Notify_Base_Notification::json_unserialize( self::JSON_SERIALIZED );
		$this->assertInstanceOf( 'WP_Notify_Base_Notification', $testee );
		$this->assertInstanceOf(
			'WP_Notify_Recipient_Collection',
			$testee->get_recipients()
		);
		$this->assertInstanceOf(
			'WP_Notify_Base_Message',
			$testee->get_message()
		);
		$this->assertInstanceOf(
			'WP_Notify_Base_Sender',
			$testee->get_sender()
		);
	}
}
