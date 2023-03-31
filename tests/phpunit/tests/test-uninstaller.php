<?php

use WP\Notifications\Activator;
use WP\Notifications\Uninstaller;

class Test_WP_Notify_Uninstaller extends WP_Notifications_DB_TestCase {

	/**
	 * Ensure tables are created before tests.
	 */
	public static function setUpBeforeClass(): void {
		Activator::create_tables_v1();
	}

	// Sanity checks to ensure the database tables initially do exist.

	public function test_it_should_initially_have_messages_table() {
		$this->assertTrue( $this->table_exists( 'notifications_messages' ) );
	}

	public function test_it_should_initially_have_channels_table() {
		$this->assertTrue( $this->table_exists( 'notifications_channels' ) );
	}

	public function test_it_should_initially_have_subscriptions_table() {
		$this->assertTrue( $this->table_exists( 'notifications_subscriptions' ) );
	}

	public function test_it_should_initially_have_queue_table() {
		$this->assertTrue( $this->table_exists( 'notifications_queue' ) );
	}

	/**
	 * Test to ensure the uninstall procedure drops the correct tables.
	 */
	public function test_it_should_drops_tables() {
		Uninstaller::uninstall();

		$this->assertFalse( $this->table_exists( 'notifications_messages' ) );
		$this->assertFalse( $this->table_exists( 'notifications_channels' ) );
		$this->assertFalse( $this->table_exists( 'notifications_subscriptions' ) );
		$this->assertFalse( $this->table_exists( 'notifications_queue' ) );
	}
}
