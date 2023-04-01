<?php

namespace WP\Notifications;

/**
 * Class to simplify logging of plugin information.
 *
 * @since 0.1.0
 */
class Logger {
	/**
	 * The plugin prefix to log messages.
	 */
	public static $prefix = 'WP_NOTIFICATIONS: ';

	/**
	 * The log level.
	 *
	 * @since 0.1.0
	 *
	 * @var int 0 = "always" | 1 = "error" | 2 = "warning" | 3 = "info"
	 */
	public static $level = 1;

	/**
	 * Log an info level message.
	 *
	 * @since 0.1.0
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
	 * @since 0.1.0
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
	 * @since 0.1.0
	 *
	 * @param string|array $data - The string/array to log.
	 *
	 * @return void
	 */
	public static function error( $data ) {
		self::maybe_log( $data, 1 );
	}

	/**
	 * Log a message.
	 *
	 * Always logs a message regardless of log level.
	 *
	 * @since 0.1.0
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
	 * @since 0.1.0
	 *
	 * @param string|array $data  The string/array to log.
	 * @param int          $level The log level. See `Logger::$level`.
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
