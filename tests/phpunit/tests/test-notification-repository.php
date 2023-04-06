<?php

namespace WP\Notifications\Tests;

use stdClass;

use WP\Notifications\Persistence\Wpdb_Notification_Repository;

class Test_Wpdb_Notification_Repository extends TestCase {

	/** @dataProvider data_provider_it_returns_false_on_invalid_ids */
	public function test_it_returns_false_on_invalid_ids( $id ) {
		$testee = new Wpdb_Notification_Repository();
		$result = $testee->find_by_id( $id );
		$this->assertFalse( $result );
	}

	public function data_provider_it_returns_false_on_invalid_ids() {
		return array(
			array( - 1 ),
			array( 0 ),
			array( 'nonsense' ),
			array( array() ),
			array( new stdClass() ),
		);
	}
}
