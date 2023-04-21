<?php
/**
 * Notifications API:Uninstaller class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications;

/**
 * Class Uninstaller.
 *
 * Defines the plugin's uninstall procedure.
 *
 * @package wordpress/wp-feature-notifications
 */
class Uninstaller {

	/**
	 * Uninstall the plugin and reinstall.
	 *
	 * @return void
	 */
	public static function full_reset() {
		self::uninstall();

		require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-activator.php';

		Activator::install();
	}


	/**
	 * Uninstall the WP Notifications plugin.
	 *
	 * Delete the plugin's database tables and options.
	 *
	 * @return void
	 */
	public static function uninstall() {
		global $wpdb;

		// Engage multisite if in the middle of turning it on from network.php.
		$is_multisite = is_multisite() || ( defined( 'WP_INSTALLING_NETWORK' ) && WP_INSTALLING_NETWORK );

		if ( $is_multisite ) {
			// Get all blogs in the network and uninstall the plugin on each one.
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );

				self::drop_tables();
				self::delete_options();

				restore_current_blog();
			}
		}

		// Always remove the main site database tables and options.
		self::drop_tables();
		self::delete_options();
	}

	/**
	 * Drop the WP Notifications database tables.
	 *
	 * @return void
	 */
	public static function drop_tables() {
		global $wpdb;

		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'notifications_messages' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'notifications_subscriptions' );
		$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'notifications_queue' );
	}

	/**
	 * Delete the WP Notifications options.
	 *
	 * @return void
	 */
	public static function delete_options() {
		delete_option( 'wp_notifications_db_version' );
		delete_option( 'wp_notifications_options' );
	}
}
