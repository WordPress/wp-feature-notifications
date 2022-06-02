<?php

class Test_WP_Notify_Action_Link extends WPNotify_TestCase {

	public function test_it_can_be_serialized() {
		$testee = new WP_Notify_Action_Link( '#', 'Read more' );

		$result = json_encode( $testee );

		$this->assertJsonStringEqualsJsonString(
			'{
                "url": "#",
                "text": "Read more"
            }',
			$result
		);
	}

	public function test_it_can_be_unserialized() {
		$json_data = '{
            "url": "#",
            "text": "Read more"
        }';

		$result = WP_Notify_Action_Link::json_unserialize( $json_data );

		$this->assertInstanceOf( WP_Notify_Action_Link::class, $result );
		$this->assertEquals( new WP_Notify_Action_Link( '#', 'Read more' ), $result );
	}
}
