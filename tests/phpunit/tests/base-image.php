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

	public function data_provider_images() {

		return array(
			array(
				array(
					'source' => 'img-source',
					'alt'    => 'img-alt',
				),
				'{"source":"img-source","alt":"img-alt"}',
			),
			array(
				array(
					'source' => 'img-source',
					'alt'    => '',
				),
				'{"source":"img-source","alt":""}',
			)
		);
	}
}
