<?php

class Test_WPNotify_BaseNotification extends WPNotify_TestCase {

	const JSON_SERIALIZED = '{"WPNotify_RecipientCollection":[],"WPNotify_BaseMessage":"Message","WPNotify_BaseSender":{"name":"Name 1","image":null}}';

	public function test_it_can_be_instantiated() {
		$sender_mock     = $this->createMock( 'WPNotify_BaseSender' );
		$recipients_mock = $this->createMock( 'WPNotify_RecipientCollection' );
		$testee          = new WPNotify_BaseNotification(
			$sender_mock,
			$recipients_mock,
			new DummyMessage()
		);
		$this->assertInstanceOf( 'WPNotify_BaseNotification', $testee );
	}

	public function test_it_implements_the_interface() {
		$sender_mock     = $this->createMock( 'WPNotify_BaseSender' );
		$recipients_mock = $this->createMock( 'WPNotify_RecipientCollection' );
		$testee          = new WPNotify_BaseNotification(
			$sender_mock,
			$recipients_mock,
			new DummyMessage()
		);
		$this->assertInstanceOf( 'WPNotify_Notification', $testee );
	}

	public function test_it_can_return_its_content() {
		$sender_mock     = $this->createMock( 'WPNotify_BaseSender' );
		$recipients_mock = $this->createMock( 'WPNotify_RecipientCollection' );
		$dummy_message   = new DummyMessage();
		$testee          = new WPNotify_BaseNotification(
			$sender_mock,
			$recipients_mock,
			$dummy_message
		);
		$this->assertEquals( $recipients_mock, $testee->get_recipients() );
		$this->assertEquals( $dummy_message, $testee->get_message() );
	}

	public function test_it_can_be_json_encoded() {
		$sender_mock      = new WPNotify_BaseSender( 'Name 1' );
		$empty_recipients = new WPNotify_RecipientCollection();
		$message          = new WPNotify_BaseMessage( 'Message' );
		$testee           = new WPNotify_BaseNotification(
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
		$testee = WPNotify_BaseNotification::json_unserialize( self::JSON_SERIALIZED );
		$this->assertInstanceOf( 'WPNotify_BaseNotification', $testee );
		$this->assertInstanceOf( 'WPNotify_RecipientCollection',
			$testee->get_recipients() );
		$this->assertInstanceOf( 'WPNotify_BaseMessage',
			$testee->get_message() );
		$this->assertInstanceOf( 'WPNotify_BaseSender',
			$testee->get_sender() );
	}
}
