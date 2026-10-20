<?php

namespace WP\Notifications\Persistence;

use DateInterval;
use DateTimeInterface;

use WP\Notifications;


interface Notification_Repository {

	/**
	 * Find a notification by ID.
	 *
	 * @param int $id ID to look for.
	 *
	 * @return Notifications\Notification|false Notification that matches the ID, or
	 *                                          false if not found.
	 */
	public function find_by_id( $id );

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
	 * @param Notifications\Model\Notification $notification Notification to add.
	 *
	 * @return int|false Notification message ID, zero if the message does not exist, or false on error.
	 */
	public function add( Notifications\Model\Notification $notification );

	/**
	 * Update state timestamps for a user's notification.
	 *
	 * @param int   $id      Notification message ID.
	 * @param int   $user_id ID of the notification owner.
	 * @param array $state   State timestamp fields to update.
	 *
	 * @return int|false One when the notification exists, zero when it does not, or false on error.
	 */
	public function update_state( $id, $user_id, array $state );
}
