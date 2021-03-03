<?php

class Test_WPNotify_BaseImage extends WPNotify_TestCase {

	/**
	 * @param array  $image_data
	 * @param string $expected_json
	 *
	 * @dataProvider data_provider_images
	 */
	public function test_it_can_be_json_encoded( $image_data, $expected_json ) {

		$source = $image_data['source'];
		$alt    = $image_data['alt'];

		$image_instance = new WPNotify_BaseImage( $source, $alt );
		$encoded_image  = json_encode( $image_instance );

		$this->assertEquals( $expected_json, $encoded_image );
	}

	/**
	 * Tests if an WPNotify_Image can be instantiated from JSON string
	 *
	 * @param array  $image_data indexed array with [source,alt] elements
	 * @param string $json
	 *
	 * @dataProvider data_provider_images
	 */
	public function test_it_can_be_instantiated_from_json( $image_data, $json ) {

		$test_instance = WPNotify_BaseImage::json_unserialize( $json );

		list( $source, $alt ) = array_values( $image_data );

		$instance = new WPNotify_BaseImage( $source, $alt );

		$this->assertEquals( $instance->get_source(), $test_instance->get_source() );
		$this->assertEquals( $instance->get_alt(), $test_instance->get_alt() );
	}

	public function data_provider_images() {

		return array(

			array(
				array(
					'source' => 'img-source1',
					'alt'    => 'img-alt1',
				),
				'{"source":"img-source1","alt":"img-alt1"}',
			),

			array(
				array(
					'source' => 'img-source2',
					'alt'    => '',
				),
				'{"source":"img-source2","alt":""}',
			),

			array(
				array(
					'source' => 'img-source3',
					'alt'    => null,
				),
				'{"source":"img-source3","alt":null}',
			),

			array(
				array(
					'source' => '',
					'alt'    => ''
				),
				'{"source":"","alt":""}',
			),
		);
	}
}
