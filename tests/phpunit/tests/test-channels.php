<?php

namespace WP\Notifications\Tests;

use WP_UnitTestCase_Base;
use WP\Notifications;
use WP\Notifications\Model;

class Test_Channels extends WP_UnitTestCase_Base {

	/**
	 * Tear down each test method and clean up any registered test channels.
	 */
	public function tear_down() {
		$registry = Notifications\Channel_Registry::get_instance();

		foreach ( array( 'test/functional-channel', 'test/instance-channel' ) as $name ) {
			if ( $registry->is_registered( $name ) ) {
				$registry->unregister( $name );
			}
		}

		parent::tear_down();
	}

	/**
	 * Should register a channel using an array of arguments.
	 */
	public function test_register_channel_with_args() {
		$name     = 'test/functional-channel';
		$settings = array(
			'title' => 'Functional Channel',
		);

		$channel = Notifications\register_channel( $name, $settings );

		$this->assertInstanceOf( Model\Channel::class, $channel );
		$this->assertSame( $name, $channel->get_name() );
		$this->assertSame( $settings['title'], $channel->get_title() );
		$this->assertTrue( Notifications\Channel_Registry::get_instance()->is_registered( $name ) );
	}

	/**
	 * Should register a channel using a Channel instance.
	 */
	public function test_register_channel_instance() {
		$channel = new Model\Channel( 'test/instance-channel', 'Instance Channel' );

		$result = Notifications\register_channel( $channel );

		$this->assertSame( $channel, $result );
		$this->assertTrue( Notifications\Channel_Registry::get_instance()->is_registered( 'test/instance-channel' ) );
	}

	/**
	 * Should unregister an existing channel.
	 */
	public function test_unregister_channel() {
		$name     = 'test/functional-channel';
		$settings = array(
			'title' => 'Functional Channel',
		);

		Notifications\register_channel( $name, $settings );
		$this->assertTrue( Notifications\Channel_Registry::get_instance()->is_registered( $name ) );

		$channel = Notifications\unregister_channel( $name );

		$this->assertInstanceOf( Model\Channel::class, $channel );
		$this->assertSame( $name, $channel->get_name() );
		$this->assertFalse( Notifications\Channel_Registry::get_instance()->is_registered( $name ) );
	}

	/**
	 * Should fail to unregister an unregistered channel.
	 *
	 * @expectedIncorrectUsage WP\Notifications\Channel_Registry::unregister
	 */
	public function test_unregister_not_registered_channel() {
		$result = Notifications\unregister_channel( 'test/non-existent' );
		$this->assertFalse( $result );
	}

	/**
	 * Should verify core channels are registered on init.
	 */
	public function test_core_channels_registered() {
		$core_channels = array(
			'core/updates',
			'core/plugin-install',
			'core/plugin-uninstall',
			'core/plugin-activate',
			'core/plugin-deactivate',
			'core/plugin-updates',
			'core/post-new',
			'core/post-edit',
			'core/post-delete',
			'core/comment-new',
		);

		$registry = Notifications\Channel_Registry::get_instance();

		foreach ( $core_channels as $channel_name ) {
			$this->assertTrue(
				$registry->is_registered( $channel_name ),
				"Core channel {$channel_name} should be registered."
			);
		}
	}
}
