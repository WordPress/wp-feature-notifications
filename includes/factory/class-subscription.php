<?php
/**
 * Notifications API:Subscriptions factory class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Factory;

use DateTime;
use WP\Notifications\Framework;
use WP\Notifications\Helper;
use WP\Notifications\Model;

/**
 * Class representing a subscriptions factory
 *
 * @implements Framework\Factory<Subscription>.
 */
class Subscription extends Framework\Factory {

	/**
	 * Instantiates a Subscription object.
	 *
	 * @param array|string $args     {
	 *     Array or string of arguments for creating a subscription. Supported
	 *     arguments are described below.
	 *
	 *     @type ?string              $channel_name  Namespaced channel name of the
	 *                                               subscription.
	 *     @type ?int                 $user_id       ID of the user the subscription
	 *                                               belongs to.
	 *     @type string|DateTime|null $created_at    Optional datetime at which the
	 *                                               subscription was created.
	 *     @type string|DateTime|null $snoozed_until Optional snoozed until datetime
	 *                                               of the subscription.
	 * }
	 *
	 * @return Model\Subscription A newly created instance of Subscription or false.
	 */
	public function make( $args = array() ): Model\Subscription {
		$parsed = wp_parse_args( $args );

		// Required properties

		$channel_name = array_key_exists( 'channel_name', $parsed ) ? $parsed['channel_name'] : null;
		$user_id      = array_key_exists( 'user_id', $parsed ) ? $parsed['user_id'] : null;

		// Optional properties

		$created_at    = array_key_exists( 'created_at', $parsed ) ? $parsed['created_at'] : null;
		$snoozed_until = array_key_exists( 'snoozed_until', $parsed ) ? $parsed['snoozed_until'] : null;

		// Deserialize MySQL datetime strings.

		$created_at    = Helper\Serde::maybe_deserialize_mysql_date( $created_at );
		$snoozed_until = Helper\Serde::maybe_deserialize_mysql_date( $snoozed_until );

		return new Model\Subscription(
			$channel_name,
			$user_id,
			$created_at,
			$snoozed_until,
		);
	}
}
