<?php


class Test_WP_Notify_Composite_Notification extends WPNotify_TestCase {

	/**
	 * @var WP_Notify_Composite_Notification
	 */
	private $composite_notification;

	public function setUp() {
		parent::setUp();

		$this->composite_notification = new WP_Notify_Composite_Notification(
			new WP_Notify_Base_Sender( 'WordPress' ),
			new WP_Notify_Recipient_Collection(),
			new WP_Notify_Base_Message( 'WordPress was successfully updated to version 5.6.' )
		);
	}

	public function test_it_can_be_json_encoded_with_a_title() {
		$this->composite_notification->add( WP_Notify_Composite_Notification::FIELD_TITLE, 'WordPress' );

		$result = json_encode( $this->composite_notification );

		$this->assertJsonStringEqualsJsonFile(
			WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-title.json',
			$result
		);
	}

	public function test_it_can_be_instantiated_from_json_with_a_title() {
		$this->composite_notification->add(
			WP_Notify_Composite_Notification::FIELD_TITLE,
			'WordPress'
		);

		$testee = WP_Notify_Composite_Notification::json_unserialize( file_get_contents( WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-title.json' ) );

		$this->assertInstanceOf( WP_Notify_Notification::class, $testee );
		$this->assertEquals( 'WordPress', $testee->get_sender()->get_name() );
		$this->assertInstanceOf( WP_Notify_Recipient_Collection::class, $testee->get_recipients() );
		$this->assertEquals( 'WordPress was successfully updated to version 5.6.', $testee->get_message()->get_content() );
		$this->assertEquals( 'WordPress', $testee->get_field( WP_Notify_Composite_Notification::FIELD_TITLE ) );
	}
}
