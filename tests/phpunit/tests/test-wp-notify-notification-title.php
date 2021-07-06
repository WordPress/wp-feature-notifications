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

	public function test_it_can_be_extended_with_a_title() {
		$testee = new WP_Notify_Notification_Title( $this->base_notification, 'WordPress' );

		$result = json_encode( $testee );

		$this->assertJsonStringEqualsJsonFile( WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-title.json', $result );
	}

	// test_it_can_be_unserialized_with_a_title

	// test_it_can_be_extended_with_an_action_link
}
