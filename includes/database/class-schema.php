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

	/*
	 * v2 notes:
	 * - Types are lowercase because dbDelta compares them case-sensitively.
	 * - Dates have no DEFAULT CURRENT_TIMESTAMP(). dbDelta sees it as a change on
	 *   every run and it uses server time. Always write UTC with gmdate().
	 */

	/**
	 * Get the SQL for all v2 tables.
	 *
	 * @return string[] CREATE TABLE statements for dbDelta().
	 */
	public static function tables_v2() {
		return array(
			self::messages_table_v2(),
			self::subscriptions_table_v2(),
			self::queue_table_v2(),
		);
	}

	public static function messages_table_v2() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		// `meta` holds JSON-encoded text. It is not a JSON column because MariaDB
		// aliases JSON to LONGTEXT, so dbDelta would always see a difference.
		return 'CREATE TABLE `' . $wpdb->prefix . "notifications_messages` (
			`id` bigint(20) unsigned NOT NULL auto_increment,
			`channel_name` varchar(64) NOT NULL,
			`created_at` datetime NOT NULL,
			`expires_at` datetime NULL,
			`severity` varchar(16) NULL,
			`title` text NULL,
			`message` text NULL,
			`meta` text NULL,
			PRIMARY KEY  (`id`),
			KEY `channel_name` (`channel_name`)
		) $charset_collate;\n";
	}

	public static function subscriptions_table_v2() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		return 'CREATE TABLE `' . $wpdb->prefix . "notifications_subscriptions` (
			`user_id` bigint(20) unsigned NOT NULL,
			`channel_name` varchar(64) NOT NULL,
			`created_at` datetime NOT NULL,
			`snoozed_until` datetime NULL,
			PRIMARY KEY  (`user_id`,`channel_name`),
			KEY `channel_name` (`channel_name`)
		) $charset_collate;\n";
	}

	public static function queue_table_v2() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		return 'CREATE TABLE `' . $wpdb->prefix . "notifications_queue` (
			`message_id` bigint(20) unsigned NOT NULL,
			`user_id` bigint(20) unsigned NOT NULL,
			`context` varchar(64) NOT NULL,
			`created_at` datetime NOT NULL,
			`dismissed_at` datetime NULL,
			`displayed_at` datetime NULL,
			PRIMARY KEY  (`user_id`,`message_id`),
			KEY `message_id` (`message_id`)
		) $charset_collate;\n";
	}
}
