<?php
/**
 * Notifications API:Serde class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Helper;

use DateTime;

/**
 * Class Serde
 *
 * SERialization and DEserialization helper static class.
 */
class Serde {

	/**
	 * Maybe serialize a DateTime as an ISO 8601 date string.
	 *
	 * @param DateTime|null $date The possible DateTime object to serialize.
	 *
	 * @return string|null Maybe an ISO 8601 date string.
	 */
	public static function maybe_serialize_json_date( $date ) {
		if ( null === $date ) {
			return null;
		}

		return $date->format( DateTime::ATOM );
	}

	/**
	 * Maybe deserialize a datetime string in MySQL format.
	 *
	 * @param string|DateTime|null $date The possible MySQL datetime to deserialize.
	 *
	 * @return DateTime|null Maybe a DateTime object.
	 */
	public static function maybe_deserialize_mysql_date( $date ) {
		if ( null === $date ) {
			return null;
		}

		if ( $date instanceof DateTime ) {
			return $date;
		}

		if ( is_string( $date ) ) {
			$date = DateTime::createFromFormat( 'Y-m-d H:i:s', $date );

			if ( false === $date ) {
				$date = null;
			}
		}

		return $date;
	}
}
