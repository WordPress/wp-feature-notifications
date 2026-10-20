<?php
/**
 * Unit tests covering WP/Notifications/Notification_Controller functionality.
 */
class WP_Test_REST_Notification_Controller extends WP_Test_REST_Controller_Testcase {
	private $user_id;

	public static function setUpBeforeClass(): void {
		parent::setUpBeforeClass();

		require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-activator.php';
		WP\Notifications\Activator::create_tables_v1();
	}

	public function set_up() {
		parent::set_up();

		global $wpdb;

		$wpdb->query( "DELETE FROM {$wpdb->prefix}notifications_queue" );
		$wpdb->query( "DELETE FROM {$wpdb->prefix}notifications_messages" );
		$this->user_id = self::factory()->user->create();
		wp_set_current_user( $this->user_id );
	}

	public function tear_down() {
		wp_set_current_user( 0 );

		parent::tear_down();
	}

	public function test_register_routes() {
		$routes = rest_get_server()->get_routes();

		$this->assertArrayHasKey( '/wp-notifications/v1/notifications/(?P<id>[\d]+)', $routes );
	}

	public function test_context_param() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_get_items() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_get_item() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_create_item() {
		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix . 'notifications_messages',
			array(
				'id'            => 123,
				'channel_name'  => 'test/channel',
				'channel_title' => 'Test channel',
			),
			array( '%d', '%s', '%s' )
		);

		$other_user_id = self::factory()->user->create();
		$request       = new WP_REST_Request( 'POST', '/wp-notifications/v1/notifications' );
		$request->set_body_params(
			array(
				'id'      => 123,
				'user_id' => $other_user_id,
			)
		);
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 201, $response->get_status() );
		$this->assertSame( 123, $response->get_data()['id'] );
		$this->assertSame(
			$this->user_id,
			(int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT user_id FROM {$wpdb->prefix}notifications_queue WHERE message_id = %d",
					123
				)
			)
		);
	}

	public function test_create_item_requires_authentication() {
		wp_set_current_user( 0 );

		$request = new WP_REST_Request( 'POST', '/wp-notifications/v1/notifications' );
		$request->set_body_params( array( 'id' => 123 ) );
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 401, $response->get_status() );
	}

	public function test_create_item_requires_an_existing_message() {
		$request = new WP_REST_Request( 'POST', '/wp-notifications/v1/notifications' );
		$request->set_body_params( array( 'id' => 123 ) );
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 404, $response->get_status() );
	}

	public function test_update_item() {
		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix . 'notifications_queue',
			array(
				'message_id' => 123,
				'user_id'    => $this->user_id,
			),
			array( '%d', '%d' )
		);

		$request = new WP_REST_Request( 'PUT', '/wp-notifications/v1/notifications/123' );
		$request->set_body_params(
			array(
				'dismissed_at' => '2026-10-15T12:30:00+02:00',
				'displayed_at' => '2026-10-15T12:00:00+02:00',
			)
		);
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );
		$this->assertSame(
			'2026-10-15 10:30:00',
			$wpdb->get_var(
				$wpdb->prepare(
					"SELECT dismissed_at FROM {$wpdb->prefix}notifications_queue WHERE message_id = %d AND user_id = %d",
					123,
					$this->user_id
				)
			)
		);
		$this->assertSame(
			'2026-10-15 10:00:00',
			$wpdb->get_var(
				$wpdb->prepare(
					"SELECT displayed_at FROM {$wpdb->prefix}notifications_queue WHERE message_id = %d AND user_id = %d",
					123,
					$this->user_id
				)
			)
		);
	}

	public function test_update_item_requires_a_state_field() {
		$request  = new WP_REST_Request( 'PUT', '/wp-notifications/v1/notifications/123' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 400, $response->get_status() );
	}

	public function test_update_item_requires_authentication() {
		wp_set_current_user( 0 );

		$request = new WP_REST_Request( 'PUT', '/wp-notifications/v1/notifications/123' );
		$request->set_body_params(
			array( 'dismissed_at' => '2026-10-15T12:30:00Z' )
		);
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 401, $response->get_status() );
	}

	public function test_update_item_does_not_update_another_users_notification() {
		global $wpdb;

		$other_user_id = self::factory()->user->create();
		$wpdb->insert(
			$wpdb->prefix . 'notifications_queue',
			array(
				'message_id' => 123,
				'user_id'    => $other_user_id,
			),
			array( '%d', '%d' )
		);

		$request = new WP_REST_Request( 'PUT', '/wp-notifications/v1/notifications/123' );
		$request->set_body_params(
			array( 'dismissed_at' => '2026-10-15T12:30:00Z' )
		);
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 404, $response->get_status() );
		$this->assertNull(
			$wpdb->get_var(
				$wpdb->prepare(
					"SELECT dismissed_at FROM {$wpdb->prefix}notifications_queue WHERE message_id = %d AND user_id = %d",
					123,
					$other_user_id
				)
			)
		);
	}

	public function test_delete_item() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_prepare_item() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_registered_query_params() {
		$request  = new WP_REST_Request( 'OPTIONS', '/wp-notifications/v1/notifications' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$keys     = array_keys( $data['endpoints'][0]['args'] );
		sort( $keys );
		$this->assertSame(
			array(
				'channel',
				'context',
				'offset',
				'order',
				'orderby',
				'page',
				'per_page',
				'search',
				'status',
			),
			$keys
		);
	}

	public function test_get_item_schema() {
		$request    = new WP_REST_Request( 'OPTIONS', '/wp-notifications/v1/notifications' );
		$response   = rest_get_server()->dispatch( $request );
		$data       = $response->get_data();
		$properties = $data['schema']['properties'];
		$this->assertCount( 18, $properties );
		$this->assertArrayHasKey( 'accept_label', $properties );
		$this->assertArrayHasKey( 'accept_link', $properties );
		$this->assertArrayHasKey( 'channel_name', $properties );
		$this->assertArrayHasKey( 'channel_title', $properties );
		$this->assertArrayHasKey( 'context', $properties );
		$this->assertArrayHasKey( 'created_at', $properties );
		$this->assertArrayHasKey( 'dismiss_label', $properties );
		$this->assertArrayHasKey( 'dismissed_at', $properties );
		$this->assertArrayHasKey( 'displayed_at', $properties );
		$this->assertArrayHasKey( 'expires_at', $properties );
		$this->assertArrayHasKey( 'icon', $properties );
		$this->assertArrayHasKey( 'id', $properties );
		$this->assertArrayHasKey( 'is_dismissible', $properties );
		$this->assertArrayHasKey( 'message', $properties );
		$this->assertArrayHasKey( 'severity', $properties );
		$this->assertArrayHasKey( 'status', $properties );
		$this->assertArrayHasKey( 'title', $properties );
		$this->assertArrayHasKey( 'user_id', $properties );
	}
}
