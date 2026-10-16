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
}
