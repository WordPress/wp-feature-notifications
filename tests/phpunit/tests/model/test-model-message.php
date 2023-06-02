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
    "channel_title": "Test channel",
    "dismiss_label": "Nope",
    "icon": "hammer",
    "is_dismissible": true,
    "severity": "warning",
    "created_at": null,
    "expires_at": null,
    "id": null,
    "message": "Testing, testings... 1, 2, 3... testing",
    "title": "Message model test"
}';
		$this->assertEquals( $actual, $expected );
	}
}
