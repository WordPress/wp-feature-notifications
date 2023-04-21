<?php
/**
 * Notifications API:Activator class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications;

use WP\Notifications\Database;

/**
 * Class Activator.
 *
 * Defines the plugin's activation procedure.
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
			'version'           => WP_FEATURE_NOTIFICATION_PLUGIN_VERSION,
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
			update_option( 'wp_notifications_db_version', WP_FEATURE_NOTIFICATION_DB_VERSION );
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
		require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/database/class-schema.php';

		$tables = $wpdb->get_results( 'SHOW TABLES' );

		// Create the messages table
		if ( ! in_array( $wpdb->prefix . 'notifications_messages', $tables, true ) ) {
			$messages_sql = Database\Schema::messages_table_v1();
			dbDelta( $messages_sql );
		}

		// Create the subscriptions table
		if ( ! in_array( $wpdb->prefix . 'notifications_subscriptions', $tables, true ) ) {
			$subscriptions_sql = Database\Schema::subscriptions_table_v1();
			dbDelta( $subscriptions_sql );
		}

		// Create the queue table
		if ( ! in_array( $wpdb->prefix . 'notifications_queue', $tables, true ) ) {
			$queue_sql = Database\Schema::queue_table_v1();
			dbDelta( $queue_sql );
		}
	}
}
