<?php

namespace WP\Notifications\Persistence;

use DateTimeInterface;

use WP\Notifications;

class Wpdb_Notification_Repository extends Abstract_Notification_Repository {

	/**
	 * Find a notification by ID.
	 *
	 * @param int $id ID to look for.
	 *
	 * @return Notifications\Notification|false Notification that matches the ID, or
	 *                                          false if not found.
	 */
	public function find_by_id( $id ) {
		if ( ! is_int( $id ) ) {
			return false;
		}

		// TODO: Implement query.
		return false;
	}

	/**
	 * Find all notifications for a given date period.
	 *
	 * @param DateTimeInterface $start      Start date of the date range.search.
	 * @param DateTimeInterface $end        End date of the date range.search.
	 * @param string            $order      Optional. Sorting order, defaults to
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
	) {
		// TODO: Implement query.
		return array();
	}

	/**
	 * Add a new notification to the repository.
	 *
	 * @param Notifications\Notification $notification Notification to add.
	 *
	 * @return int ID that the notification was stored under.
	 */
	public function add( Notifications\Notification $notification ) {
		// TODO: Implement query.
		return -1;
	}

	/**
	 * Remove a user's notification.
	 *
	 * Only the user's queue row is removed, the message itself can still
	 * belong to other users.
	 *
	 * @param int $message_id Message ID.
	 * @param int $user_id    User who owns the notification.
	 *
	 * @return bool Whether a notification was removed.
	 */
	public function remove( int $message_id, int $user_id ): bool {
		global $wpdb;

		$deleted = $wpdb->delete(
			$wpdb->prefix . 'notifications_queue',
			array(
				'message_id' => $message_id,
				'user_id'    => $user_id,
			),
			array( '%d', '%d' )
		);

		return ! empty( $deleted );
	}
}
