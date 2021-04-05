<?php

class Test_WPNotify_BaseSender extends WPNotify_TestCase {

	/**
	 * @param array  $sender_params
	 * @param string $expected_json
	 *
	 * @dataProvider data_provider_senders
	 */
	public function test_it_can_be_json_encoded( $sender_params, $expected_json ) {

		$sender_reflection = new ReflectionClass( 'WPNotify_BaseSender' );
		$sender            = $sender_reflection->newInstanceArgs( array_values( $sender_params ) );

		$sender_encoded = json_encode( $sender );

		$this->assertEquals( $expected_json, $sender_encoded );
	}

	/**
	 * @param array  $sender_params
	 * @param string $json
	 *
	 * @dataProvider data_provider_senders
	 */
	public function test_it_can_be_instantiated_from_json( $sender_params, $json ) {

		$testee = WPNotify_BaseSender::json_unserialize( $json );

		$sender_reflection = new ReflectionClass( 'WPNotify_BaseSender' );
		/** @var WPNotify_BaseSender $sender */
		$sender = $sender_reflection->newInstanceArgs( array_values( (array) $sender_params ) );

		$this->assertInstanceOf( 'WPNotify_BaseSender', $testee );
		$this->assertEquals( $sender->get_name(), $testee->get_name() );

		$this->assertEquals( $sender->get_image(), $testee->get_image() );

		/**
		 * If an Image has been sent
		 */
		if ( $testee->get_image() ) {
			$this->assertInstanceOf( get_class( $sender->get_image() ), $testee->get_image() );
			$this->assertEquals( $sender->get_image()->get_source(), $testee->get_image()->get_source() );
			$this->assertEquals( $sender->get_image()->get_alt(), $testee->get_image()->get_alt() );
		}
	}

	public function data_provider_senders() {

		return array(

			'sender without image' => array(
				array(
					'Name 1'
				),
				'{"name":"Name 1"}',
			),

			'sender with image' => array(
				array(
					'name'               => 'Name 2',
					'WPNotify_BaseImage' => new WPNotify_BaseImage( 'img-source', 'img-alt' ),
				),
				'{"name":"Name 2","WPNotify_BaseImage":{"source":"img-source","alt":"img-alt"}}',
			),
		);
	}
}
