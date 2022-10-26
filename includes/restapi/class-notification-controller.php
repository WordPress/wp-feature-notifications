<?php
/**
 * REST API controller to get and set notifications.
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
	const NAMESPACE = 'wp/v2';

	/**
	 * Base for notification REST routes.
	 *
	 * @type string
	 */
	const NOTIFICATION_BASE = 'notifications';

	public function __construct()
	{
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST routes
	 *
	 * @return void
	 */
	public function register_routes() : void {
		register_rest_route(
			self::NAMESPACE,
			self::NOTIFICATION_BASE,
			array(
				'methods'             => WP_REST_Server::READABLE,
				'args'                => array(),
				'callback'            => array( $this, 'get_notifications' ),
				'permission_callback' => array( $this, 'authenticate' ),
			),
			false,
		);
	}

	/**
	 * Authenticate notification request.
	 *
	 * @return boolean true if authenticated, false otherwise.
	 */
	public function authenticate() : bool {
		// TODO: Implement authentication.
		return true;
	}

	/**
	 * Get notifications for request.
	 *
	 * @param WP_REST_Request $request Recieved REST request
	 *
	 * @return WP_REST_RESPONSE|WP_Error REST response or WP Error
	 */
	public function get_notifications( $request ) {
		$demo_data = file_get_contents( dirname( __FILE__ ) . 'fake_api.json' );

		if ( empty( $demo_data ) ) {
			return new WP_Error( 'demo', __( 'Could not read demo data.' ) );
		}

		return rest_ensure_response( $demo_data );
	}
}
