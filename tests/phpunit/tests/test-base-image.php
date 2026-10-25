<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Image;

class Test_BaseImage extends TestCase {

	/**
	 * @param array  $image_data
	 * @param string $expected_json
	 *
	 * @dataProvider data_provider_images
	 */
	public function test_it_can_be_json_encoded( $image_data, $expected_json ) {

		$source = $image_data['source'];
		$alt    = $image_data['alt'];

		$image_instance = new Image\Base_Image( $source, $alt );
		$encoded_image  = json_encode( $image_instance );

		$this->assertEquals( $expected_json, $encoded_image );
	}

	/**
	 * Tests if an Image can be instantiated from JSON string
	 *
	 * @param array  $image_data indexed array with [source,alt] elements
	 * @param string $json
	 *
	 * @dataProvider data_provider_images
	 */
	public function test_it_can_be_instantiated_from_json( $image_data, $json ) {

		$test_instance = Image\Base_Image::json_unserialize( $json );

		list( $source, $alt ) = array_values( $image_data );

		$instance = new Image\Base_Image( $source, $alt );

		$this->assertEquals( $instance->get_source(), $test_instance->get_source() );
		$this->assertEquals( $instance->get_alt(), $test_instance->get_alt() );
	}

	/**
	 * Tests that json_unserialize returns null for invalid JSON.
	 */
	public function test_json_unserialize_returns_null_for_invalid_json() {
		$this->assertNull( Image\Base_Image::json_unserialize( 'not-valid-json' ) );
		$this->assertNull( Image\Base_Image::json_unserialize( '' ) );
		$this->assertNull( Image\Base_Image::json_unserialize( 'null' ) );
		$this->assertNull( Image\Base_Image::json_unserialize( '"just-a-string"' ) );
	}

	public function data_provider_images() {

		return array(

			'Image with source and alternative text'      => array(
				array(
					'source' => 'img-source1',
					'alt'    => 'img-alt1',
				),
				'{"source":"img-source1","alt":"img-alt1"}',
			),

			'Image with source and empty string alternative text' => array(
				array(
					'source' => 'img-source2',
					'alt'    => '',
				),
				'{"source":"img-source2"}',
			),

			'Image with source and null alternative text' => array(
				array(
					'source' => 'img-source3',
					'alt'    => null,
				),
				'{"source":"img-source3"}',
			),

			'Image with empty string source and alternative text' => array(
				array(
					'source' => '',
					'alt'    => '',
				),
				'[]',
			),
		);
	}
}
