<?php

namespace WP\Notifications;

/**
 * Class Activator.
 *
 * Defines the plugin's activation procedure.
 *
 * @package wordpress/wp-feature-notifications
 */
class Activator {

	/**
	 * The default options array.
	 *
	 * @var array $default_options
	 */
	private static $default_options = array();

	/**
	 * Initialize the default options.
	 *
	 * @return void
	 */
	public static function init_options() {

		self::$default_options = array(
			'version'           => WP_NOTIFICATION_CENTER_PLUGIN_VERSION,
			'max_lifespan'      => 1000 * 60 * 60 * 24 * 31 * 6, // 6 months
			'delete_on_dismiss' => false,
		);
	}

	/**
	 * Create or Update the WP_Notifications options.
	 *
	 * @return void
	 */
	public static function update_options() {

		self::init_options();

		$options = get_option( 'wp_notifications_options' );

		if ( false !== $options ) {

			// Update the plugin options but add the new options automatically
			if ( isset( $options['version'] ) ) {
				unset( $options['version'] );
			}

			// Merge previous options, preserve the previously modified options as default.
			$new_options = array_merge( self::$default_options, $options );

			update_option( 'wp_notifications_options', $new_options );
		} else {
			// If the plugin options are missing, initialize the plugin with the default options.
			$new_options = array_merge( self::$default_options );

			add_option( 'wp_notifications_options', $new_options );
		}
	}

	/**
	 * Install the WP Notifications plugin.
	 *
	 * Create the plugin's database tables and options
	 *
	 * @return void
	 */
	public static function install() {
		global $wpdb;

		// Engage multisite if in the middle of turning it on from network.php.
		$is_multisite = is_multisite() || ( defined( 'WP_INSTALLING_NETWORK' ) && WP_INSTALLING_NETWORK );

		if ( $is_multisite ) {
			// Get all blogs in the network and uninstall the plugin on each one.
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

			// Loop over the individual sites and create tables for each.
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );

				self::create_tables();

				restore_current_blog();
			}
		}

		// Always create the main site database tables and options.
		self::create_tables();
	}

	/**
	 * Activate the WP Notifications plugin.
	 *
	 * @return void
	 */
	public static function activate() {
		self::install();
		set_transient( 'wp_notifications_activation', true );
	}

	/**
	 * Create the WP Notifications tables and options.
	 *
	 * @return void
	 */
	public static function create_tables() {
		$db_version = get_option( 'wp_notifications_db_version' );

		if ( ! $db_version ) {
			self::create_tables_v1();
			update_option( 'wp_notifications_db_version', WP_NOTIFICATION_CENTER_DB_VERSION );
		}

		// If the options do not exist then create them
		self::update_options();
	}

	/**
	 * Create v1 WP Notifications tables and options.
	 *
	 * @return void
	 */
	public static function create_tables_v1() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();

		$tables = $wpdb->get_results( 'SHOW TABLES' );

		// Create the messages table
		if ( ! in_array( $wpdb->prefix . 'notifications_messages', $tables, true ) ) {
			$messages_sql = 'CREATE TABLE `' . $wpdb->prefix . "notifications_messages` (
				`id` BIGINT(20) NOT NULL,
				`channel_id` BIGINT(20) NOT NULL,
				`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
				`expires_at` DATETIME NULL,
				`severity` VARCHAR(16) NULL,
				`title_key` VARCHAR(127) NULL,
				`message_key` VARCHAR(127) NULL,
				`meta` TEXT NULL,
				PRIMARY KEY  (`id`),
				KEY `channel_id` (`channel_id` ASC)
			) $charset_collate;\n";

			dbDelta( $messages_sql );
		}

		// Create the channels table
		if ( ! in_array( $wpdb->prefix . 'notifications_channels', $tables, true ) ) {
			$channels_sql = 'CREATE TABLE `' . $wpdb->prefix . "notifications_channels` (
				`id` BIGINT(20) NOT NULL,
				`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
				`source` VARCHAR(127) NULL,
				`name_key` VARCHAR(127) NULL,
				`description_key` VARCHAR(127) NULL,
				`role` VARCHAR(127) NULL,
				`meta` TEXT NULL,
				PRIMARY KEY  (`id`)
			) $charset_collate;\n";

			dbDelta( $channels_sql );
		}

		// Create the subscriptions table
		if ( ! in_array( $wpdb->prefix . 'notifications_subscriptions', $tables, true ) ) {
			$subscriptions_sql = 'CREATE TABLE `' . $wpdb->prefix . "notifications_subscriptions` (
				`user_id` BIGINT(20) NOT NULL,
				`channel_id` BIGINT(20) NOT NULL,
				`snoozed_until` DATETIME NULL,
				KEY `channel_id` (`channel_id` ASC),
				KEY `user_id` (`user_id` ASC)
			) $charset_collate;\n";

			dbDelta( $subscriptions_sql );
		}

		// Create the queue table
		if ( ! in_array( $wpdb->prefix . 'notifications_queue', $tables, true ) ) {
			$queue_sql = 'CREATE TABLE `' . $wpdb->prefix . "notifications_queue` (
				`message_id` BIGINT(20) NOT NULL,
				`user_id` BIGINT(20) NOT NULL,
				`dismissed_at` DATETIME NULL,
				`displayed_at` DATETIME NULL,
				KEY `message_id` (`message_id` ASC),
				KEY `user_id` (`user_id` ASC)
			) $charset_collate;\n";

			dbDelta( $queue_sql );
		}
	}
}
