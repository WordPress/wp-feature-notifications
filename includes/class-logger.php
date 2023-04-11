<?php
/**
 * Notifications API:Logger class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications;

/**
 * Class Logger
 *
 * Defines the plugin's conditional logging logic.
 */
class Logger {
	/**
	 * The plugin prefix to log messages.
	 *
	 * @var string
	 */
	public static $prefix = 'WP_NOTIFICATIONS: ';

	/**
	 * The log level.
	 *
	 * @var int 0 = "always" | 1 = "error" | 2 = "warning" | 3 = "info"
	 */
	public static $level = 1;

	/**
	 * Log an info level message.
	 *
	 * @param string|array $data - The string/array to log.
	 *
	 * @return void
	 */
	public static function info( $data ) {
		self::maybe_log( $data, 3 );
	}

	/**
	 * Log a warning level message.
	 *
	 * @param string|array $data - The string/array to log.
	 *
	 * @return void
	 */
	public static function warning( $data ) {
		self::maybe_log( $data, 2 );
	}

	/**
	 * Log an error level message.
	 *
	 * @param string|array $data - The string/array to log.
	 *
	 * @return void
	 */
	public static function error( $data ) {
		self::maybe_log( $data, 1 );
	}

	/**
	 * Always log regardless of log level.
	 *
	 * @param string|array $data - The string/array to log.
	 *
	 * @return void
	 */
	public static function log( $data ) {
		self::maybe_log( $data, 0 );
	}

	/**
	 * Conditionally log based on `WP_DEBUG` and log level.
	 *
	 * @param string|array $data  The string/array to log.
	 * @param int          $level The log level. See `Logger::$level`.
	 *
	 * @return void
	 */
	protected static function maybe_log( $data, $level ) {
		if ( WP_DEBUG || $level <= self::$level ) {
		  // phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log(
				is_string( $data )
				? self::$prefix . $data
				// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r
				: self::$prefix . print_r( $data, true )
			);
		}
	}
}
