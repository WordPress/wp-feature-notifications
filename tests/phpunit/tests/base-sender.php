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
			$image = null;
			if ( ! empty( $sender['image'] ) ) {
				$image = new WPNotify_BaseImage( $sender['image']['source'], $sender['image']['alt'] );
			}
			$sender_instance = new WPNotify_BaseSender( $sender['name'], $image );
		}

		$sender_encoded = json_encode( $sender_instance );

		$this->assertEquals( $expected_json, $sender_encoded );
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
				'{"name":"Name 1","image":null}',
			),
			array(
				array(
					'name'  => 'Name 1',
					'image' => array(
						'source' => 'img-source',
						'alt'    => 'img-alt',
					)
				),
				'{"name":"Name 1","image":{"source":"img-source","alt":"img-alt"}}'
			)
		);
	}
}
