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

	/**
	 * Should deserialize MySQL datetime strings as UTC dates.
	 */
	public function test_makes_message_with_mysql_dates() {
		$actual = $this->factory->make(
			array(
				'message'    => 'Message with dates',
				'created_at' => '2024-01-02 03:04:05',
				'expires_at' => '2024-02-03 04:05:06',
			)
		);

		$this->assertInstanceOf( \DateTime::class, $actual->get_created_at() );
		$this->assertSame( '2024-01-02T03:04:05+00:00', $actual->get_created_at()->format( \DateTime::ATOM ) );
		$this->assertInstanceOf( \DateTime::class, $actual->get_expires_at() );
		$this->assertSame( '2024-02-03T04:05:06+00:00', $actual->get_expires_at()->format( \DateTime::ATOM ) );
	}
}
