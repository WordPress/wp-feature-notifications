<?php
/**
 * Unit tests covering WP/Notifications/Notification_Controller functionality.
 */
class WP_Test_REST_Notification_Controller extends WP_Test_REST_Controller_Testcase {
	protected static $user_id;
	protected static $other_user_id;

	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-activator.php';

		// Created outside the test transaction so the table is not temporary.
		WP\Notifications\Activator::create_tables_v1();

		self::$user_id       = $factory->user->create();
		self::$other_user_id = $factory->user->create();
	}

	public static function wpTearDownAfterClass() {
		self::delete_user( self::$user_id );
		self::delete_user( self::$other_user_id );
	}

	protected function add_queue_row( int $message_id, int $user_id, array $fields = array() ) {
		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix . 'notifications_queue',
			array_merge(
				array(
					'message_id' => $message_id,
					'user_id'    => $user_id,
				),
				$fields
			)
		);
	}

	protected function add_message_row( int $id ) {
		global $wpdb;

		$wpdb->insert(
			$wpdb->prefix . 'notifications_messages',
			array(
				'id'            => $id,
				'channel_name'  => 'core/test',
				'channel_title' => 'Test channel',
				'created_at'    => '2026-01-02 03:04:05',
				'severity'      => 'info',
				'title'         => 'Test title',
				'message'       => 'Test message',
				'meta'          => wp_json_encode(
					array(
						'accept_label'   => 'Open',
						'accept_link'    => 'https://example.org/',
						'icon'           => 'admin-site',
						'is_dismissible' => false,
					)
				),
			)
		);
	}

	protected function count_queue_rows( int $message_id ): int {
		global $wpdb;

		return (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->prefix}notifications_queue WHERE message_id = %d",
				$message_id
			)
		);
	}

	protected function get_schema(): array {
		$request  = new WP_REST_Request( 'OPTIONS', '/wp-notifications/v1/notifications' );
		$response = rest_get_server()->dispatch( $request );

		return $response->get_data()['schema'];
	}

	public function test_register_routes() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_context_param() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_get_items() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_get_item() {
		$this->add_message_row( 50 );
		$this->add_queue_row( 50, self::$user_id );
		wp_set_current_user( self::$user_id );

		$request  = new WP_REST_Request( 'GET', '/wp-notifications/v1/notifications/50' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		$this->assertSame( 200, $response->get_status() );
		$this->assertCount( 18, $data );
		$this->assertSame( 50, $data['id'] );
		$this->assertSame( self::$user_id, $data['user_id'] );
		$this->assertSame( 'core/test', $data['channel_name'] );
		$this->assertSame( 'Test channel', $data['channel_title'] );
		$this->assertSame( 'adminbar', $data['context'] );
		$this->assertSame( 'Test title', $data['title'] );
		$this->assertSame( 'Test message', $data['message'] );
		$this->assertSame( 'info', $data['severity'] );
		$this->assertSame( 'Open', $data['accept_label'] );
		$this->assertSame( 'https://example.org/', $data['accept_link'] );
		$this->assertSame( 'admin-site', $data['icon'] );
		$this->assertFalse( $data['is_dismissible'] );
		$this->assertNull( $data['dismiss_label'] );
		$this->assertSame( '2026-01-02T03:04:05+00:00', $data['created_at'] );
		$this->assertNull( $data['expires_at'] );
		$this->assertNull( $data['dismissed_at'] );
		$this->assertNull( $data['displayed_at'] );
		$this->assertSame( 'undisplayed', $data['status'] );
		$this->assertTrue( rest_validate_value_from_schema( $data, $this->get_schema() ) );
	}

	public function test_get_item_without_meta() {
		global $wpdb;

		$this->add_message_row( 55 );
		$wpdb->update( $wpdb->prefix . 'notifications_messages', array( 'meta' => null ), array( 'id' => 55 ) );
		$this->add_queue_row( 55, self::$user_id );
		wp_set_current_user( self::$user_id );

		$request  = new WP_REST_Request( 'GET', '/wp-notifications/v1/notifications/55' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		$this->assertSame( 200, $response->get_status() );
		$this->assertCount( 18, $data );
		$this->assertNull( $data['accept_label'] );
		$this->assertNull( $data['icon'] );
		$this->assertTrue( $data['is_dismissible'] );
		$this->assertTrue( rest_validate_value_from_schema( $data, $this->get_schema() ) );
	}

	public function test_get_item_status() {
		$this->add_message_row( 51 );
		$this->add_queue_row( 51, self::$user_id, array( 'displayed_at' => '2026-01-03 00:00:00' ) );
		$this->add_message_row( 52 );
		$this->add_queue_row(
			52,
			self::$user_id,
			array(
				'displayed_at' => '2026-01-03 00:00:00',
				'dismissed_at' => '2026-01-04 00:00:00',
			)
		);
		wp_set_current_user( self::$user_id );

		$response = rest_get_server()->dispatch( new WP_REST_Request( 'GET', '/wp-notifications/v1/notifications/51' ) );
		$this->assertSame( 'displayed', $response->get_data()['status'] );
		$this->assertSame( '2026-01-03T00:00:00+00:00', $response->get_data()['displayed_at'] );

		$response = rest_get_server()->dispatch( new WP_REST_Request( 'GET', '/wp-notifications/v1/notifications/52' ) );
		$this->assertSame( 'dismissed', $response->get_data()['status'] );
		$this->assertSame( '2026-01-04T00:00:00+00:00', $response->get_data()['dismissed_at'] );
	}

	public function test_get_item_logged_out() {
		$this->add_message_row( 53 );
		$this->add_queue_row( 53, self::$user_id );
		wp_set_current_user( 0 );

		$request  = new WP_REST_Request( 'GET', '/wp-notifications/v1/notifications/53' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'rest_notifications_login_required', $response, 401 );
	}

	public function test_get_item_of_other_user() {
		$this->add_message_row( 54 );
		$this->add_queue_row( 54, self::$other_user_id );
		wp_set_current_user( self::$user_id );

		$request  = new WP_REST_Request( 'GET', '/wp-notifications/v1/notifications/54' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'rest_notification_invalid_id', $response, 404 );
	}

	public function test_get_item_invalid_id() {
		wp_set_current_user( self::$user_id );

		$request  = new WP_REST_Request( 'GET', '/wp-notifications/v1/notifications/999999' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'rest_notification_invalid_id', $response, 404 );
	}

	public function test_create_item() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_update_item() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_delete_item() {
		$this->add_queue_row( 42, self::$user_id );
		$this->add_queue_row( 42, self::$other_user_id );
		wp_set_current_user( self::$user_id );

		$request  = new WP_REST_Request( 'DELETE', '/wp-notifications/v1/notifications/42' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertSame( 200, $response->get_status() );
		$this->assertSame(
			array(
				'deleted' => true,
				'id'      => 42,
			),
			$response->get_data()
		);
		// The other user's copy of the message must stay.
		$this->assertSame( 1, $this->count_queue_rows( 42 ) );
	}

	public function test_delete_item_logged_out() {
		$this->add_queue_row( 43, self::$user_id );
		wp_set_current_user( 0 );

		$request  = new WP_REST_Request( 'DELETE', '/wp-notifications/v1/notifications/43' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'rest_notifications_login_required', $response, 401 );
		$this->assertSame( 1, $this->count_queue_rows( 43 ) );
	}

	public function test_delete_item_of_other_user() {
		$this->add_queue_row( 44, self::$other_user_id );
		wp_set_current_user( self::$user_id );

		$request  = new WP_REST_Request( 'DELETE', '/wp-notifications/v1/notifications/44' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'rest_notification_invalid_id', $response, 404 );
		$this->assertSame( 1, $this->count_queue_rows( 44 ) );
	}

	public function test_delete_item_invalid_id() {
		wp_set_current_user( self::$user_id );

		$request  = new WP_REST_Request( 'DELETE', '/wp-notifications/v1/notifications/999999' );
		$response = rest_get_server()->dispatch( $request );

		$this->assertErrorResponse( 'rest_notification_invalid_id', $response, 404 );
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
