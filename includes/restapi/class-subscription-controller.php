<?php
/**
 * Notifications API:Subscription_Controller.
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\REST;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * REST API Subscription Controller class
 */
class Subscription_Controller extends WP_REST_Controller {

	/**
	 * Namespace for the REST endpoint.
	 *
	 * @type string
	 */
	const NAMESPACE = 'wp-notifications/v1';

	/**
	 * Base for channel REST routes.
	 *
	 * @type string
	 */
	public const NOTIFICATION_BASE = 'subscriptions';

	/**
	 * Register REST routes
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
			self::NOTIFICATION_BASE,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'args'                => $this->get_collection_params(),
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			),
			false
		);
	}

	/**
	 * Checks if a given request has access to view subscriptions.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has access to view the items, error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		// if ( ! is_user_logged_in() ) {
		// 	return new WP_Error(
		// 		'rest_notifications_login_required',
		// 		__( 'Sorry, you must be logged to view subscriptions.' ),
		// 		array( 'status' => 401 )
		// 	);
		// }
		return true;
	}

	/**
	 * Get subscriptions for request.
	 *
	 * @param WP_REST_Request $request Received REST request
	 *
	 * @return WP_REST_RESPONSE|WP_Error REST response or WP Error
	 */
	public function get_items( $request ) {
		// TODO query for the current users subscriptions

		return rest_ensure_response( array() );
	}

	/**
	 * Retrieves the subscription's schema, conforming to JSON Schema.
	 *
	 * @return array The notification subscription schema.
	 */
	public function get_item_schema(): array {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'subscription',
			'type'       => 'object',
			'properties' => array(
				'channel_name'  => array(
					'description' => __( 'Unique identifier for the channel of the subscription.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'created_at'    => array(
					'description' => __( 'The datetime the subscription was created, in UTC time.' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'snoozed_until' => array(
					'description' => __( 'The datetime to resume the subscription, in UTC time.' ),
					'type'        => array( 'string', 'null' ),
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
				),
				'user_id'       => array(
					'description' => __( 'The identifier of user the subscription is belongs to.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
			),
		);

		// Cache generated schema on endpoint instance.
		$this->schema = $schema;

		return $this->add_additional_fields_schema( $this->schema );
	}

	/**
	 * Retrieves the query params for collections.
	 *
	 * @return array Subscription collection parameters.
	 */
	public function get_collection_params(): array {
		$query_params = parent::get_collection_params();

		$query_params['offset'] = array(
			'description' => __( 'Offset the result set by a specific number of items.' ),
			'type'        => 'integer',
		);

		$query_params['order'] = array(
			'description' => __( 'Order sort attribute ascending or descending.' ),
			'type'        => 'string',
			'default'     => 'desc',
			'enum'        => array(
				'asc',
				'desc',
			),
		);

		$query_params['orderby'] = array(
			'description' => __( 'Sort collection by notifications attribute.' ),
			'type'        => 'string',
			'default'     => 'created_at',
			'enum'        => array(
				'created_at',
				'snoozed_until',
			),
		);

		return $query_params;
	}
}
