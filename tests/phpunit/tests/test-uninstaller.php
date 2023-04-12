<?php

namespace WP\Notifications\Tests;

use WP\Notifications;

require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-activator.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-uninstaller.php';

class Test_Uninstaller extends DB_TestCase {

	/**
	 * Ensure tables are created before tests.
	 */
	public static function setUpBeforeClass(): void {
		Notifications\Activator::create_tables_v1();
	}

	// Sanity checks to ensure the database tables initially do exist.

	public function test_it_should_initially_have_messages_table() {
		$this->assertTrue( $this->table_exists( 'notifications_messages' ) );
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
		Notifications\Uninstaller::uninstall();

		$this->assertFalse( $this->table_exists( 'notifications_messages' ) );
		$this->assertFalse( $this->table_exists( 'notifications_channels' ) );
		$this->assertFalse( $this->table_exists( 'notifications_subscriptions' ) );
		$this->assertFalse( $this->table_exists( 'notifications_queue' ) );
	}
}
