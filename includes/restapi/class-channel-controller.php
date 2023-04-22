<?php
/**
 * Notifications API:Channel_Controller.
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\REST;

use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP\Notifications;
use WP\Notifications\Channel_Registry;

/**
 * REST API Channel Controller class
 */
class Channel_Controller extends WP_REST_Controller {

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
	public const NOTIFICATION_BASE = 'channels';

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
	 * Checks if a given request has access to view channels.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has access to view the items, error object otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! is_user_logged_in() ) {
			return new WP_Error(
				'rest_notifications_login_required',
				__( 'Sorry, you must be logged to view channels.' ),
				array( 'status' => 401 )
			);
		}
		return true;
	}

	/**
	 * Get channels for request.
	 *
	 * @param WP_REST_Request $request Received REST request
	 *
	 * @return WP_REST_RESPONSE|WP_Error REST response or WP Error
	 */
	public function get_items( $request ) {
		$channels = Channel_Registry::get_instance()->get_all_registered();

		// TODO filter based on permissions.

		return rest_ensure_response( $channels );
	}

	/**
	 * Retrieves the channels' schema, conforming to JSON Schema.
	 *
	 * @return array The notification channel schema.
	 */
	public function get_item_schema(): array {
		if ( $this->schema ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'channel',
			'type'       => 'object',
			'properties' => array(
				'context' => array(
					'description' => __( 'The default view context the notification channel.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'enum'        => array(
						'adminbar',
						'dashboard',
					),
					'readonly'    => true,
				),
				'icon'    => array(
					'description' => __( 'The default icon of the notification channel.' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'name'    => array(
					'description' => __( 'Unique identifier for the notification channel.' ),
					'type'        => 'string',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'title'   => array(
					'description' => __( 'The human-readable label of the notification channel.' ),
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
	 * @return array Channel collection parameters.
	 */
	public function get_collection_params(): array {
		$query_params = parent::get_collection_params();

		$query_params['context']['default'] = 'view';

		$query_params['offset'] = array(
			'description' => __( 'Offset the result set by a specific number of items.' ),
			'type'        => 'integer',
		);

		$query_params['context'] = array(
			'description' => __( 'Limit result set to channels assigned a specific display context.' ),
			'default'     => 'all',
			'type'        => 'string',
		);

		return $query_params;
	}
}
