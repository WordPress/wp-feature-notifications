<?php

namespace WP\Notifications\Tests;

use DateInterval;
use DateTime;
use DateTimeInterface;
use WP\Notifications\Model;
use WP\Notifications\Persistence\Order;
use WP\Notifications\Persistence\Wpdb_Notification_Repository;

class Test_Notification_Repository extends TestCase {

	/**
	 * The convenience query must preserve the user scope and pagination.
	 */
	public function test_find_latest_forwards_user_and_date_range() {
		$repository = new class() extends Wpdb_Notification_Repository {
			public $query;

			public function find_by_date_range(
				int $user_id,
				DateTimeInterface $start,
				DateTimeInterface $end,
				$order = Order::DESCENDING,
				$pagination = 10,
				$offset = 0
			): array {
				$this->query = func_get_args();
				return array(
					array(
						'notification' => new Model\Notification(),
						'message'      => new Model\Message(),
					),
				);
			}
		};

		$before = new DateTime( 'now' );
		$result = $repository->find_latest( 42, new DateInterval( 'P2D' ), 5, 10 );
		$after  = new DateTime( 'now' );

		$this->assertSame( 42, $repository->query[0] );
		$this->assertEquals( $repository->query[2], ( clone $repository->query[1] )->add( new DateInterval( 'P2D' ) ) );
		$this->assertTrue( $repository->query[2] >= $before && $repository->query[2] <= $after );
		$this->assertSame( Order::DESCENDING, $repository->query[3] );
		$this->assertSame( 5, $repository->query[4] );
		$this->assertSame( 10, $repository->query[5] );
		$this->assertInstanceOf( Model\Notification::class, $result[0]['notification'] );
		$this->assertInstanceOf( Model\Message::class, $result[0]['message'] );
	}
}
