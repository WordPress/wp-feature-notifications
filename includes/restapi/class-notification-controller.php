<?php
/**
 * Notifications API:Notification_Controller.
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
 * REST API Notification Controller class
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
	 * @param WP_REST_Request $request Received REST request
	 *
	 * @return WP_REST_RESPONSE|WP_Error REST response or WP Error
	 */
	public function get_items( $request ) {
		return rest_ensure_response( array() );
	}

	/**
	 * Retrieves the notification's schema, conforming to JSON Schema.
	 *
	 * @return array The notification schema.
	 */
	public function get_item_schema(): array {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'notification',
			'type'       => 'object',
			'properties' => array(
				'accept_label'   => array(
					'description' => __( 'The label of the accept action.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'accept_link'    => array(
					'description' => __( 'The URL of the accept action..' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'channel_name'   => array(
					'description' => __( 'Unique identifier for the notification channel.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'channel_title'  => array(
					'description' => __( 'The title of the notification channel.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'context'        => array(
					'description' => __( 'The view context the notification.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'default'     => 'adminbar',
					'enum'        => array(
						'adminbar',
						'dashboard',
					),
					'readonly'    => true,
				),
				'created_at'     => array(
					'description' => __( 'The datetime the notification was broadcast, in UTC time.' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'dismiss_label'  => array(
					'description' => __( 'The label of the dismiss action.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'dismissed_at'   => array(
					'description' => __( 'The datetime the notification was dismissed, in UTC time.' ),
					'type'        => array( 'string', 'null' ),
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
				),
				'displayed_at'   => array(
					'description' => __( 'The datetime the notification was displayed, in UTC time.' ),
					'type'        => array( 'string', 'null' ),
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'expires_at'     => array(
					'description' => __( 'The datetime the notification expires, in UTC time.' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'icon'           => array(
					'description' => __( 'The icon of the notification.' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'id'             => array(
					'description' => __( 'Unique identifier for the notification.' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'is_dismissible' => array(
					'description' => __( 'Whether the notification can be dismissed.' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'message'        => array(
					'description' => __( 'The message content of the notification.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'severity'       => array(
					'description' => __( 'The severity of the notification.' ),
					'type'        => array( 'string', 'null' ),
					'context'     => array( 'view', 'embed' ),
					'enum'        => array(
						'alert',
						'info',
						'warning',
						'success',
					),
					'readonly'    => true,
				),
				'status'         => array(
					'description' => __( 'The status of the notification.' ),
					'type'        => 'string',
					'enum'        => array(
						'undisplayed',
						'displayed',
						'dismissed',
						'new',
					),
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'title'          => array(
					'description' => __( 'The title of the notification.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'user_id'        => array(
					'description' => __( 'The identifier of user the notification is belongs to.' ),
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
	 * @return array Notifications collection parameters.
	 */
	public function get_collection_params(): array {
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
