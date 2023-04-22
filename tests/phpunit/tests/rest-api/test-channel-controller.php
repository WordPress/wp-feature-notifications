<?php
/**
 * Unit tests covering WP/Notifications/Channel_Controller functionality.
 */
class WP_Test_REST_Channel_Controller extends WP_Test_REST_Controller_Testcase {
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
		$request  = new WP_REST_Request( 'OPTIONS', '/wp-notifications/v1/channels' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();
		$keys     = array_keys( $data['endpoints'][0]['args'] );
		sort( $keys );
		$this->assertSame(
			array(
				'context',
				'offset',
				'page',
				'per_page',
				'search',
			),
			$keys
		);
	}

	public function test_get_item_schema() {
		$request    = new WP_REST_Request( 'OPTIONS', '/wp-notifications/v1/channels' );
		$response   = rest_get_server()->dispatch( $request );
		$data       = $response->get_data();
		$properties = $data['schema']['properties'];
		$this->assertCount( 4, $properties );
		$this->assertArrayHasKey( 'context', $properties );
		$this->assertArrayHasKey( 'icon', $properties );
		$this->assertArrayHasKey( 'name', $properties );
		$this->assertArrayHasKey( 'title', $properties );
	}
}
