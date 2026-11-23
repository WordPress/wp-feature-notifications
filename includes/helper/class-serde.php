<?php
/**
 * Notifications API:Serde class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Helper;

use DateTime;
use DateTimeZone;

/**
 * Class Serde
 *
 * SERialization and DEserialization helper static class.
 *
 * All dates are handled in UTC, regardless of the PHP default timezone.
 */
class Serde {

	/**
	 * Maybe serialize a DateTime as an ISO 8601 date string in UTC.
	 *
	 * @param DateTime|null $date The possible DateTime object to serialize.
	 *
	 * @return string|null Maybe an ISO 8601 date string.
	 */
	public static function maybe_serialize_json_date( $date ) {
		if ( null === $date ) {
			return null;
		}

		return self::to_utc( $date )->format( DateTime::ATOM );
	}

	/**
	 * Maybe deserialize a datetime string in MySQL format.
	 *
	 * The string is assumed to be in UTC, as dates are always stored in UTC.
	 *
	 * @param string|DateTime|null $date The possible MySQL datetime to deserialize.
	 *
	 * @return DateTime|null Maybe a DateTime object in UTC.
	 */
	public static function maybe_deserialize_mysql_date( $date ) {
		if ( null === $date ) {
			return null;
		}

		if ( $date instanceof DateTime ) {
			return self::to_utc( $date );
		}

		if ( ! is_string( $date ) ) {
			return null;
		}

		// The `!` resets the fields not in the format, such as microseconds.
		$parsed = DateTime::createFromFormat( '!Y-m-d H:i:s', $date, new DateTimeZone( 'UTC' ) );
		$errors = DateTime::getLastErrors();

		// Reject dates PHP would silently roll over, e.g. `2024-02-30`.
		if ( false === $parsed || ( is_array( $errors ) && $errors['warning_count'] > 0 ) ) {
			return null;
		}

		return $parsed;
	}

	/**
	 * Get a copy of a DateTime converted to UTC.
	 *
	 * @param DateTime $date The DateTime to convert.
	 *
	 * @return DateTime A new DateTime object in UTC.
	 */
	private static function to_utc( DateTime $date ): DateTime {
		$utc = clone $date;

		return $utc->setTimezone( new DateTimeZone( 'UTC' ) );
	}
}
