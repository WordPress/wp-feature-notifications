<?php

namespace WP\Notifications\Persistence;

use DateTimeInterface;

use WP\Notifications;
use WP\Notifications\Factory;
use WP\Notifications\Model;

class Wpdb_Notification_Repository extends Abstract_Notification_Repository {

	/**
	 * Find a user's notification by message ID.
	 *
	 * @param int $message_id Message ID.
	 * @param int $user_id    User who owns the notification.
	 *
	 * @return array{notification: Model\Notification, message: Model\Message}|null
	 *         The notification and its message, or null if not found.
	 */
	public function find_by_id( int $message_id, int $user_id ): ?array {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT m.*, q.user_id, q.dismissed_at, q.displayed_at
				FROM {$wpdb->prefix}notifications_queue AS q
				INNER JOIN {$wpdb->prefix}notifications_messages AS m ON m.id = q.message_id
				WHERE q.message_id = %d AND q.user_id = %d
				LIMIT 1",
				$message_id,
				$user_id
			),
			ARRAY_A
		);

		if ( null === $row ) {
			return null;
		}

		$meta = json_decode( (string) $row['meta'], true );

		// Table columns win over the same keys in meta.
		$message = ( new Factory\Message() )->make(
			array_merge(
				is_array( $meta ) ? $meta : array(),
				array(
					'channel_title' => $row['channel_title'],
					'created_at'    => $row['created_at'],
					'expires_at'    => $row['expires_at'],
					'id'            => (int) $row['id'],
					'message'       => $row['message'],
					'severity'      => $row['severity'],
					'title'         => $row['title'],
				)
			)
		);

		$notification = ( new Factory\Notification() )->make(
			array(
				'channel_name' => $row['channel_name'],
				'message_id'   => (int) $row['id'],
				'user_id'      => (int) $row['user_id'],
				'created_at'   => $row['created_at'],
				'dismissed_at' => $row['dismissed_at'],
				'displayed_at' => $row['displayed_at'],
				'expires_at'   => $row['expires_at'],
			)
		);

		return array(
			'notification' => $notification,
			'message'      => $message,
		);
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
