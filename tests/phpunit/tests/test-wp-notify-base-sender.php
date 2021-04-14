<?php

class Test_WP_Notify_Base_Sender extends WPNotify_TestCase {

	/**
	 * @param array  $sender_params
	 * @param string $expected_json
	 *
	 * @dataProvider data_provider_senders
	 */
	public function test_it_can_be_json_encoded( $sender_params, $expected_json ) {

		$sender_reflection = new ReflectionClass('WP_Notify_Base_Sender');
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

		$testee = WP_Notify_Base_Sender::json_unserialize( $json );

		$sender_reflection = new ReflectionClass('WP_Notify_Base_Sender');
		/** @var WP_Notify_Base_Sender $sender */
		$sender = $sender_reflection->newInstanceArgs( array_values( (array) $sender_params ) );

		$this->assertInstanceOf( 'WP_Notify_Base_Sender', $testee );
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
					'Name 1',
				),
				'{"name":"Name 1"}',
			),

			'sender with image'    => array(
				array(
					'name'               => 'Name 2',
					'WP_Notify_Base_Image' => new WP_Notify_Base_Image( 'img-source', 'img-alt' ),
				),
				'{"name":"Name 2","WP_Notify_Base_Image":{"source":"img-source","alt":"img-alt"}}',
			),
		);
	}
}
