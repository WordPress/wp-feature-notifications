<?php

abstract class WP_Notify_Abstract_Notification_Repository
	implements WP_Notify_Notification_Repository {

	/**
	 * Find the latest notifications for a given date range.
	 *
	 * @param DateInterval $interval   Time interval to search.
	 * @param int          $pagination Optional. Number of elements per page.
	 *                                 Defaults to 10.
	 * @param int          $offset     Optional. Offset into the result set.
	 *                                 Defaults to 0.
	 *
	 * @return WP_Notify_Notification[] Array of notifications, empty array if
	 *                                 none found.
	 */
	public function find_latest(
		DateInterval $interval,
		$pagination = 10,
		$offset = 0
	) {
		$end   = new DateTime( 'now' );
		$start = clone $end;
		$start->sub( $interval );

		return $this->find_by_date_range(
			$start,
			$end,
			WP_Notify_Order::DESCENDING,
			$pagination,
			$offset
		);
	}
}
