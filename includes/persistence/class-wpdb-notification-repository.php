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
	 * @param Notifications\Model\Notification $notification Notification to add.
	 *
	 * @return int|false Notification message ID, zero if the message does not exist, or false on error.
	 */
	public function add( Notifications\Model\Notification $notification ) {
		global $wpdb;

		$message_id = $notification->get_message_id();
		$user_id    = $notification->get_user_id();

		if ( ! is_int( $message_id ) || $message_id < 1 || ! is_int( $user_id ) || $user_id < 1 ) {
			return false;
		}

		$queue_table    = $wpdb->prefix . 'notifications_queue';
		$messages_table = $wpdb->prefix . 'notifications_messages';
		$result         = $wpdb->query(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- The table names are generated internally.
				"INSERT INTO {$queue_table} (message_id, user_id) SELECT id, %d FROM {$messages_table} WHERE id = %d",
				$user_id,
				$message_id
			)
		);

		if ( false === $result ) {
			return false;
		}

		return 0 === $result ? 0 : $message_id;
	}

	/**
	 * Update state timestamps for a user's notification.
	 *
	 * @param int   $id      Notification message ID.
	 * @param int   $user_id ID of the notification owner.
	 * @param array $state   State timestamp fields to update.
	 *
	 * @return int|false One when the notification exists, zero when it does not, or false on error.
	 */
	public function update_state( $id, $user_id, array $state ) {
		global $wpdb;

		$allowed_fields = array( 'displayed_at', 'dismissed_at' );
		$data           = array();
		$formats        = array();

		foreach ( $allowed_fields as $field ) {
			if ( array_key_exists( $field, $state ) ) {
				$data[ $field ] = $state[ $field ] ? $state[ $field ]->format( 'Y-m-d H:i:s' ) : null;
				$formats[]      = '%s';
			}
		}

		if ( empty( $data ) ) {
			return false;
		}

		$table  = $wpdb->prefix . 'notifications_queue';
		$exists = $wpdb->get_var(
			$wpdb->prepare(
				// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- The table name is generated internally.
				"SELECT 1 FROM {$table} WHERE message_id = %d AND user_id = %d LIMIT 1",
				$id,
				$user_id
			)
		);

		if ( null === $exists ) {
			return 0;
		}

		$result = $wpdb->update(
			$table,
			$data,
			array(
				'message_id' => $id,
				'user_id'    => $user_id,
			),
			$formats,
			array( '%d', '%d' )
		);

		return false === $result ? false : 1;
	}
}
