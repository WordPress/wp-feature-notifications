<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Factory;

class Test_Factory_Message extends TestCase {

	/**
	 * Test message factory.
	 */
	protected ?Factory\Message $factory = null;

	/**
	 * Set up each test method.
	 */
	public function set_up() {
		parent::set_up();

		$this->factory = new Factory\Message();
	}

	/**
	 * Tear down each test method.
	 */
	public function tear_down() {
		$this->factory = null;

		parent::tear_down();
	}

	/**
	 * Should create an instance of the message model class.
	 */
	public function test_makes_message_instance() {
		$actual = $this->factory->make();
		$this->assertInstanceOf( '\WP\Notifications\Model\Message', $actual );
	}
}
