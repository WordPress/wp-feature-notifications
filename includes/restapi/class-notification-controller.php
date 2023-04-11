<?php

/**
 * REST API controller to get and set notification messages.
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
 * REST API Controller class
 */
class Notification_Controller extends WP_REST_Controller {

	/**
	 * Namespace for the REST endpoint.
	 *
	 * @type string
	 */
	const NAMESPACE = 'wp-notifications/v1';

	/**
	 * Base for notification REST routes.
	 *
	 * @type string
	 */
	public const NOTIFICATION_BASE = 'notifications';

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

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
	 * Checks if a given request has access to read notifications.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has access to view the items, error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! is_user_logged_in() ) {
			return new WP_Error(
				'rest_notifications_login_required',
				__( 'Sorry, you must be logged check notifications.' ),
				array( 'status' => 401 )
			);
		}
		return true;
	}

	/**
	 * Get notifications for request.
	 *
	 * @param WP_REST_Request $request Recieved REST request
	 *
	 * @return WP_REST_RESPONSE|WP_Error REST response or WP Error
	 */
	public function get_items( $request ) {
		$demo_data = file_get_contents( dirname( __FILE__ ) . '/fake_api.json' );

		if ( empty( $demo_data ) ) {
			return new WP_Error( 'demo', __( 'Could not read demo data.' ) );
		}

		return rest_ensure_response( json_decode( $demo_data ) );
	}

	/**
	 * Retrieves the notification's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'notification',
			'type'       => 'object',
			'properties' => array(
				'id'            => array(
					'description' => __( 'Unique identifier for the notification.' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'channel_name'  => array(
					'description' => __( 'Unique identifier for the notification channel.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'channel_title' => array(
					'description' => __( 'The title of the notification channel.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'created_at'    => array(
					'description' => __( "The datetime the notification was broadcast, in the site's timezone." ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
				),
				'dismissed_at'  => array(
					'description' => __( "The datetime the notification was dismissed, in the site's timezone." ),
					'type'        => array( 'string', 'null' ),
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
				),
				'displayed_at'  => array(
					'description' => __( "The datetime the notification was displayed, in the site's timezone." ),
					'type'        => array( 'string', 'null' ),
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
				),
				'expires_at'    => array(
					'description' => __( "The datetime the notification expires, in the site's timezone." ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit', 'embed' ),
				),
				'context'       => array(
					'description' => __( 'The view context the notification.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit', 'embed' ),
					'default'     => 'hub',
					'enum'        => array(
						'adminbar',
						'dashboard',
						'hub',
					),
				),
				'severity'      => array(
					'description' => __( 'The severity of the notification.' ),
					'type'        => array( 'string', 'null' ),
					'context'     => array( 'view', 'edit', 'embed' ),
					'enum'        => array(
						'alert',
						'info',
						'warning',
						'success',
					),
				),
				'status'        => array(
					'description' => __( 'The status of the notification.' ),
					'type'        => 'string',
					'default'     => 'undisplayed',
					'enum'        => array(
						'undisplayed',
						'displayed',
						'dismissed',
						'new',
					),
				),
				'title'         => array(
					'description' => __( 'The title of the notification.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
				),
				'message'       => array(
					'description' => __( 'The message content of the notification.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
				),
				'meta'          => array(
					'description' => __( 'The metadata of the notification.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'edit', 'embed' ),
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
	 * @return array Comments collection parameters.
	 */
	public function get_collection_params() {
		$query_params = parent::get_collection_params();

		$query_params['context']['default'] = 'view';

		$query_params['channel'] = array(
			'default'     => array(),
			'description' => __( 'Limit result set to notifications assigned to specific channel IDs.' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
		);

		$query_params['offset'] = array(
			'description' => __( 'Offset the result set by a specific number of items.' ),
			'type'        => 'integer',
		);

		$query_params['order'] = array(
			'description' => __( 'Order sort attribute ascending or descending. Requires authorization.' ),
			'type'        => 'string',
			'default'     => 'desc',
			'enum'        => array(
				'asc',
				'desc',
			),
		);

		$query_params['orderby'] = array(
			'description' => __( 'Sort collection by notifications attribute. Requires authorization.' ),
			'type'        => 'string',
			'default'     => 'created_at',
			'enum'        => array(
				'created_at',
				'dismissed_at',
				'displayed_at',
			),
		);

		$query_params['status'] = array(
			'description' => __( 'Limit result set to notifications assigned a specific status.' ),
			'default'     => 'undisplayed',
			'type'        => 'string',
			'enum'        => array(
				'undisplayed',
				'displayed',
				'dismissed',
				'new',
			),
		);

		return $query_params;
	}
}
