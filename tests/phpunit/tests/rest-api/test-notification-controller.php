<?php
/**
 * Unit tests covering WP/Notifications/Notification_Controller functionality.
 */
class WP_Test_REST_Notification_Controller extends WP_Test_REST_Controller_Testcase {
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
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_create_item() {
		$this->markTestSkipped( 'TODO Implement' );
	}

	public function test_update_item() {
		$this->markTestSkipped( 'TODO Implement' );
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
