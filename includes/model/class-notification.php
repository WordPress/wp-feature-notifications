<?php
/**
 * Notifications API:Notification class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Model;

use DateTime;
use JsonSerializable;
use WP\Notifications\Helper;

/**
 * Class representing a notification.
 */
class Notification implements JsonSerializable {

	/**
	 * Channel name, including namespace, the notification belongs to.
	 */
	protected string $channel_name;

	/**
	 * Display context of the notification.
	 */
	protected ?string $context;

	/**
	 * Datetime at which the notification was created.
	 */
	protected ?DateTime $created_at;

	/**
	 * Datetime at which the notification was dismissed.
	 */
	protected ?DateTime $dismissed_at;

	/**
	 * Datetime at which the notification was first displayed to the user.
	 *
	 * Intended to facility polling for new notifications from the client.
	 */
	protected ?DateTime $displayed_at;

	/**
	 * Datetime at which the notification expires.
	 *
	 * Intended to allow notification emitters to specify when a notification can be automatically
	 * disposed of in UTC time.
	 */
	protected ?DateTime $expires_at;

	/**
	 * Database ID of the message related to the notification.
	 */
	protected int $message_id;

	/**
	 * Database ID of the user the notification belongs to.
	 */
	protected int $user_id;

	/**
	 * Constructor.
	 *
	 * Instantiates a Notice object.
	 *
	 * @param string     $channel_name Channel name, including namespace, the notification
	 *                                 was emitted from.
	 * @param int        $message_id   ID of the message related to the notification.
	 * @param int        $user_id      ID of the user the notification belongs to.
	 * @param ?string    $context      Optional display context of the notification.
	 *                                 Default `'adminbar'`
	 * @param ?DateTime  $created_at   Optional datetime at which the notification was created.
	 * @param ?DateTime  $dismissed_at Optional datetime  t which the notification was dismissed.
	 * @param ?DateTime  $displayed_at Optional datetime at which the notification was first displayed.
	 * @param ?DateTime  $expires_at   Optional datetime at which the notification expires.
	 */
	public function __construct(
		$channel_name,
		$message_id,
		$user_id,
		$context = 'admin',
		$created_at = null,
		$dismissed_at = null,
		$displayed_at = null,
		$expires_at = null
	) {
		// Required properties

		$this->channel_name = $channel_name;
		$this->message_id   = $message_id;
		$this->user_id      = $user_id;

		// Optional properties

		$this->context      = $context;
		$this->created_at   = $created_at;
		$this->dismissed_at = $dismissed_at;
		$this->displayed_at = $displayed_at;
		$this->expires_at   = $expires_at;
	}

	/**
	 * Specifies data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode, which is a
	 *               value of any type other than a resource.
	 */
	public function jsonSerialize(): mixed {
		return array(
			'channel_name' => $this->channel_name,
			'context'      => $this->context,
			'created_at'   => Helper\Serde::maybe_serialize_json_date( $this->created_at ),
			'dismissed_at' => Helper\Serde::maybe_serialize_json_date( $this->dismissed_at ),
			'displayed_at' => Helper\Serde::maybe_serialize_json_date( $this->displayed_at ),
			'expires_at'   => Helper\Serde::maybe_serialize_json_date( $this->expires_at ),
			'message_id'   => $this->message_id,
			'user_id'      => $this->user_id,
		);
	}

	/**
	 * Get the namespaced channel name.
	 *
	 * @return string The namespaced channel name of the notification.
	 */
	public function get_channel_name(): string {
		return $this->channel_name;
	}

	/**
	 * Get the display context.
	 *
	 * @return ?string The display context of the notification.
	 */
	public function get_context(): ?string {
		return $this->context;
	}

	/**
	 * Get the created at datetime.
	 *
	 * @return ?DateTime The datetime at which the notification was created.
	 */
	public function get_created_at(): ?DateTime {
		return $this->created_at;
	}

	/**
	 * Get the dismissed at datetime.
	 *
	 * @return ?DateTime The datetime at which the notification was dismissed.
	 */
	public function get_dismissed_at(): ?DateTime {
		return $this->dismissed_at;
	}

	/**
	 * Get the displayed at datetime.
	 *
	 * @return ?DateTime The datetime at which the notification was first displayed.
	 */
	public function get_displayed_at(): ?DateTime {
		return $this->displayed_at;
	}

	/**
	 * Get the expires at datetime.
	 *
	 * @return ?DateTime The datetime at which the notification expires.
	 */
	public function get_expires_at(): ?DateTime {
		return $this->expires_at;
	}

	/**
	 * Get the message ID.
	 *
	 * @return int The database ID of the message related to the notification.
	 */
	public function get_message_id(): int {
		return $this->message_id;
	}

	/**
	 * Get the user ID.
	 *
	 * @return int The database ID of the user the notification belongs to.
	 */
	public function get_user_id(): int {
		return $this->user_id;
	}
}
