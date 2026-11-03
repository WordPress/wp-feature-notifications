<?php

namespace WP\Notifications\Tests;

use PHPUnit_Adapter_TestCase;

class DB_TestCase extends PHPUnit_Adapter_TestCase {

	/**
	 * Check if a table exists in the database.
	 *
	 * @param string $table_name The name of the table to check.
	 *
	 * @return bool Whether the database exists.
	 */
	protected function table_exists( string $table_name ): bool {
		global $wpdb;

		$expected = $wpdb->prefix . $table_name;

		$actual = $wpdb->get_var(
			$wpdb->prepare(
				'SHOW TABLES LIKE %s',
				$wpdb->esc_like( $wpdb->prefix . $table_name )
			)
		);

		return $expected === $actual;
	}

	/**
	 * Get the columns of a plugin table, keyed by column name.
	 *
	 * @param string $table_name Table name without the prefix.
	 *
	 * @return object[] Column rows from SHOW COLUMNS.
	 */
	protected function get_columns( string $table_name ): array {
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
	protected function get_primary_key( string $table_name ): array {
		global $wpdb;

		return $wpdb->get_col( 'SHOW INDEX FROM ' . esc_sql( $wpdb->prefix . $table_name ) . " WHERE Key_name = 'PRIMARY'", 4 );
	}
}
