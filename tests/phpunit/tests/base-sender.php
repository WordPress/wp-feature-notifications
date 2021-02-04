<?php

class Test_WPNotify_BaseSender extends WPNotify_TestCase {

	/**
	 * @param string $senderName
	 * @param string $expectedJson
	 *
	 * @dataProvider data_provider_senders
	 */
	public function test_it_can_be_json_encoded( $senderName, $expectedJson ) {

		$senderInstance = new WPNotify_BaseSender( $senderName );
		$encoded        = json_encode( $senderInstance );
		$this->assertEquals( $expectedJson, $encoded );
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
