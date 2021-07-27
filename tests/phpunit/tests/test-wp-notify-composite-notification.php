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
	 * @param array $fields Associative array of fields to add.
	 * @param string $json_file
	 */
	public function test_it_can_be_json_encoded_with_additional_fields( $fields, $json_file ) {
		$this->composite_notification->add_fields( $fields );

		$result = json_encode( $this->composite_notification );

		$this->assertJsonStringEqualsJsonFile(
			$json_file,
			$result
		);
	}

	/**
	 * @dataProvider json_encoded_notifications_provider
	 * @param array $fields Associative array of fields to add.
	 * @param string $json_file
	 */
	public function test_it_can_be_instantiated_from_json_with_additonal_fields( $fields, $json_file ) {
		$result = WP_Notify_Composite_Notification::json_unserialize( file_get_contents( $json_file ) );

		$this->assertInstanceOf( WP_Notify_Composite_Notification::class, $result );
		$this->assertEquals( 'WordPress', $result->get_sender()->get_name() );
		$this->assertInstanceOf( WP_Notify_Recipient_Collection::class, $result->get_recipients() );
		$this->assertEquals( 'WordPress was successfully updated to version 5.6.', $result->get_message()->get_content() );
		$additional_fields = new ReflectionProperty( WP_Notify_Composite_Notification::class, 'additional_fields' );
		$additional_fields->setAccessible( true );
		$this->assertEqualSets( $fields, $additional_fields->getValue( $result ) );
	}

	public function json_encoded_notifications_provider() {
		return array(
			'With title'                        => array(
				'fields'    => array( WP_Notify_Composite_Notification::FIELD_TITLE => 'WordPress' ),
				'json_file' => WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-title.json',
			),
			'With image'                        => array(
				'fields'    => array( WP_Notify_Composite_Notification::FIELD_IMAGE => new WP_Notify_Base_Image( '/path/to/my/image.jpg' ) ),
				'json_file' => WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-image.json',
			),
			'With action link'                  => array(
				'fields'    => array( WP_Notify_Composite_Notification::FIELD_ACTION_LINK => new WP_Notify_Action_Link( '#', "Read what's new in 5.6" ) ),
				'json_file' => WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-action-link.json',
			),
			'With title, image and action link' => array(
				'fields'    => array(
					WP_Notify_Composite_Notification::FIELD_TITLE => 'WordPress',
					WP_Notify_Composite_Notification::FIELD_IMAGE => new WP_Notify_Base_Image( '/path/to/my/iamge.jpg' ),
					WP_Notify_Composite_Notification::FIELD_ACTION_LINK => new WP_Notify_Action_Link( '#', "Read what's new in 5.6" ),
				),
				'json_file' => WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-title-image-link.json',
			),
		);
	}

	public function test_it_cannot_accept_an_unregistered_field_type() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "'user' is not a valid WP_Notify_Composite_Notification additional field type." );

		$this->composite_notification->add_field( 'user', 'totoro' );
	}

	public function test_it_cannot_accept_an_unregistered_field_type_in_an_array() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( "'user' is not a valid WP_Notify_Composite_Notification additional field type." );

		$this->composite_notification->add_fields(
			array(
				WP_Notify_Composite_Notification::FIELD_TITLE => 'WordPress',
				'user' => 'totore',
				WP_Notify_Composite_Notification::FIELD_IMAGE => new WP_Notify_Base_Image( '/path/to/my/image.jpg' ),
			)
		);
	}
}
