<?php

namespace WP\Notifications\Tests;

use WP\Notifications\Factory;

/**
 * @covers \WP\Notifications\Framework\Factory
 */
class Test_Framework_Factory extends TestCase {

	/**
	 * Should return an instance of the subclass it is called on.
	 */
	public function test_get_instance_returns_instance_of_called_class() {
		$this->assertInstanceOf( Factory\Message::class, Factory\Message::get_instance() );
		$this->assertInstanceOf( Factory\Notification::class, Factory\Notification::get_instance() );
		$this->assertInstanceOf( Factory\Subscription::class, Factory\Subscription::get_instance() );
	}

	/**
	 * Should not share one instance across factory subclasses.
	 */
	public function test_get_instance_is_distinct_per_subclass() {
		$message      = Factory\Message::get_instance();
		$notification = Factory\Notification::get_instance();
		$subscription = Factory\Subscription::get_instance();

		$this->assertNotSame( $message, $notification );
		$this->assertNotSame( $message, $subscription );
		$this->assertNotSame( $notification, $subscription );
	}

	/**
	 * Should return the same instance on repeated calls for the same subclass.
	 */
	public function test_get_instance_is_singleton_per_subclass() {
		$this->assertSame( Factory\Message::get_instance(), Factory\Message::get_instance() );
		$this->assertSame( Factory\Notification::get_instance(), Factory\Notification::get_instance() );
		$this->assertSame( Factory\Subscription::get_instance(), Factory\Subscription::get_instance() );
	}
}
