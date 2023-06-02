<?php
/**
 * Notifications API:Schema class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Database;

/**
 * Class Schema
 *
 * Static class representing the database schema.
 */
final class Schema {

	public static function messages_table_v1() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		return 'CREATE TABLE `' . $wpdb->prefix . "notifications_messages` (
			`id` BIGINT(20) NOT NULL,
			`channel_name` VARCHAR(50) NOT NULL,
			`channel_title` TINYTEXT NOT NULL,
			`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
			`expires_at` DATETIME NULL,
			`severity` VARCHAR(16) NULL,
			`title` TINYTEXT NULL,
			`message` TINYTEXT NULL,
			`meta` TEXT NULL,
			PRIMARY KEY  (`id`),
			KEY `channel_name` (`channel_name`)
		) $charset_collate;\n";
	}


	public static function subscriptions_table_v1() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		return 'CREATE TABLE `' . $wpdb->prefix . "notifications_subscriptions` (
			`user_id` BIGINT(20) NOT NULL,
			`channel_name` VARCHAR(50) NOT NULL,
			`snoozed_until` DATETIME NULL,
			KEY `user_id` (`user_id`),
			KEY `channel_name` (`channel_name`)
		) $charset_collate;\n";
	}

	public static function queue_table_v1() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		return 'CREATE TABLE `' . $wpdb->prefix . "notifications_queue` (
				`message_id` BIGINT(20) NOT NULL,
				`user_id` BIGINT(20) NOT NULL,
				`dismissed_at` DATETIME NULL,
				`displayed_at` DATETIME NULL,
				KEY `message_id` (`message_id`),
				KEY `user_id` (`user_id`)
			) $charset_collate;\n";
	}
}
