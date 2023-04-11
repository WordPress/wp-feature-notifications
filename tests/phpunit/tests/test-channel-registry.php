<?php

namespace WP\Notifications\Test;

use WP_Notify_TestCase;
use WP\Notifications;

use function WP\Notifications\register_channel;

class Test_Channel_Registry extends WP_Notify_TestCase {
	public function test_it_should_add_channel_to_registry() {
		$expected = new Notifications\Channel(
			'core/test',
			array(
				'title'       => 'Testing',
				'icon'        => 'wordpress',
				'description' => 'Test notification channel.',
			)
		);

		register_channel( $expected );

		$actual = Notifications\Channel_Registry::get_instance()->get_registered( 'core/test' );

		$this->assertSame( $expected, $actual );
	}
}
