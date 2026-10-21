<?php

namespace WP\Notifications\Tests;

use WP\Notifications;

require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-activator.php';
require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-uninstaller.php';

class Test_Schema extends DB_TestCase {

	/**
	 * Start every test run from fresh v2 tables.
	 */
	public static function setUpBeforeClass(): void {
		Notifications\Uninstaller::drop_tables();
		Notifications\Activator::create_tables_v2();
	}

	/**
	 * Leave empty v2 tables behind for the next test class.
	 */
	public static function tearDownAfterClass(): void {
		Notifications\Uninstaller::drop_tables();
		Notifications\Activator::create_tables_v2();
	}

	/**
	 * Remove rows inserted by a test, even when its assertions failed.
	 */
	protected function tearDown(): void {
		global $wpdb;

		$wpdb->query( 'TRUNCATE TABLE ' . $wpdb->prefix . 'notifications_messages' );
		$wpdb->query( 'TRUNCATE TABLE ' . $wpdb->prefix . 'notifications_queue' );

		parent::tearDown();
	}

	/**
	 * Get the columns of a plugin table, keyed by column name.
	 *
	 * @param string $table_name Table name without the prefix.
	 *
	 * @return object[] Column rows from SHOW COLUMNS.
	 */
	private function get_columns( string $table_name ): array {
		global $wpdb;

		return $wpdb->get_results( 'SHOW COLUMNS FROM ' . esc_sql( $wpdb->prefix . $table_name ), OBJECT_K );
	}

	/**
	 * Get the columns of a table's primary key, in index order.
	 *
	 * @param string $table_name Table name without the prefix.
	 *
	 * @return string[] Column names.
	 */
	private function get_primary_key( string $table_name ): array {
		global $wpdb;

		return $wpdb->get_col( 'SHOW INDEX FROM ' . esc_sql( $wpdb->prefix . $table_name ) . " WHERE Key_name = 'PRIMARY'", 4 );
	}

	/**
	 * Insert a message row with only the required columns.
	 *
	 * @return int|false Number of rows inserted, or false on error.
	 */
	private function insert_message() {
		global $wpdb;

		return $wpdb->insert(
			$wpdb->prefix . 'notifications_messages',
			array(
				'channel_name' => 'core/test',
				'created_at'   => gmdate( 'Y-m-d H:i:s' ),
			)
		);
	}

	public function test_messages_id_should_auto_increment() {
		$columns = $this->get_columns( 'notifications_messages' );

		$this->assertStringContainsString( 'unsigned', $columns['id']->Type );
		$this->assertSame( 'auto_increment', $columns['id']->Extra );
		$this->assertSame( array( 'id' ), $this->get_primary_key( 'notifications_messages' ) );
	}

	/**
	 * Without AUTO_INCREMENT every insert wrote id 0, so the second one failed.
	 */
	public function test_messages_should_get_distinct_ids() {
		global $wpdb;

		$this->assertSame( 1, $this->insert_message() );
		$first_id = $wpdb->insert_id;

		$this->assertSame( 1, $this->insert_message() );
		$second_id = $wpdb->insert_id;

		$this->assertGreaterThan( 0, $first_id );
		$this->assertNotSame( $first_id, $second_id );
	}

	public function test_messages_columns() {
		$columns = $this->get_columns( 'notifications_messages' );

		$this->assertArrayNotHasKey( 'channel_title', $columns );
		$this->assertSame( 'text', $columns['message']->Type );
		$this->assertSame( 'text', $columns['meta']->Type );
		$this->assertSame( 'varchar(64)', $columns['channel_name']->Type );
		$this->assertNull( $columns['created_at']->Default );
	}

	public function test_queue_columns_and_primary_key() {
		$columns = $this->get_columns( 'notifications_queue' );

		$this->assertSame( 'varchar(64)', $columns['context']->Type );
		$this->assertSame( 'datetime', $columns['created_at']->Type );
		$this->assertNull( $columns['created_at']->Default );
		$this->assertSame( array( 'user_id', 'message_id' ), $this->get_primary_key( 'notifications_queue' ) );
	}

	public function test_queue_should_reject_duplicate_user_and_message() {
		global $wpdb;

		$table = $wpdb->prefix . 'notifications_queue';
		$row   = array(
			'message_id' => 1,
			'user_id'    => 1,
			'context'    => 'adminbar',
			'created_at' => gmdate( 'Y-m-d H:i:s' ),
		);

		$this->assertSame( 1, $wpdb->insert( $table, $row ) );

		$suppress = $wpdb->suppress_errors( true );
		$this->assertFalse( $wpdb->insert( $table, $row ) );
		$wpdb->suppress_errors( $suppress );
	}

	public function test_subscriptions_columns_and_primary_key() {
		$columns = $this->get_columns( 'notifications_subscriptions' );

		$this->assertSame( 'varchar(64)', $columns['channel_name']->Type );
		$this->assertSame( 'datetime', $columns['created_at']->Type );
		$this->assertSame( array( 'user_id', 'channel_name' ), $this->get_primary_key( 'notifications_subscriptions' ) );
	}

	/**
	 * Running dbDelta again on v2 tables must not find anything to change.
	 */
	public function test_dbdelta_should_find_no_changes_on_v2_tables() {
		$changes = dbDelta(
			array(
				Notifications\Database\Schema::messages_table_v2(),
				Notifications\Database\Schema::subscriptions_table_v2(),
				Notifications\Database\Schema::queue_table_v2(),
			),
			false
		);

		$this->assertSame( array(), $changes );
	}
}
