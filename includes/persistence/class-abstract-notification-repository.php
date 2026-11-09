<?php

namespace WP\Notifications\Persistence;

use DateTime;
use DateInterval;

abstract class Abstract_Notification_Repository implements Notification_Repository {

	/**
	 * Find the latest notifications for a given date range.
	 *
	 * @param int          $user_id    User who owns the notifications.
	 * @param DateInterval $interval   Time interval to search.
	 * @param int          $pagination Optional. Number of elements per page.
	 *                                 Defaults to 10.
	 * @param int          $offset     Optional. Offset into the result set.
	 *                                 Defaults to 0.
	 *
	 * @return array Array of notification/message model pairs, empty if none found.
	 */
	public function find_latest(
		int $user_id,
		DateInterval $interval,
		$pagination = 10,
		$offset = 0
	): array {
		$end   = new DateTime( 'now' );
		$start = clone $end;
		$start->sub( $interval );

		return $this->find_by_date_range(
			$user_id,
			$start,
			$end,
			Order::DESCENDING,
			$pagination,
			$offset
		);
	}
}
