<?php

class Test_WPNotify_BaseSender extends WPNotify_TestCase {

	/**
	 * @param string $sender_name
	 * @param string $expected_json
	 *
	 * @dataProvider data_provider_senders
	 */
	public function test_it_can_be_json_encoded( $sender_name, $expected_json ) {

		$senderInstance = new WPNotify_BaseSender( $sender_name );
		$encoded        = json_encode( $senderInstance );
		$this->assertEquals( $expected_json, $encoded );
	}

	/**
	 * @param string $sender_name
	 * @param string $json
	 *
	 * @dataProvider data_provider_senders
	 */
	public function test_it_can_be_instantiated_from_json( $sender_name, $json ) {

		$sender_instance = WPNotify_BaseSender::json_unserialize( $json );

		$this->assertInstanceOf( 'WPNotify_BaseSender', $sender_instance );
		$this->assertEquals( $sender_name, $sender_instance->get_name() );
	}

	public function data_provider_senders() {
		return array(
			array(
				'Name 1',
				'{"name":"Name 1"}',
			)
		);
	}
}
