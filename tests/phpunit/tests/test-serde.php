<?php

namespace WP\Notifications\Tests;

use DateTime;
use DateTimeZone;
use WP\Notifications\Helper\Serde;

/**
 * @covers \WP\Notifications\Helper\Serde
 */
class Test_Serde extends TestCase {

	/**
	 * The PHP default timezone before each test.
	 */
	private string $default_timezone;

	/**
	 * Set up each test method.
	 */
	public function set_up() {
		parent::set_up();

		$this->default_timezone = date_default_timezone_get();

		// WordPress sets the PHP default timezone to UTC. Use another timezone to
		// make sure Serde does not depend on it.
		date_default_timezone_set( 'America/New_York' ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set
	}

	/**
	 * Tear down each test method.
	 */
	public function tear_down() {
		date_default_timezone_set( $this->default_timezone ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.timezone_change_date_default_timezone_set

		parent::tear_down();
	}

	/**
	 * Should deserialize a MySQL datetime string as UTC.
	 */
	public function test_deserialize_mysql_date_is_utc() {
		$date = Serde::maybe_deserialize_mysql_date( '2024-01-02 03:04:05' );

		$this->assertInstanceOf( DateTime::class, $date );
		$this->assertSame( 'UTC', $date->getTimezone()->getName() );
		$this->assertSame( '2024-01-02 03:04:05', $date->format( 'Y-m-d H:i:s' ) );
		$this->assertSame( 1704164645, $date->getTimestamp() );
	}

	/**
	 * Should not carry over the current microseconds into a deserialized date.
	 */
	public function test_deserialize_mysql_date_has_no_microseconds() {
		$date = Serde::maybe_deserialize_mysql_date( '2024-01-02 03:04:05' );

		$this->assertSame( '000000', $date->format( 'u' ) );
	}

	/**
	 * Should round trip a MySQL datetime string to an ISO 8601 date in UTC.
	 */
	public function test_mysql_date_round_trip() {
		$date = Serde::maybe_deserialize_mysql_date( '2024-01-02 03:04:05' );

		$this->assertSame( '2024-01-02T03:04:05+00:00', Serde::maybe_serialize_json_date( $date ) );
	}

	/**
	 * Should convert a DateTime in another timezone to UTC without changing the moment in time.
	 */
	public function test_deserialize_datetime_is_converted_to_utc() {
		$input = new DateTime( '2024-01-02 09:04:05', new DateTimeZone( 'Asia/Dhaka' ) );
		$date  = Serde::maybe_deserialize_mysql_date( $input );

		$this->assertSame( 'UTC', $date->getTimezone()->getName() );
		$this->assertSame( $input->getTimestamp(), $date->getTimestamp() );
		$this->assertSame( '2024-01-02 03:04:05', $date->format( 'Y-m-d H:i:s' ) );
	}

	/**
	 * Should not modify the DateTime passed in.
	 */
	public function test_deserialize_datetime_does_not_mutate_input() {
		$input = new DateTime( '2024-01-02 09:04:05', new DateTimeZone( 'Asia/Dhaka' ) );
		Serde::maybe_deserialize_mysql_date( $input );

		$this->assertSame( 'Asia/Dhaka', $input->getTimezone()->getName() );
	}

	/**
	 * Should serialize a DateTime in another timezone as UTC.
	 */
	public function test_serialize_json_date_is_utc() {
		$input = new DateTime( '2024-01-02 09:04:05', new DateTimeZone( 'Asia/Dhaka' ) );

		$this->assertSame( '2024-01-02T03:04:05+00:00', Serde::maybe_serialize_json_date( $input ) );
		$this->assertSame( 'Asia/Dhaka', $input->getTimezone()->getName() );
	}

	/**
	 * Should pass null through.
	 */
	public function test_null_is_passed_through() {
		$this->assertNull( Serde::maybe_deserialize_mysql_date( null ) );
		$this->assertNull( Serde::maybe_serialize_json_date( null ) );
	}

	/**
	 * Should return null for values which are not a valid MySQL datetime.
	 *
	 * @dataProvider data_invalid_mysql_dates
	 *
	 * @param mixed $input The invalid value.
	 */
	public function test_deserialize_invalid_mysql_date_returns_null( $input ) {
		$this->assertNull( Serde::maybe_deserialize_mysql_date( $input ) );
	}

	/**
	 * Data provider for test_deserialize_invalid_mysql_date_returns_null().
	 *
	 * @return array[]
	 */
	public function data_invalid_mysql_dates() {
		return array(
			'empty string'       => array( '' ),
			'not a date'         => array( 'not a date' ),
			'ISO 8601 format'    => array( '2024-01-02T03:04:05+00:00' ),
			'date only'          => array( '2024-01-02' ),
			'out of range day'   => array( '2024-02-30 00:00:00' ),
			'out of range month' => array( '2024-13-01 00:00:00' ),
			'integer'            => array( 1704164645 ),
		);
	}
}
