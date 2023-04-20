<?php
/**
 * Notifications API:Subscription class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Model;

use DateTime;
use JsonSerializable;
use WP\Notifications\Helper;

/**
 * Class representing a notification channel subscription.
 */
class Subscription implements JsonSerializable {

	/**
	 * Channel name, including namespace, the subscription belongs to.
	 *
	 * Used to relate a user and notification channel at the time of notice emission.
	 */
	protected string $channel_name;

	/**
	 * Datetime at which the subscription was created.
	 */
	protected ?DateTime $created_at;

	/**
	 * ID of the user the subscription belongs to.
	 */
	protected int $user_id;

	// Optional properties

	/**
	 * Snoozed until option of the subscription.
	 *
	 * Intended to allow users a quick method to disable a channel subscription for
	 * a set amount of time.
	 */
	protected ?DateTime $snoozed_until;

	/**
	 * Constructor.
	 *
	 * Instantiates a Subscription object.
	 *
	 * @param string    $channel_name  Channel name, including namespace, the subscription belongs to.
	 * @param int       $user_id       ID of the user the subscription belongs to.
	 * @param ?DateTime $created_at    Optional datetime at which the subscription was created.
	 * @param ?DateTime $snoozed_until Optional snoozed until datetime of the subscription.
	 *
	 */
	public function __construct( $channel_name, $user_id, $created_at = null, $snoozed_until = null ) {
		$this->channel_name = $channel_name;
		$this->user_id      = $user_id;

		// Optional properties

		$this->created_at    = $created_at;
		$this->snoozed_until = $snoozed_until;
	}

	/**
	 * Specifies data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode, which is a
	 *               value of any type other than a resource.
	 */
	public function jsonSerialize(): mixed {
		return array(
			'channel_name'  => $this->channel_name,
			'created_at'    => Helper\Serde::maybe_serialize_json_date( $this->created_at ),
			'user_id'       => $this->user_id,
			'snoozed_until' => Helper\Serde::maybe_serialize_json_date( $this->snoozed_until ),
		);
	}

	/**
	 * Get the namespaced channel name.
	 *
	 * @return string The namespaced channel name of the subscription.
	 */
	public function get_channel_name(): string {
		return $this->channel_name;
	}

	/**
	 * Get the created at datetime.
	 *
	 * @return ?DateTime The datetime at which the subscription was created.
	 */
	public function get_created_at(): ?DateTime {
		return $this->created_at;
	}

	/**
	 * Get the user ID.
	 *
	 * @return int The user ID of the subscription.
	 */
	public function get_user_id(): int {
		return $this->user_id;
	}

	/**
	 * Get the snoozed until option.
	 *
	 * @return ?DateTime The snoozed until option of the subscription.
	 */
	public function get_snoozed_until(): ?DateTime {
		return $this->snoozed_until;
	}
}
