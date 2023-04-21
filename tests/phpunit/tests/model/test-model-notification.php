<?php

namespace WP\Notifications\Tests;

use DateTime;
use WP\Notifications\Model;

class Test_Model_Notification extends TestCase {

	/**
	 * Test message model.
	 */
	private ?Model\Notification $model = null;

	/**
	 * Set up each test method.
	 */
	public function set_up() {
		parent::set_up();

		$this->model = new Model\Notification(
			'core/test',
			1,
			1,
			'adminbar',
			null,
			null,
			null,
			new DateTime( '2023-12-31' )
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
    "channel_name": "core\/test",
    "context": "adminbar",
    "created_at": null,
    "dismissed_at": null,
    "displayed_at": null,
    "expires_at": "2023-12-31T00:00:00+00:00",
    "message_id": 1,
    "user_id": 1
}';
		$this->assertEquals( $actual, $expected );
	}
}
