<?php

class Test_WP_Notification_Recipient_Collection extends WP_Notification_Center_TestCase {

	public function test_it_can_be_instantiated() {
		$testee = new WP_Notification_Recipient_Collection();
		$this->assertInstanceOf( 'WP_Notification_Recipient_Collection', $testee );
	}

	public function test_it_is_countable() {
		$testee = new WP_Notification_Recipient_Collection();
		$this->assertInstanceOf( 'Countable', $testee );
	}

	public function test_it_is_traversable() {
		$testee = new WP_Notification_Recipient_Collection();
		$this->assertInstanceOf( 'Traversable', $testee );
	}

	public function test_it_is_empty_when_instantiated_without_arguments() {
		$testee = new WP_Notification_Recipient_Collection();
		$this->assertCount( 0, $testee );
	}

	public function test_it_can_accept_a_singular_recipient() {
		$mock_recipient = $this->getMock( 'WP_Notification_Recipient' );
		$testee = new WP_Notification_Recipient_Collection( $mock_recipient );
		$this->assertCount( 1, $testee );
	}

	public function test_it_can_accept_an_array_of_recipients() {
		$mock_recipient_1 = $this->getMock( 'WP_Notification_Recipient' );
		$mock_recipient_2 = $this->getMock( 'WP_Notification_Recipient' );
		$mock_recipient_3 = $this->getMock( 'WP_Notification_Recipient' );
		$testee = new WP_Notification_Recipient_Collection(
			array(
				$mock_recipient_1,
				$mock_recipient_2,
				$mock_recipient_3,
			)
		);
		$this->assertCount( 3, $testee );
	}

	public function test_recipients_can_be_added() {
		$mock_recipient_1 = $this->getMock( 'WP_Notification_Recipient' );
		$mock_recipient_2 = $this->getMock( 'WP_Notification_Recipient' );
		$mock_recipient_3 = $this->getMock( 'WP_Notification_Recipient' );
		$testee = new WP_Notification_Recipient_Collection( $mock_recipient_1 );
		$this->assertCount( 1, $testee );
		$testee->add( $mock_recipient_2 );
		$this->assertCount( 2, $testee );
		$testee->add( $mock_recipient_3 );
		$this->assertCount( 3, $testee );
	}

	/** @dataProvider data_provider_it_throws_on_invalid_type */
	public function test_it_throws_on_invalid_type( $invalid_recipient ) {
		$this->setExpectedException( 'WP_Notification_Runtime_Exception' );
		new WP_Notification_Recipient_Collection( $invalid_recipient );
	}

	public function data_provider_it_throws_on_invalid_type() {
		$mock_recipient_1 = $this->getMock( 'WP_Notification_Recipient' );
		$mock_recipient_2 = $this->getMock( 'WP_Notification_Recipient' );

		return array(
			array( 1, 2, 3 ),
			array( $mock_recipient_1, 'invalid', $mock_recipient_2 ),
		);
	}
}
