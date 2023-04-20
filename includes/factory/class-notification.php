<?php
/**
 * Notifications API:Notifications factory class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Factory;

use DateTime;
use WP\Notifications\Framework;
use WP\Notifications\Helper;
use WP\Notifications\Model;

/**
 * Class representing a notifications factory.
 *
 * @implements Framework\Factory<Notification>
 */
class Notification extends Framework\Factory {

	/**
	 * Instantiates a Notification object.

	 * @param array|string $args     {
	 *     Array or string of arguments for creating a notification. Supported arguments are described below.
	 *
	 *     @type string               $channel_name Channel name, including namespace,
	 *                                              the notification was emitted from.
	 *     @type int                  $message_id   ID of the message related to the
	 *                                              notification.
	 *     @type int                  $user_id      ID of the user the notification
	 *                                              belongs to.
	 *     @type ?string              $context      Optional display context of the
	 *                                              notification. Default `'adminbar'`
	 *     @type string|DateTime|null $created_at   Optional datetime at which the
	 *                                              notification was created. Default `null`
	 *     @type string|DateTime|null $dismissed_at Optional datetime  t which the
	 *                                              notification was dismissed. Default `null`
	 *     @type string|DateTime|null $displayed_at Optional datetime at which the
	 *                                              notification was first displayed.
	 *                                              Default `null`
	 *     @type string|DateTime|null $expires_at   Optional datetime at which the
	 *                                              notification expires. Default `null`
	 * }
	 * @param bool         $validate Optionally validate the arguments.
	 *
	 * @return Model\Notification|false A newly created instance of Channel or false.
	 */
	public function make( $args ) {
		$parsed = wp_parse_args( $args );

		// Required properties

		$channel_name = $parsed['channel_name'];
		$message_id   = $parsed['message_id'];
		$user_id      = $parsed['user_id'];

		// Optional properties

		$context      = array_key_exists( 'context', $parsed ) ? $parsed['context'] : 'adminbar';
		$created_at   = array_key_exists( 'created_at', $parsed ) ? $parsed['created_at'] : null;
		$dismissed_at = array_key_exists( 'dismissed_at', $parsed ) ? $parsed['dismissed_at'] : null;
		$displayed_at = array_key_exists( 'displayed_at', $parsed ) ? $parsed['displayed_at'] : null;
		$expires_at   = array_key_exists( 'expires_at', $parsed ) ? $parsed['expires_at'] : null;

		// Deserialize MySQL datetime strings.

		$created_at   = Helper\Serde::maybe_deserialize_mysql_date( $created_at );
		$dismissed_at = Helper\Serde::maybe_deserialize_mysql_date( $dismissed_at );
		$displayed_at = Helper\Serde::maybe_deserialize_mysql_date( $displayed_at );
		$expires_at   = Helper\Serde::maybe_deserialize_mysql_date( $expires_at );

		$notification = new Model\Notification(
			$channel_name,
			$message_id,
			$user_id,
			$context,
			$created_at,
			$dismissed_at,
			$displayed_at,
			$expires_at
		);

		return $notification;
	}
}
