<?php

namespace WP\Notifications\Persistence;

use DateInterval;
use DateTimeInterface;

use WP\Notifications;
use WP\Notifications\Model;


interface Notification_Repository {

	/**
	 * Find a user's notification by message ID.
	 *
	 * @param int $message_id Message ID.
	 * @param int $user_id    User who owns the notification.
	 *
	 * @return array{notification: Model\Notification, message: Model\Message}|null
	 *         The notification and its message, or null if not found.
	 */
	public function find_by_id( int $message_id, int $user_id ): ?array;

	/**
	 * Find all notifications for a given date period.
	 *
	 * @param DateTimeInterface $start      Start date of the date range.search.
	 * @param DateTimeInterface $end        End date of the date range.search.
	 * @param string            $order      Optional. Sorting order, defaults
	 *                                      to
	 *                                      descending.
	 * @param int               $pagination Optional. Number of elements per
	 *                                      page. Defaults to 10.
	 * @param int               $offset     Optional. Offset into the result
	 *                                      set. Defaults to 0.
	 *
	 * @return Notifications\Notification[] Array of notifications, empty array if
	 *                                      none found.
	 */
	public function find_by_date_range(
		DateTimeInterface $start,
		DateTimeInterface $end,
		$order = Order::DESCENDING,
		$pagination = 10,
		$offset = 0
	);

	/**
	 * Find the latest notifications for a given date range.
	 *
	 * @param DateInterval $interval   Time interval to search.
	 * @param int          $pagination Optional. Number of elements per page.
	 *                                 Defaults to 10.
	 * @param int          $offset     Optional. Offset into the result set.
	 *                                 Defaults to 0.
	 *
	 * @return Notifications\Notification[] Array of notifications, empty array
	 *                                      if none found.
	 */
	public function find_latest(
		DateInterval $interval,
		$pagination = 10,
		$offset = 0
	);

	/**
	 * Add a new notification to the repository.
	 *
	 * @param Notifications\Notification $notification Notification to add.
	 *
	 * @return int ID that the notification was stored under.
	 */
	public function add( Notifications\Notification $notification );

	/**
	 * Remove a user's notification.
	 *
	 * @param int $message_id Message ID.
	 * @param int $user_id    User who owns the notification.
	 *
	 * @return bool Whether a notification was removed.
	 */
	public function remove( int $message_id, int $user_id ): bool;
}
