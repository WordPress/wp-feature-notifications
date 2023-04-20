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
	 *     @type string               $channel_name  Namespaced channel name of the
	 *                                               subscription.
	 *     @type int                  $user_id       ID of the user the subscription
	 *                                               belongs to.
	 *     @type string|DateTime|null $created_at    Optional datetime at which the
	 *                                               subscription was created.
	 *     @type string|DateTime|null $snoozed_until Optional snoozed until datetime
	 *                                               of the subscription.
	 * }
	 *
	 * @return Model\Subscription|false A newly created instance of Subscription or false.
	 */
	public function make( $args ) {
		$parsed = wp_parse_args( $args );

		// Required properties

		$channel_name = $parsed['channel_name'];
		$user_id      = $parsed['user_id'];

		// Optional properties

		$created_at    = array_key_exists( 'created_at', $parsed ) ? $parsed['created_at'] : null;
		$snoozed_until = array_key_exists( 'snoozed_until', $parsed ) ? $parsed['snoozed_until'] : null;

		// Deserialize MySQL datetime strings.

		$created_at    = Helper\Serde::maybe_deserialize_mysql_date( $created_at );
		$snoozed_until = Helper\Serde::maybe_deserialize_mysql_date( $snoozed_until );

		$subscription = new Model\Subscription(
			$channel_name,
			$user_id,
			$created_at,
			$snoozed_until,
		);

		return $subscription;
	}
}
