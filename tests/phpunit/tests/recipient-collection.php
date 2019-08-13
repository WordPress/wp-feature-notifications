<?php

class Test_WPNotify_RecipientCollection extends WPNotify_TestCase {

	const SERIALIZED = 'C:28:"WPNotify_RecipientCollection":6:{a:0:{}}';

	public function test_it_can_be_instantiated() {
		$testee = new WPNotify_RecipientCollection();
		$this->assertInstanceOf( 'WPNotify_RecipientCollection', $testee );
	}

	public function test_it_is_countable() {
		$testee = new WPNotify_RecipientCollection();
		$this->assertInstanceOf( 'Countable', $testee );
	}

	public function test_it_is_traversable() {
		$testee = new WPNotify_RecipientCollection();
		$this->assertInstanceOf( 'Traversable', $testee );
	}

	public function test_it_is_empty_when_instantiated_without_arguments() {
		$testee = new WPNotify_RecipientCollection();
		$this->assertCount( 0, $testee );
	}

	public function test_it_can_accept_a_singular_recipient() {
		$mock_recipient = $this->getMock( 'WPNotify_Recipient' );
		$testee = new WPNotify_RecipientCollection( $mock_recipient );
		$this->assertCount( 1, $testee );
	}

	public function test_it_can_accept_an_array_of_recipients() {
		$mock_recipient_1 = $this->getMock( 'WPNotify_Recipient' );
		$mock_recipient_2 = $this->getMock( 'WPNotify_Recipient' );
		$mock_recipient_3 = $this->getMock( 'WPNotify_Recipient' );
		$testee = new WPNotify_RecipientCollection(
			array(
				$mock_recipient_1,
				$mock_recipient_2,
				$mock_recipient_3,
			)
		);
		$this->assertCount( 3, $testee );
	}

	public function test_recipients_can_be_added() {
		$mock_recipient_1 = $this->getMock( 'WPNotify_Recipient' );
		$mock_recipient_2 = $this->getMock( 'WPNotify_Recipient' );
		$mock_recipient_3 = $this->getMock( 'WPNotify_Recipient' );
		$testee = new WPNotify_RecipientCollection( $mock_recipient_1 );
		$this->assertCount( 1, $testee );
		$testee->add( $mock_recipient_2 );
		$this->assertCount( 2, $testee );
		$testee->add( $mock_recipient_3 );
		$this->assertCount( 3, $testee );
	}

	/** @dataProvider data_provider_it_throws_on_invalid_type */
	public function test_it_throws_on_invalid_type( $invalid_recipient ) {
		$this->setExpectedException( 'WPNotify_RuntimeException' );
		new WPNotify_RecipientCollection( $invalid_recipient );
	}

	public function data_provider_it_throws_on_invalid_type() {
		$mock_recipient_1 = $this->getMock( 'WPNotify_Recipient' );
		$mock_recipient_2 = $this->getMock( 'WPNotify_Recipient' );

		return array(
			array( null ),
			array( true ),
			array( new stdClass ),
			array( 'invalid' ),
			array( array( 1, 2, 3 ) ),
			array( array( $mock_recipient_1, 'invalid', $mock_recipient_2 ) ),
		);
	}

	public function test_it_can_be_json_encoded() {
		$testee = new WPNotify_RecipientCollection( array() );
		$this->assertEquals(
			'[]',
			json_encode( $testee )
		);
	}

	public function test_it_can_be_instantiated_from_json() {
		$json   = '[]';
		$testee = WPNotify_RecipientCollection::json_unserialize( $json );
		$this->assertInstanceOf( 'WPNotify_RecipientCollection', $testee );
		$this->assertEquals( 0, $testee->count() );
	}
}
