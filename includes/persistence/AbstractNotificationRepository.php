<?php

abstract class WPNotify_AbstractNotificationRepository
	implements WPNotify_NotificationRepository {

	/**
	 * Find the latest notifications for a given date range.
	 *
	 * @param DateInterval $interval   Time interval to search.
	 * @param int          $pagination Optional. Number of elements per page.
	 *                                 Defaults to 10.
	 * @param int          $offset     Optional. Offset into the result set.
	 *                                 Defaults to 0.
	 *
	 * @return WPNotify_Notification[] Array of notifications, empty array if
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
			WPNotify_Order::DESCENDING,
			$pagination,
			$offset
		);
	}
}
