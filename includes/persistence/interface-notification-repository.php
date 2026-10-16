<?php

namespace WP\Notifications\Persistence;

use DateInterval;
use DateTimeInterface;
use WP\Notifications\Model;

/**
 * Stores messages and their per-user notification queue entries.
 *
 * All message IDs are scoped to the supplied user, including IDs exposed by REST.
 */
interface Notification_Repository {

	/**
	 * Find a message and its notification belonging to a user.
	 *
	 * @param int $message_id Message ID, not a surrogate queue ID.
	 * @param int $user_id    User who owns the notification.
	 * @return array{notification: Model\Notification, message: Model\Message}|null Null if not found for this user.
	 */
	public function find_by_id( int $message_id, int $user_id ): ?array;

	/**
	 * Find notifications belonging to a user.
	 *
	 * @param int   $user_id User who owns the notifications.
	 * @param array $args {
	 *     Optional query arguments.
	 *
	 *     @type string $status          Notification status to match.
	 *     @type string $channel_name    Namespaced channel name to match.
	 *     @type string $context         Display context to match.
	 *     @type string $after           Lower date bound, used by polling.
	 *     @type string $before          Upper date bound.
	 *     @type bool   $include_expired Include expired notifications. Default false.
	 *     @type string $order           Sort direction.
	 *     @type string $orderby         Field to sort by.
	 *     @type int    $per_page        Number of results per page.
	 *     @type int    $offset          Offset into the result set.
	 * }
	 * @return array Array of array{notification: Model\Notification, message: Model\Message} pairs.
	 */
	public function find_by_user( int $user_id, array $args = array() ): array;

	/**
	 * Count a user's matching notifications for totals such as X-WP-Total.
	 *
	 * @param int   $user_id User who owns the notifications.
	 * @param array $args    Filters as in find_by_user(); pagination is ignored.
	 * @return int Number of matching notifications.
	 */
	public function count_by_user( int $user_id, array $args = array() ): int;

	/**
	 * Find a user's notifications within a date range.
	 *
	 * @param int               $user_id    User who owns the notifications.
	 * @param DateTimeInterface $start      Start of the date range.
	 * @param DateTimeInterface $end        End of the date range.
	 * @param string            $order      Sort direction. Default descending.
	 * @param int               $pagination Number of results per page. Default 10.
	 * @param int               $offset     Offset into the result set. Default 0.
	 * @return array Array of array{notification: Model\Notification, message: Model\Message} pairs.
	 */
	public function find_by_date_range(
		int $user_id,
		DateTimeInterface $start,
		DateTimeInterface $end,
		$order = Order::DESCENDING,
		$pagination = 10,
		$offset = 0
	): array;

	/**
	 * Find a user's latest notifications within an interval ending now.
	 *
	 * @param int          $user_id    User who owns the notifications.
	 * @param DateInterval $interval   Time interval to search.
	 * @param int          $pagination Number of results per page. Default 10.
	 * @param int          $offset     Offset into the result set. Default 0.
	 * @return array Array of array{notification: Model\Notification, message: Model\Message} pairs.
	 */
	public function find_latest(
		int $user_id,
		DateInterval $interval,
		$pagination = 10,
		$offset = 0
	): array;

	/**
	 * Store a message and fan it out to the supplied users.
	 *
	 * @param Model\Message $message      Message to store.
	 * @param string        $channel_name Namespaced channel name.
	 * @param int[]         $user_ids     Recipient user IDs.
	 * @param string        $context      Display context. Default 'adminbar'.
	 * @return int|\WP_Error Stored message ID, or an error on failure.
	 */
	public function add( Model\Message $message, string $channel_name, array $user_ids, string $context = 'adminbar' );

	/**
	 * Dismiss a user's notification.
	 *
	 * @param int $message_id Message ID.
	 * @param int $user_id    User who owns the notification.
	 * @return bool Whether the operation succeeded.
	 */
	public function dismiss( int $message_id, int $user_id ): bool;

	/**
	 * Mark a user's notification as displayed.
	 *
	 * @param int $message_id Message ID.
	 * @param int $user_id    User who owns the notification.
	 * @return bool Whether the operation succeeded.
	 */
	public function mark_displayed( int $message_id, int $user_id ): bool;

	/**
	 * Remove a user's notification.
	 *
	 * @param int $message_id Message ID.
	 * @param int $user_id    User who owns the notification.
	 * @return bool Whether the operation succeeded.
	 */
	public function remove( int $message_id, int $user_id ): bool;

	/**
	 * Purge messages with no remaining notification queue entries.
	 *
	 * @return int Number of messages removed.
	 */
	public function purge_orphans(): int;
}
