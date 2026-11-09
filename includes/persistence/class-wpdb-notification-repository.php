<?php

namespace WP\Notifications\Persistence;

use DateTimeInterface;
use WP\Notifications\Model;
use WP_Error;

/**
 * Database repository placeholder; persistence queries are not implemented yet.
 */
class Wpdb_Notification_Repository extends Abstract_Notification_Repository {

	/**
	 * {@inheritDoc}
	 */
	public function find_by_id( int $message_id, int $user_id ): ?array {
		// TODO: Implement query.
		return null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function find_by_user( int $user_id, array $args = array() ): array {
		// TODO: Implement query.
		return array();
	}

	/**
	 * {@inheritDoc}
	 */
	public function count_by_user( int $user_id, array $args = array() ): int {
		// TODO: Implement query.
		return 0;
	}

	/**
	 * {@inheritDoc}
	 */
	public function find_by_date_range(
		int $user_id,
		DateTimeInterface $start,
		DateTimeInterface $end,
		$order = Order::DESCENDING,
		$pagination = 10,
		$offset = 0
	): array {
		// TODO: Implement query.
		return array();
	}

	/**
	 * {@inheritDoc}
	 */
	public function add( Model\Message $message, string $channel_name, array $user_ids, string $context = 'adminbar' ) {
		// TODO: Implement query.
		return new WP_Error( 'not_implemented', __( 'Notification persistence is not implemented yet.', 'wp-feature-notifications' ) );
	}

	/**
	 * {@inheritDoc}
	 */
	public function dismiss( int $message_id, int $user_id ): bool {
		// TODO: Implement query.
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function mark_displayed( int $message_id, int $user_id ): bool {
		// TODO: Implement query.
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove( int $message_id, int $user_id ): bool {
		// TODO: Implement query.
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function purge_orphans(): int {
		// TODO: Implement query.
		return 0;
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
