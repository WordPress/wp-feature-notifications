<?php

namespace WP\Notifications\Tests;

use WP\Notifications;

class Test_Factory extends TestCase {

	public function test_create_with_vendor_sender() {

		$vendor_sender = $this->createMock( '\WP\Notifications\Senders\Sender' );

		$message_factory = $this->getMockBuilder( '\WP\Notifications\Messages\Factory' )
								->setMethods( array( 'create', 'accepts' ) )
								->getMock();

		$message_factory->method( 'create' )->willReturn( $this->createMock( '\WP\Notifications\Messages\Message' ) );
		$message_factory->method( 'accepts' )->willReturn( true );

		$sender_factory = $this->getMockBuilder( '\WP\Notifications\Senders\Factory' )
			->setMethods( array( 'create' ) )
			->getMock();

		$sender_factory->method( 'create' )->willReturn( $this->createMock( '\WP\Notifications\Senders\Sender' ) );

		$factory = new Notifications\Factory(
			$message_factory,
			$this->createMock( '\WP\Notifications\Recipients\Factory' ),
			$sender_factory
		);

		$args = array(
			array(),
			array(),
			array(),
		);

		$notification = $factory->create( $args );
		$this->assertEquals( $vendor_sender, $notification->get_sender() );
	}
}
