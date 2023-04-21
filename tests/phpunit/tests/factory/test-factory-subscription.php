<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Factory;

class Test_Factory_Subscription extends TestCase {

	/**
	 * Test subscription factory.
	 */
	protected ?Factory\Subscription $factory = null;

	/**
	 * Set up each test method.
	 */
	public function set_up() {
		parent::set_up();

		$this->factory = new Factory\Subscription();
	}

	/**
	 * Tear down each test method.
	 */
	public function tear_down() {
		$this->factory = null;

		parent::tear_down();
	}

	/**
	 * Should create an instance of the subscription model class.
	 */
	public function test_makes_subscription_instance() {
		$actual = $this->factory->make();
		$this->assertInstanceOf( '\WP\Notifications\Model\Subscription', $actual );
	}
}
