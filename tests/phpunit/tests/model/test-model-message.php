<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Model;

class Test_Model_Message extends TestCase {

	/**
	 * Test message model.
	 */
	private ?Model\Message $model = null;

	/**
	 * Set up each test method.
	 */
	public function set_up() {
		parent::set_up();

		$this->model = new Model\Message(
			'Testing, testings... 1, 2, 3... testing',
			'Ok',
			null,
			null,
			'Nope',
			new \DateTime( '2023-12-31T00:00:00+00:00' ),
			'hammer',
			null,
			true,
			'warning',
			'Message model test'
		);
	}

	/**
	 * Tear down each test method.
	 */
	public function tear_down() {
		$this->model = null;

		parent::tear_down();
	}

	/**
	 * Should be JSON serializable.
	 */
	public function test_json_serializable() {
		$actual   = json_encode( $this->model, JSON_PRETTY_PRINT );
		$expected = '{
    "accept_label": "Ok",
    "dismiss_label": "Nope",
    "icon": "hammer",
    "is_dismissible": true,
    "created_at": null,
    "expires_at": "2023-12-31T00:00:00+00:00",
    "id": null,
    "message": "Testing, testings... 1, 2, 3... testing",
    "severity": "warning",
    "title": "Message model test"
}';
		$this->assertEquals( $actual, $expected );
	}

	/**
	 * Should not write column values into the meta JSON.
	 */
	public function test_encode_meta_excludes_columns() {
		$actual   = $this->model->encode_meta();
		$expected = '{"accept_label":"Ok","dismiss_label":"Nope","icon":"hammer","is_dismissible":true}';
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * Should accept messages up to the size of the TEXT column.
	 */
	public function test_validate_message_length() {
		$validate = new \ReflectionMethod( Model\Message::class, 'validate_message' );
		$validate->setAccessible( true );

		$this->assertTrue( $validate->invoke( new Model\Message( str_repeat( 'a', 256 ) ) ) );
		$this->assertTrue( $validate->invoke( new Model\Message( str_repeat( 'a', 65535 ) ) ) );
		$this->assertFalse( $validate->invoke( new Model\Message( str_repeat( 'a', 65536 ) ) ) );
		$this->assertFalse( $validate->invoke( new Model\Message() ) );
	}
}
