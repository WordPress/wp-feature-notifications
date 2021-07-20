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

	/**
	 * @dataProvider json_encoded_notifications_provider
	 * @param string $field_name
	 * @param mixed $field_value
	 * @param string $json_file
	 */
	public function test_it_can_be_json_encoded_with_additional_fields( $field_name, $field_value, $json_file ) {
		$this->composite_notification->add( $field_name, $field_value );

		$result = json_encode( $this->composite_notification );

		$this->assertJsonStringEqualsJsonFile(
			$json_file,
			$result
		);
	}

	/**
	 * @dataProvider json_encoded_notifications_provider
	 * @param string $field_name
	 * @param mixed $field_value
	 * @param string $json_file
	 */
	public function test_it_can_be_instantiated_from_json_with_additonal_fields( $field_name, $field_value, $json_file ) {
		$result = WP_Notify_Composite_Notification::json_unserialize( file_get_contents( $json_file ) );

		$this->assertInstanceOf( WP_Notify_Composite_Notification::class, $result );
		$this->assertEquals( 'WordPress', $result->get_sender()->get_name() );
		$this->assertInstanceOf( WP_Notify_Recipient_Collection::class, $result->get_recipients() );
		$this->assertEquals( 'WordPress was successfully updated to version 5.6.', $result->get_message()->get_content() );
		$this->assertEquals( $field_value, $result->get_field( $field_name ) );
	}

	public function json_encoded_notifications_provider() {
		return array(
			array(
				'field_name'  => WP_Notify_Composite_Notification::FIELD_TITLE,
				'field_value' => 'WordPress',
				'json_file'   => WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-title.json',
			),
			array(
				'field_name'  => WP_Notify_Composite_Notification::FIELD_IMAGE,
				'field_value' => new WP_Notify_Base_Image( '/path/to/my/image.jpg' ),
				'json_file'   => WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-image.json',
			),
			array(
				'field_name'  => WP_Notify_Composite_Notification::FIELD_ACTION_LINK,
				'field_value' => new WP_Notify_Action_Link( '#', "Read what's new in 5.6" ),
				'json_file'   => WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-action-link.json',
			),
		);
	}
}
