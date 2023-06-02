<?php

namespace WP\Notifications\Tests;

use WP_UnitTestCase_Base;
use WP\Notifications;
use WP\Notifications\Model;

class Test_Channel_Registry extends WP_UnitTestCase_Base {
	/**
	 * Fake channel registry.
	 *
	 * @var Channel_Registry
	 */
	private $registry = null;

	/**
	 * Set up each test method.
	 */
	public function set_up() {
		parent::set_up();

		$this->registry = new Notifications\Channel_Registry();
	}

	/**
	 * Tear down each test method.
	 */
	public function tear_down() {
		$this->registry = null;

		parent::tear_down();
	}

	/**
	 * Should reject channel without a namespace.
	 *
	 * @expectedIncorrectUsage WP\Notifications\Channel_Registry::register
	 */
	public function test_invalid_names_without_namespace() {
		$result = $this->registry->register( 'test', array() );
		$this->assertFalse( $result );
	}

	/**
	 * Should reject channels with invalid characters.
	 *
	 * @expectedIncorrectUsage WP\Notifications\Channel_Registry::register
	 */
	public function test_invalid_characters() {
		$result = $this->registry->register( 'test/_doing_it_wrong', array() );
		$this->assertFalse( $result );
	}

	/**
	 * Should reject channels with uppercase characters.
	 *
	 * @expectedIncorrectUsage WP\Notifications\Channel_Registry::register
	 */
	public function test_uppercase_characters() {
		$result = $this->registry->register( 'Core/Test', array() );
		$this->assertFalse( $result );
	}

	/**
	 * Should accept valid channel names
	 */
	public function test_register_channel() {
		$name     = 'core/test';
		$settings = array(
			'title' => 'Test Channel',
		);

		$channel = $this->registry->register( $name, $settings );
		$this->assertSame( $name, $channel->get_name() );
		$this->assertSame( $settings['title'], $channel->get_title() );
		$this->assertSame( $channel, $this->registry->get_registered( $name ) );
	}

	/**
	 * Should fail to re-register the same channel.
	 *
	 * @expectedIncorrectUsage WP\Notifications\Channel_Registry::register
	 */
	public function test_register_channel_twice() {
		$name     = 'core/test';
		$settings = array(
			'title' => 'Test Channel',
		);

		$result = $this->registry->register( $name, $settings );
		$this->assertNotFalse( $result );
		$result = $this->registry->register( $name, $settings );
		$this->assertFalse( $result );
	}

	/**
	 * Should accept a Channel instance.
	 */
	public function test_register_channel_instance() {
		$channel = new Model\Channel( 'core/test', 'Test Channel' );

		$result = $this->registry->register( $channel );
		$this->assertSame( $channel, $result );
	}

	/**
	 * Unregistering should fail if a channel is not registered.
	 *
	 * @expectedIncorrectUsage WP\Notifications\Channel_Registry::unregister
	 */
	public function test_unregister_not_registered_channel() {
		$result = $this->registry->unregister( 'core/unregistered' );
		$this->assertFalse( $result );
	}

	/**
	 * Should unregister existing channels.
	 */
	public function test_unregister_channel() {
		$name     = 'core/test';
		$settings = array(
			'title' => 'Test Channel',
		);

		$this->registry->register( $name, $settings );
		$channel = $this->registry->unregister( $name );
		$this->assertSame( $name, $channel->get_name() );
		$this->assertSame( $settings['title'], $channel->get_title() );
		$this->assertFalse( $this->registry->is_registered( $name ) );
	}

	/**
	 * Should return all registered channels.
	 */
	public function test_get_all_registered() {
		$names    = array( 'core/updates', 'core/post-edit', 'core/post-delete' );
		$settings = array(
			'title' => 'random',
		);

		foreach ( $names as $name ) {
			$this->registry->register( $name, $settings );
		}

		$registered = $this->registry->get_all_registered();
		$this->assertSameSets( $names, array_keys( $registered ) );
	}
}
