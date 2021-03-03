<?php

class Test_WPNotify_BaseSender extends WPNotify_TestCase {

	/**
	 * @param string|array $sender
	 * @param string       $expected_json
	 *
	 * @dataProvider data_provider_senders
	 */
	public function test_it_can_be_json_encoded( $sender, $expected_json ) {

		$sender_instance = null;

		if ( is_string( $sender ) ) {
			$sender_instance = new WPNotify_BaseSender( $sender );
		} else if ( is_array( $sender ) ) {
			$sender_instance = new WPNotify_BaseSender( $sender['name'], $sender['image'] );
		}

		$sender_encoded = json_encode( $sender_instance );

		$this->assertEquals( $expected_json, $sender_encoded );
	}

	/**
	 * @param string|array $sender
	 * @param string       $json
	 *
	 * @dataProvider data_provider_senders
	 */
	public function test_it_can_be_instantiated_from_json( $sender, $json ) {

		$testee = WPNotify_BaseSender::json_unserialize( $json );

		$sender_name     = '';
		$sender_instance = null;

		if ( is_string( $sender ) ) {
			$sender_name     = $sender;
			$sender_instance = new WPNotify_BaseSender( $sender );
		} else if ( is_array( $sender ) ) {
			$sender_name     = $sender['name'];
			$sender_instance = new WPNotify_BaseSender( $sender['name'], $sender['image'] );
		}

		$this->assertInstanceOf( 'WPNotify_BaseSender', $testee );
		$this->assertEquals( $sender_name, $testee->get_name() );

		/**
		 * If an Image has been sent
		 */
		if ( $testee->get_image() ) {
			$this->assertInstanceOf( 'WPNotify_BaseImage', $testee->get_image() );
			$this->assertEquals( $sender_instance->get_image()->get_source(), $testee->get_image()->get_source() );
			$this->assertEquals( $sender_instance->get_image()->get_alt(), $testee->get_image()->get_alt() );
		}
	}

	public function data_provider_senders() {
		return array(

			array(
				'Name 1',
				'{"name":"Name 1","image":null}',
			),

			array(
				array(
					'name'  => 'Name 2',
					'image' => new WPNotify_BaseImage( 'img-source', 'img-alt' ),
				),
				'{"name":"Name 2","image":{"source":"img-source","alt":"img-alt"}}',
			),
		);
	}
}
