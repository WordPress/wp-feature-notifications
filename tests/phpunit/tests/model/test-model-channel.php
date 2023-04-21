<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Model;

class Test_Model_Channel extends TestCase {

	/**
	 * Test channel model.
	 */
	private ?Model\Channel $model = null;

	/**
	 * Set up each test method.
	 */
	public function set_up() {
		parent::set_up();

		$this->model = new Model\Channel(
			'core/test',
			'Test channel',
			'test',
			'A test case channel',
			'WordPress'
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
    "context": "test",
    "description": "A test case channel",
    "icon": "WordPress",
    "name": "core\/test",
    "title": "Test channel"
}';
		$this->assertEquals( $actual, $expected );
	}
}
