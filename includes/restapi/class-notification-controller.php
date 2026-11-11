<?php
/**
 * Notifications API:Notification_Controller.
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\REST;

use WP;
use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP\Notifications;
use WP\Notifications\Model;
use WP\Notifications\Persistence;

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
	 * Namespaced channel name regex.
	 *
	 * @type string
	 */
	protected const CHANNEL_NAME_PATTERN = '^[a-z0-9-]+/[a-z0-9-]+$';

	/**
	 * Repository, lazily created per request.
	 *
	 * @var Persistence\Notification_Repository|null
	 */
	protected $repository = null;

	/**
	 * Returns (and caches) the notification repository.
	 *
	 * The instance is filtered so plugins and tests can substitute a fake, per
	 * issue #617. The default is a freshly constructed `Wpdb_Notification_Repository`.
	 *
	 * @return Persistence\Notification_Repository
	 */
	protected function get_repository(): Persistence\Notification_Repository {
		if ( null === $this->repository ) {
			$repository       = new Persistence\Wpdb_Notification_Repository();
			$this->repository = apply_filters( 'wp_notifications_repository', $repository );
		}

		return $this->repository;
	}

	/**
	 * Register REST routes
	 *
	 * @return void
	 */
	public function register_routes(): void {
		// Collection route — GET for the list, plus an OPTIONS-style discovery.
		register_rest_route(
			self::NAMESPACE,
			self::NOTIFICATION_BASE,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'args'                => $this->get_collection_params(),
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'allow_batch'         => array(
						'v1' => true,
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			),
			false
		);

		// Item route — supports GET, POST, PUT, PATCH so a caller can look at a
		// single notification and (per #176) update its per-user status without a
		// dedicated dismiss endpoint. #305 / #376 also rely on these verbs.
		register_rest_route(
			self::NAMESPACE,
			sprintf( '%s/(?P<id>[\d]+)', self::NOTIFICATION_BASE ),
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'args'                => $this->get_collection_params(),
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'allow_batch'         => array(
						'v1' => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'args'                => $this->get_item_schema( true ),
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'allow_batch'         => array(
						'v1' => true,
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'args'                => $this->get_item_schema( true ),
					'callback'            => array( $this, 'update_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
					'allow_batch'         => array(
						'v1' => true,
					),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			),
			false
		);
	}

	/**
	 * Returns a 401 if the current user is not logged in.
	 *
	 * @return true|WP_Error
	 */
	protected function login_or_unauthorized(): true|WP_Error {
		if ( ! is_user_logged_in() ) {
			return new WP_Error(
				'rest_notifications_login_required',
				__( 'Sorry, you must be logged in to manage notifications.', 'wp-feature-notifications' ),
				array(
					'status' => 401,
				)
			);
		}

		return true;
	}

	/**
	 * Checks if a given request has access to read notifications.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error True if the request has access to view the items, error otherwise.
	 */
	public function get_items_permissions_check( $request ) {
		$auth = $this->login_or_unauthorized();

		if ( is_wp_error( $auth ) ) {
			return $auth;
		}

		// A "user_id" target is allowed, but only when the requester can manage
		// options; otherwise the lookup is restricted to the current user.
		$user_id = (int) $request->get_param( 'user_id' );
		if ( 0 !== $user_id && ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_notifications_forbidden',
				__( 'Sorry, you are not allowed to view other users\' notifications.', 'wp-feature-notifications' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Checks access to a single item for reading.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error
	 */
	public function get_item_permissions_check( $request ) {
		$auth = $this->login_or_unauthorized();

		if ( is_wp_error( $auth ) ) {
			return $auth;
		}

		$user_id = (int) $request->get_param( 'user_id' );
		if ( 0 !== $user_id && ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_notifications_forbidden',
				__( 'Sorry, you are not allowed to view other users\' notifications.', 'wp-feature-notifications' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Checks access for a creation request.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error
	 */
	public function create_item_permissions_check( $request ) {
		$auth = $this->login_or_unauthorized();

		if ( is_wp_error( $auth ) ) {
			return $auth;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_notifications_forbidden',
				__( 'Sorry, you are not allowed to create notifications.', 'wp-feature-notifications' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Checks access for an update request.
	 *
	 * An update against the caller's own queue is allowed; targeting another
	 * user's queue requires `manage_options`.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return true|WP_Error
	 */
	public function update_item_permissions_check( $request ) {
		$auth = $this->login_or_unauthorized();

		if ( is_wp_error( $auth ) ) {
			return $auth;
		}

		$user_id = (int) $request->get_param( 'user_id' );
		$caller  = get_current_user_id();
		if ( 0 === $user_id ) {
			$user_id = $caller;
		}

		if ( $user_id !== $caller && ! current_user_can( 'manage_options' ) ) {
			return new WP_Error(
				'rest_notifications_forbidden',
				__( 'Sorry, you are not allowed to update other users\' notifications.', 'wp-feature-notifications' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Resolves the effective user ID for a request, applying the current-user
	 * fallback only for callers without `manage_options` — they can't target
	 * someone else's queue.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return int
	 */
	protected function resolve_user_id( WP_REST_Request $request ): int {
		$user_id = (int) $request->get_param( 'user_id' );
		if ( 0 !== $user_id ) {
			return $user_id;
		}

		return (int) get_current_user_id();
	}

	/**
	 * Get notifications for a request.
	 *
	 * @param WP_REST_Request $request Received REST request
	 *
	 * @return WP_REST_RESPONSE|WP_Error REST response or WP error.
	 */
	public function get_items( $request ) {
		$repository = $this->get_repository();
		$user_id    = $this->resolve_user_id( $request );

		$args = array(
			'status'          => $request->get_param( 'status' ),
			'channel_name'    => $request->get_param( 'channel_name' ),
			'context'         => $request->get_param( 'context' ),
			'after'           => $request->get_param( 'after' ),
			'include_expired' => (bool) $request->get_param( 'include_expired' ),
			'order'           => $request->get_param( 'order' ),
			'orderby'         => $request->get_param( 'orderby' ),
			'per_page'        => (int) $request->get_param( 'per_page' ),
			'offset'          => (int) $request->get_param( 'offset' ),
		);

		// Drop nulls so the repository falls back to its own defaults.
		$args = array_filter(
			$args,
			static function ( $value ) {
				return null !== $value && '' !== $value;
			}
		);

		$items = $repository->find_by_user( $user_id, $args );
		$total = $repository->count_by_user( $user_id, $args );

		$per_page = isset( $args['per_page'] ) ? (int) $args['per_page'] : 10;
		$offset   = isset( $args['offset'] ) ? (int) $args['offset'] : 0;
		$pages    = $per_page > 0 ? (int) ceil( $total / $per_page ) : 0;
		$page     = $per_page > 0 && 0 !== $offset ? (int) floor( $offset / $per_page ) + 1 : 1;

		$prepped = array();
		foreach ( $items as $pair ) {
			$prepped[] = $this->prepare_item_for_response( $pair, $request );
		}

		$response = rest_ensure_response( $prepped );
		$response->header( 'X-WP-Total', (int) $total );
		$response->header( 'X-WP-TotalPages', (int) $pages );

		// `page` is a derived value; expose it so clients can show "page 2 of N".
		$response->header( 'X-WP-Page', $page );

		return $response;
	}

	/**
	 * Look up a single notification by message id for a given user.
	 *
	 * @param WP_REST_Request $request Received REST request.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_item( $request ) {
		$repository = $this->get_repository();
		$message_id = (int) $request->get_param( 'id' );
		$user_id    = $this->resolve_user_id( $request );

		$pair = $repository->find_by_id( $message_id, $user_id );

		if ( null === $pair ) {
			return new WP_Error(
				'rest_notifications_not_found',
				__( 'Notification not found.', 'wp-feature-notifications' ),
				array(
					'status' => 404,
				)
			);
		}

		return $this->prepare_item_for_response( $pair, $request );
	}

	/**
	 * Create a notification.
	 *
	 * @param WP_REST_Request $request Received REST request.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_item( $request ) {
		$repository = $this->get_repository();

		$channel_name = (string) $request->get_param( 'channel_name' );
		$message_args = array(
			'message'        => $request->get_param( 'message' ),
			'title'          => $request->get_param( 'title' ),
			'accept_label'   => $request->get_param( 'accept_label' ),
			'accept_link'    => $request->get_param( 'accept_link' ),
			'dismiss_label'  => $request->get_param( 'dismiss_label' ),
			'icon'           => $request->get_param( 'icon' ),
			'severity'       => $request->get_param( 'severity' ),
			'is_dismissible' => $request->get_param( 'is_dismissible' ),
			'expires_at'     => $request->get_param( 'expires_at' ),
		);
		$context      = $request->get_param( 'context' );
		$context      = $context ? (string) $context : 'adminbar';
		$user_raw     = $request->get_param( 'user_id' );
		if ( null === $user_raw || '' === $user_raw ) {
			return new WP_Error(
				'rest_notifications_invalid_payload',
				__( 'At least one user_id is required.' ),
				array( 'status' => 400 )
			);
		}
		$user_ids = array_map( 'intval', (array) $user_raw );
		$message  = new \WP\Notifications\Model\Message(
			$message_args['message'],
			$message_args['accept_label'],
			$message_args['accept_link'],
			$channel_name,
			isset( $message_args['created_at'] ) ? $message_args['created_at'] : null,
			$message_args['dismiss_label'],
			$message_args['expires_at'],
			$message_args['icon'],
			null,
			(bool) ( $message_args['is_dismissible'] ?? true ),
			$message_args['severity'],
			$message_args['title']
		);

		$result = $repository->add( $message, $channel_name, $user_ids, $context );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		$response = rest_ensure_response(
			array(
				'id'           => (int) $result,
				'channel_name' => $channel_name,
			)
		);
		$response->set_status( 201 );

		return $response;
	}

	/**
	 * Update a user's per-notification state, e.g. dismissing it.
	 *
	 * @param WP_REST_Request $request Received REST request.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_item( $request ) {
		$repository = $this->get_repository();
		$message_id = (int) $request->get_param( 'id' );
		$user_id    = $this->resolve_user_id( $request );

		$status = $request->get_param( 'status' );
		if ( null === $status || '' === $status ) {
			return new WP_Error(
				'rest_notifications_invalid_payload',
				__( 'A status value is required to update a notification.' ),
				array( 'status' => 400 )
			);
		}

		$valid_status = array(
			\WP\Notifications\Status_Interface::NEW,
			\WP\Notifications\Status_Interface::UNDISPLAYED,
			\WP\Notifications\Status_Interface::DISPLAYED,
			\WP\Notifications\Status_Interface::DISMISSED,
		);
		if ( ! in_array( $status, $valid_status, true ) ) {
			return new WP_Error(
				'rest_notifications_invalid_status',
				__( 'The provided status value is not valid.' ),
				array( 'status' => 400 )
			);
		}

		switch ( $status ) {
			case \WP\Notifications\Status_Interface::DISMISSED:
				$ok = $repository->dismiss( $message_id, $user_id );
				break;
			case \WP\Notifications\Status_Interface::DISPLAYED:
				$ok = $repository->mark_displayed( $message_id, $user_id );
				break;
			default:
				// 'new' / 'undisplayed' are read-only post-states; the only
				// mutation we expose is dismissal.
				return new WP_Error(
					'rest_notifications_invalid_status',
					__( 'Only the "dismissed" status can be updated via this endpoint.' ),
					array( 'status' => 400 )
				);
		}

		if ( ! $ok ) {
			return new WP_Error(
				'rest_notifications_not_found',
				__( 'Notification not found for the current user.', 'wp-feature-notifications' ),
				array( 'status' => 404 )
			);
		}

		$pair = $repository->find_by_id( $message_id, $user_id );
		if ( null === $pair ) {
			return new WP_Error(
				'rest_notifications_not_found',
				__( 'Notification not found for the current user.', 'wp-feature-notifications' ),
				array( 'status' => 404 )
			);
		}

		return $this->prepare_item_for_response( $pair, $request );
	}

	/**
	 * Prepares a notification for REST response.
	 *
	 * The repository returns `{ notification: Model\Notification,
	 * message: Model\Message }` pairs. This method merges their JSON
	 * representations and derives `channel_title` from the registry (#618).
	 *
	 * @param array|Model\Notification|Model\Message $item    A repository pair,
	 *                                                        or a bare model (used in tests).
	 * @param WP_REST_Request                         $request The request so contexts
	 *                                                        are picked up.
	 *
	 * @return array
	 */
	public function prepare_item_for_response( $item, $request = null ) {
		// Support both the "pair" shape returned by the repository and a bare
		// model passed from ad-hoc tests.
		if ( is_array( $item ) ) {
			$notification = $item['notification'];
			$message      = $item['message'];
		} elseif ( $item instanceof \WP\Notifications\Model\Message ) {
			$notification = null;
			$message      = $item;
		} else {
			$notification = $item;
			$message      = null;
		}

		$data = array();

		if ( $message instanceof \WP\Notifications\Model\Message ) {
			$payload = $message->jsonSerialize();
			// Drop the embedded channel_title; we recompute it below so the
			// registry is the source of truth (see #618).
			unset( $payload['channel_title'] );
			$data       = $payload;
			$data['id'] = (int) $message->get_id();
		}

		if ( $notification instanceof \WP\Notifications\Model\Notification ) {
			$notification_data = $notification->jsonSerialize();
			$data              = array_merge( $data, $notification_data );
		}

		// Derive channel_title from the registry so it always reflects the
		// current human-readable label, even when a stored copy has drifted.
		$channel_name = $notification
			? $notification->get_channel_name()
			: ( ( isset( $data['channel_name'] ) && is_string( $data['channel_name'] ) ) ? $data['channel_name'] : null );

		if ( is_string( $channel_name ) && '' !== $channel_name ) {
			$channel               = \WP\Notifications\Channel_Registry::get_instance()->get_registered( $channel_name );
			$data['channel_title'] = $channel ? $channel->get_title() : ( isset( $data['channel_title'] ) ? $data['channel_title'] : null );
			$data['channel_name']  = $channel_name;
		} else {
			$data['channel_title'] = null;
		}

		// `status` is derived from the timestamps so the client never sees a
		// contradictory combination.
		$data['status'] = $this->derive_status( $notification );

		// `user_id` mirrors the notification's owner (int per schema).
		$data['user_id'] = $notification
			? (int) $notification->get_user_id()
			: null;

		// Ensure the public shape always exposes every declared field.
		$defaults = array(
			'accept_label'   => null,
			'accept_link'    => null,
			'channel_name'   => $channel_name,
			'channel_title'  => $data['channel_title'] ?? null,
			'context'        => null,
			'created_at'     => null,
			'dismiss_label'  => null,
			'dismissed_at'   => null,
			'displayed_at'   => null,
			'expires_at'     => null,
			'icon'           => null,
			'id'             => 0,
			'is_dismissible' => true,
			'message'        => null,
			'severity'       => null,
			'status'         => $data['status'] ?? null,
			'title'          => null,
			'user_id'        => 0,
		);
		$data     = array_merge( $defaults, $data );

		/**
		 * Filters a notification before it is returned to the REST client.
		 *
		 * @param array           $data    The prepared notification data.
		 * @param Model\Notification|Model\Message $item The original model(s).
		 * @param WP_REST_Request         $request The request (may be null in unit tests).
		 */
		$data = apply_filters( 'rest_pre_get_notification', $data, $item, $request );

		return $data;
	}

	/**
	 * Derives the per-user status from the notification's lifecycle timestamps.
	 *
	 * @param Model\Notification|null $notification Notification model, or null.
	 *
	 * @return string One of the Status_Interface constants.
	 */
	protected function derive_status( ?\WP\Notifications\Model\Notification $notification ): string {
		if ( null === $notification ) {
			return \WP\Notifications\Status_Interface::UNDISPLAYED;
		}

		if ( null !== $notification->get_dismissed_at() ) {
			return \WP\Notifications\Status_Interface::DISMISSED;
		}

		if ( null !== $notification->get_displayed_at() ) {
			return \WP\Notifications\Status_Interface::DISPLAYED;
		}

		return \WP\Notifications\Status_Interface::UNDISPLAYED;
	}

	/**
	 * Retrieves the notification's schema, conforming to JSON Schema.
	 *
	 * @param bool $context Whether to include editable context fields.
	 *
	 * @return array The notification schema.
	 */
	public function get_item_schema( $context = 'view' ): array {
		if ( isset( $this->schema ) ) {
			return $this->add_additional_fields_schema( $this->schema );
		}

		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'notification',
			'type'       => 'object',
			'properties' => array(
				'accept_label'   => array(
					'description' => __( 'The label of the accept action.', 'wp-feature-notifications' ),
					'type'        => array( 'string', 'null' ),
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'accept_link'    => array(
					'description'       => __( 'The URL of the accept action.', 'wp-feature-notifications' ),
					'type'              => array( 'string', 'null' ),
					'format'            => 'uri',
					'context'           => array( 'view', 'embed' ),
					'readonly'          => true,
					'sanitize_callback' => static function ( $value ) {
						return is_string( $value ) ? esc_url_raw( $value ) : null;
					},
					'validate_callback' => static function ( $value, $request, $key ) {
						if ( null === $value ) {
							return true;
						}
						return rest_validate_value_from_schema(
							$value,
							array(
								'type'   => 'string',
								'format' => 'uri',
							),
							$key
						);
					},
				),
				'channel_name'   => array(
					'description'       => __( 'Namespaced channel name of the notification, e.g. `core/updates`.', 'wp-feature-notifications' ),
					'type'              => array( 'string', 'null' ),
					'pattern'           => self::CHANNEL_NAME_PATTERN,
					'context'           => array( 'view', 'embed' ),
					'readonly'          => true,
					'validate_callback' => static function ( $value, $request, $key ) {
						if ( null === $value ) {
							return true;
						}
						return (bool) preg_match( '/^' . self::CHANNEL_NAME_PATTERN . '$/', (string) $value );
					},
				),
				'channel_title'  => array(
					'description' => __( 'The title of the notification channel.', 'wp-feature-notifications' ),
					'type'        => array( 'string', 'null' ),
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'context'        => array(
					'description'       => __( 'The view context the notification is displayed in.', 'wp-feature-notifications' ),
					'type'              => array( 'string', 'null' ),
					'context'           => array( 'view', 'embed' ),
					'default'           => 'adminbar',
					'enum'              => array(
						'adminbar',
						'dashboard',
					),
					'readonly'          => true,
					'sanitize_callback' => 'sanitize_key',
				),
				'created_at'     => array(
					'description' => __( 'The datetime the notification was broadcast, in UTC time.', 'wp-feature-notifications' ),
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'dismiss_label'  => array(
					'description' => __( 'The label of the dismiss action.', 'wp-feature-notifications' ),
					'type'        => array( 'string', 'null' ),
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'dismissed_at'   => array(
					'description' => __( 'The datetime the notification was dismissed, in UTC time.', 'wp-feature-notifications' ),
					'type'        => array( 'string', 'null' ),
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'displayed_at'   => array(
					'description' => __( 'The datetime the notification was displayed, in UTC time.', 'wp-feature-notifications' ),
					'type'        => array( 'string', 'null' ),
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'expires_at'     => array(
					'description' => __( 'The datetime the notification expires, in UTC time.', 'wp-feature-notifications' ),
					'type'        => array( 'string', 'null' ),
					'format'      => 'date-time',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'icon'           => array(
					'description'       => __( 'The dashicon slug of the notification icon, e.g. `megaphone`.', 'wp-feature-notifications' ),
					'type'              => array( 'string', 'null' ),
					'context'           => array( 'view', 'embed' ),
					'readonly'          => true,
					'sanitize_callback' => static function ( $value ) {
						return is_string( $value ) ? sanitize_key( $value ) : null;
					},
				),
				'id'             => array(
					'description' => __( 'Unique identifier for the notification.', 'wp-feature-notifications' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'is_dismissible' => array(
					'description' => __( 'Whether the notification can be dismissed.', 'wp-feature-notifications' ),
					'type'        => 'boolean',
					'context'     => array( 'view', 'embed' ),
					'readonly'    => true,
				),
				'message'        => array(
					'description'       => __( 'The message content of the notification.', 'wp-feature-notifications' ),
					'type'              => 'string',
					'context'           => array( 'view', 'embed' ),
					'readonly'          => true,
					'sanitize_callback' => 'wp_kses_post',
				),
				'severity'       => array(
					'description' => __( 'The severity of the notification.', 'wp-feature-notifications' ),
					'type'        => array( 'string', 'null' ),
					'context'     => array( 'view', 'embed' ),
					'enum'        => array(
						'alert',
						'info',
						'warning',
						'success',
					),
				),
				// `status` is the one writable property — it's how #176 dismisses
				// a notification from the REST layer.
				'status'         => array(
					'description'       => __( 'The status of the notification.', 'wp-feature-notifications' ),
					'type'              => 'string',
					'enum'              => array(
						\WP\Notifications\Status_Interface::NEW,
						\WP\Notifications\Status_Interface::UNDISPLAYED,
						\WP\Notifications\Status_Interface::DISPLAYED,
						\WP\Notifications\Status_Interface::DISMISSED,
					),
					'context'           => array( 'view', 'edit', 'embed' ),
					'sanitize_callback' => 'sanitize_key',
					'validate_callback' => static function ( $value, $request, $key ) {
						$valid = array(
							\WP\Notifications\Status_Interface::NEW,
							\WP\Notifications\Status_Interface::UNDISPLAYED,
							\WP\Notifications\Status_Interface::DISPLAYED,
							\WP\Notifications\Status_Interface::DISMISSED,
						);
						return in_array( $value, $valid, true );
					},
				),
				'title'          => array(
					'description'       => __( 'The title of the notification.', 'wp-feature-notifications' ),
					'type'              => array( 'string', 'null' ),
					'context'           => array( 'view', 'embed' ),
					'readonly'          => true,
					'sanitize_callback' => 'wp_kses_post',
				),
				'user_id'        => array(
					'description' => __( 'The identifier of the user the notification belongs to.', 'wp-feature-notifications' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
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

		// Replaces the legacy `channel` integer array — channels are namespaced
		// strings, not integer IDs (see Channel_Registry::register()).
		$query_params['channel_name'] = array(
			'default'           => null,
			'description'       => __( 'Limit result set to notifications assigned to a specific channel name.', 'wp-feature-notifications' ),
			'type'              => 'string',
			'pattern'           => self::CHANNEL_NAME_PATTERN,
			'required'          => false,
			'sanitize_callback' => static function ( $value ) {
				return is_string( $value ) ? $value : null;
			},
			'validate_callback' => static function ( $value, $request, $key ) {
				if ( null === $value ) {
					return true;
				}

				return (bool) preg_match( '/^' . self::CHANNEL_NAME_PATTERN . '$/', (string) $value );
			},
		);

		// Polling (#306): only return notifications created after this instant.
		$query_params['after'] = array(
			'default'           => null,
			'description'       => __(
				'If set, only notifications created after this timestamp (ISO 8601, UTC) are returned. Used for polling.',
				'wp-feature-notifications'
			),
			'type'              => 'string',
			'format'            => 'date-time',
			'required'          => false,
			'sanitize_callback' => static function ( $value ) {
				return is_string( $value ) ? $value : null;
			},
			'validate_callback' => static function ( $value, $request, $key ) {
				if ( null === $value ) {
					return true;
				}
				$datetime = date_create( (string) $value );
				return false !== $datetime;
			},
		);

		// Target another user's queue; gated on `manage_options` in the
		// permission callbacks.
		$query_params['user_id'] = array(
			'default'           => 0,
			'description'       => __(
				'User ID whose notifications to fetch. Defaults to the current user. Requesting another user requires `manage_options`.',
				'wp-feature-notifications'
			),
			'type'              => 'integer',
			'required'          => false,
			'sanitize_callback' => 'absint',
		);

		// Toggle inclusion of expired notifications.
		$query_params['include_expired'] = array(
			'default'           => false,
			'description'       => __( 'Whether to include notifications past their expiry timestamp.', 'wp-feature-notifications' ),
			'type'              => 'boolean',
			'required'          => false,
			'sanitize_callback' => 'rest_sanitize_boolean',
		);

		$query_params['offset'] = array(
			'description'       => __( 'Offset the result set by a specific number of items.', 'wp-feature-notifications' ),
			'type'              => 'integer',
			'required'          => false,
			'sanitize_callback' => 'absint',
		);

		$query_params['order'] = array(
			'description'       => __( 'Order sort attribute ascending or descending.', 'wp-feature-notifications' ),
			'type'              => 'string',
			'default'           => 'desc',
			'enum'              => array(
				'asc',
				'desc',
			),
			'required'          => false,
			'sanitize_callback' => 'sanitize_key',
		);

		$query_params['orderby'] = array(
			'description'       => __( 'Sort collection by notification attribute.', 'wp-feature-notifications' ),
			'type'              => 'string',
			'default'           => 'created_at',
			'enum'              => array(
				'created_at',
				'dismissed_at',
				'displayed_at',
			),
			'required'          => false,
			'sanitize_callback' => 'sanitize_key',
		);

		$query_params['status'] = array(
			'description'       => __( 'Limit result set to notifications assigned a specific status.', 'wp-feature-notifications' ),
			'default'           => \WP\Notifications\Status_Interface::UNDISPLAYED,
			'type'              => 'string',
			'enum'              => array(
				\WP\Notifications\Status_Interface::NEW,
				\WP\Notifications\Status_Interface::UNDISPLAYED,
				\WP\Notifications\Status_Interface::DISPLAYED,
				\WP\Notifications\Status_Interface::DISMISSED,
			),
			'required'          => false,
			'sanitize_callback' => 'sanitize_key',
			'validate_callback' => static function ( $value, $request, $key ) {
				$valid = array(
					\WP\Notifications\Status_Interface::NEW,
					\WP\Notifications\Status_Interface::UNDISPLAYED,
					\WP\Notifications\Status_Interface::DISPLAYED,
					\WP\Notifications\Status_Interface::DISMISSED,
				);
				return in_array( $value, $valid, true );
			},
		);

		// The legacy integer `channel` query param has been removed in favour of
		// `channel_name`; channels are namespaced strings, not integer IDs.
		unset( $query_params['channel'] );

		return $query_params;
	}
}
