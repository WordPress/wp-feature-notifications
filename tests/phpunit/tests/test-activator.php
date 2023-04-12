<?php

namespace WP\Notifications\Tests;

use WP\Notifications;

require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-activator.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-uninstaller.php';

class Test_Activator extends DB_TestCase {

	/**
	 * Remove the plugin to test installation.
	 */
	public static function setUpBeforeClass(): void {
		Notifications\Uninstaller::drop_tables();
	}

	// Sanity checks to ensure the database table initially does not exist.

	public function test_it_should_initially_not_have_messages_table() {
		$this->assertFalse( $this->table_exists( 'notifications_messages' ) );
	}

	public function test_it_should_initially_not_have_subscriptions_table() {
		$this->assertFalse( $this->table_exists( 'notifications_subscriptions' ) );
	}

	public function test_it_should_initially_not_have_queue_table() {
		$this->assertFalse( $this->table_exists( 'notifications_queue' ) );
	}

	// Installation procedure tests.

	/**
	 * Test to ensure the uninstall procedure drops the correct tables.
	 */
	public function test_it_should_create_tables() {
		Notifications\Activator::create_tables_v1();

		$this->assertTrue( $this->table_exists( 'notifications_messages' ) );
		$this->assertTrue( $this->table_exists( 'notifications_subscriptions' ) );
		$this->assertTrue( $this->table_exists( 'notifications_queue' ) );
	}
}
