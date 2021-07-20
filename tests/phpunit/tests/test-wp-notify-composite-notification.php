<?php


class Test_WP_Notify_Composite_Notification extends WPNotify_TestCase
{
    /**
     * @var WP_Notify_Composite_Notification
     */
    private $testee;

    public function setUp()
    {
        parent::setUp();

        $this->testee = new WP_Notify_Composite_Notification(
            new WP_Notify_Base_Sender( 'WordPress' ),
            new WP_Notify_Recipient_Collection(),
            new WP_Notify_Base_Message( 'WordPress was successfully updated to version 5.6.' )
        );
    }

    public function test_it_can_be_json_encoded_with_a_title() {
       $this->testee->add( WP_Notify_Composite_Notification::FIELD_TITLE, 'WordPress');

        $result = json_encode( $this->testee );

        $this->assertJsonStringEqualsJsonFile(
            WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/tests/data/notification-with-title.json',
            $result
        );
    }
}