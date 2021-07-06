<?php

class Test_WP_Notify_Notification_Decorators extends WPNotify_TestCase {

	/**
	 * @var WP_Notify_Base_Notification
	 */
	private $base_notification;

	public function setUp() {
		parent::setUp();

		$this->base_notification = new WP_Notify_Base_Notification(
			new WP_Notify_Base_Sender( 'WordPress' ),
			new WP_Notify_Recipient_Collection(
				array(
					new WP_Notify_Role_Recipient( 'admin' ),
				)
			),
			new WP_Notify_Base_Message( 'WordPress was successfully updated to version 5.6.' )
		);
	}

	public function test_it_can_be_serialized_with_a_title() {
		$testee = new WP_Notify_Notification_Title( $this->base_notification, 'WordPress' );

		$result = json_encode( $testee );

		$this->assertJsonStringEqualsJsonFile( WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-title.json', $result );
	}

	// test_it_can_be_unserialized_with_a_title

	public function test_it_can_be_serialized_with_an_action_link() {
		$testee = new WP_Notify_Notification_Action_Link(
			$this->base_notification,
			new WP_Notify_Action_Link( '#', "Read what's new in 5.6" )
		);

		$result = json_encode( $testee );

		$this->assertJsonStringEqualsJsonFile( WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-action-link.json', $result );
	}

	// test_it_can_be_unserialized_with_an_action_link

	public function test_it_can_be_serialized_with_an_image() {
		$testee = new WP_Notify_Notification_Image(
			$this->base_notification,
			new WP_Notify_Base_Image( WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/wordpress-watermark.png' )
		);

		$result = json_encode( $testee );

		$this->assertJsonStringEqualsJsonFile(
			WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-image.json',
			$result
		);
	}

	// test_it_can_be_unserialized_with_an_action_link

	public function test_it_can_be_serialized_with_an_image_a_title_and_an_action_link() {
		$testee = new WP_Notify_Notification_Image(
			new WP_Notify_Notification_Title(
				new WP_Notify_Notification_Action_Link(
					$this->base_notification,
					new WP_Notify_Action_Link( '#', "Read what's new in 5.6" )
				),
				'WordPress'
			),
			new WP_Notify_Base_Image( WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/wordpress-watermark.png' )
		);

		$result = json_encode( $testee );

		$this->assertJsonStringEqualsJsonFile(
			WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-image-title-link.json',
			$result
		);
	}

	// test_it_can_be_unserialized_with_an_image_a_title_and_an_action_link
}
