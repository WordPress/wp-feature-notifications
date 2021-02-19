<?php

class Test_WPNotify_Factory extends WPNotify_TestCase {


	public function test_create_with_vendor_sender() {

		$vendor_sender = $this->createMock( 'WPNotify_Sender' );

		$message_factory = $this->getMockBuilder( 'WPNotify_MessageFactory' )
		                        ->setMethods( array( 'create', 'accepts' ) )
		                        ->getMock();

		$message_factory->method( 'create' )->willReturn( $this->createMock( 'WPNotify_Message' ) );
		$message_factory->method( 'accepts' )->willReturn( true );

		$sender_factory = $this->getMockBuilder( 'WPNotify_SenderFactory' )
		                       ->setMethods( array( 'create' ) )
		                       ->getMock();

		$sender_factory->method( 'create' )->willReturn( $this->createMock( 'WPNotify_Sender' ) );

		$factory = new WPNotify_Factory(
			$message_factory,
			$this->createMock( 'WPNotify_RecipientFactory' ),
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
