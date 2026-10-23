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
			'Test channel',
			null,
			'Nope',
			null,
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
    "channel_title": "Test channel",
    "created_at": null,
    "expires_at": null,
    "id": null,
    "message": "Testing, testings... 1, 2, 3... testing",
    "severity": "warning",
    "title": "Message model test"
}';
		$this->assertEquals( $actual, $expected );
	}

	/**
	 * Should only encode the metadata keys into the meta JSON.
	 */
	public function test_encode_meta_only_includes_meta_keys() {
		$actual   = json_decode( $this->model->encode_meta(), true );
		$expected = array(
			'accept_label'   => 'Ok',
			'dismiss_label'  => 'Nope',
			'icon'           => 'hammer',
			'is_dismissible' => true,
		);
		$this->assertSame( $expected, $actual );
	}

	/**
	 * Should not encode properties stored in their own columns into the meta JSON.
	 */
	public function test_encode_meta_excludes_column_properties() {
		$model = new Model\Message(
			'Message with an expiry',
			null,
			null,
			'Test channel',
			new \DateTime( '2024-01-02 03:04:05', new \DateTimeZone( 'UTC' ) ),
			null,
			new \DateTime( '2024-02-03 04:05:06', new \DateTimeZone( 'UTC' ) ),
			null,
			null,
			true,
			'warning'
		);

		$actual = json_decode( $model->encode_meta(), true );

		$this->assertArrayNotHasKey( 'channel_title', $actual );
		$this->assertArrayNotHasKey( 'created_at', $actual );
		$this->assertArrayNotHasKey( 'expires_at', $actual );
		$this->assertArrayNotHasKey( 'severity', $actual );
	}
}
