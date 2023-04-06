<?php

class Test_WP_Notify_Factory extends WP_Notify_TestCase {


	public function test_create_with_vendor_sender() {

		$vendor_sender = $this->createMock( 'WP_Notify_Sender' );

		$message_factory = $this->getMockBuilder( 'WP_Notify_Message_Factory' )
								->setMethods( array( 'create', 'accepts' ) )
								->getMock();

		$message_factory->method( 'create' )->willReturn( $this->createMock( 'WP_Notify_Message' ) );
		$message_factory->method( 'accepts' )->willReturn( true );

		$sender_factory = $this->getMockBuilder( 'WP_Notify_Sender_Factory' )
			->setMethods( array( 'create' ) )
			->getMock();

		$sender_factory->method( 'create' )->willReturn( $this->createMock( 'WP_Notify_Sender' ) );

		$factory = new WP_Notify_Factory(
			$message_factory,
			$this->createMock( 'WP_Notify_Recipient_Factory' ),
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
