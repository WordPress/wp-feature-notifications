<?php

namespace WP\Notifications\Tests;

use PHPUnit\Framework\TestCase;

class DB_TestCase extends TestCase {
	protected function table_exists( string $table_name ): bool {
		global $wpdb;
		$expected = $wpdb->prefix . $table_name;
		$result   = $wpdb->get_var(
			$wpdb->prepare(
				'SHOW TABLES LIKE %s',
				$wpdb->esc_like( $wpdb->prefix . $table_name )
			)
		);
		return $expected === $result;
	}
}
