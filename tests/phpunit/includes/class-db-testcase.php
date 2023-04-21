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

}
