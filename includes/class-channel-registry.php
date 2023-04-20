<?php
/**
 * Notifications API:Channel_Registry class
 *
 * Heavy inspired by the `WP_Block_Type_Registry`.
 *
 * The registered channels are stored in code, not in the database. If the data were
 * stored in the database it would require a lookup to ensure the registered channel
 * matched it's value in the database based. Since the channels can be registered by
 * plugins, which maybe be uninstalled, the notification system cannot rely on the
 * presence of the `Channel` instance after an initial render of a notification
 * message. The namespaced channel name is the key used to look up a user's
 * subscription to a channel.
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications;

use WP\Notifications\Model;

/**
 * Class used for interacting with channels.
 */
final class Channel_Registry {

	/**
	 * Registered channels, as `$name => $instance` pairs.
	 *
	 * @var Model\Channel[]
	 */
	private $registered_channels = array();

	/**
	 * Container for the main instance of the class.
	 *
	 * @var Channel_Registry|null
	 */
	private static $instance = null;

	/**
	 * Registers a channel.
	 *
	 * @see \WP\Notifications\Channel::__construct()
	 *
	 * @param string|Model\Channel $name Channel name including namespace, or
	 *                                   alternatively a complete Channel instance.
	 *                                   In case a Channel is provided, the $args
	 *                                   parameter will be ignored.
	 * @param array|string         $args {
	 *     Array or string of arguments for registering a channel. Supported arguments
	 *     are described below.
	 *
	 *     @type string      $title       Human-readable label of the channel.
	 *     @type string|null $context     Optional display context of the channel
	 *     @type string|null $description Optional detailed description of the channel.
	 *     @type string|null $icon        Optional icon of the channel.
	 * }
	 * @return Model\Channel|false The registered channel on success, or false on failure.
	 */
	public function register( $name, $args = array() ) {
		$channel = null;

		if ( $name instanceof Channel ) {
			$channel = $name;
			$name    = $channel->get_name();
		}

		if ( ! is_string( $name ) ) {
			_doing_it_wrong(
				__METHOD__,
				__( 'Channel names must be strings.' ),
				'1.0.0'
			);
			return false;
		}

		if ( preg_match( '/[A-Z]+/', $name ) ) {
			_doing_it_wrong(
				__METHOD__,
				__( 'Channel names must not contain uppercase characters.' ),
				'1.0.0'
			);
			return false;
		}

		$name_matcher = '/^[a-z0-9-]+\/[a-z0-9-]+$/';
		if ( ! preg_match( $name_matcher, $name ) ) {
			_doing_it_wrong(
				__METHOD__,
				__( 'Channel names must contain a namespace prefix. Example: my-plugin/my-channel' ),
				'1.0.0'
			);
			return false;
		}

		if ( $this->is_registered( $name ) ) {
			_doing_it_wrong(
				__METHOD__,
				/* translators: %s: Channel name. */
				sprintf( __( 'Channel "%s" is already registered.' ), $name ),
				'1.0.0'
			);
			return false;
		}

		if ( ! $channel ) {
			$parsed = wp_parse_args( $args );

			$title       = $parsed['title'];
			$context     = array_key_exists( 'context', $parsed ) ? $parsed['context'] : null;
			$description = array_key_exists( 'description', $parsed ) ? $parsed['description'] : null;
			$icon        = array_key_exists( 'icon', $parsed ) ? $parsed['icon'] : null;

			$channel = new Model\Channel( $name, $title, $context, $description, $icon );
		}

		$this->registered_channels[ $channel->get_name() ] = $channel;

		return $channel;
	}

	/**
	 * Unregister a channel.
	 *
	 * @param string|Model/Channel $name Channel type name including namespace, or
	 *                                   alternatively a complete Channel instance.
	 * @return Model/Channel|false The unregistered channel on success, or false on failure.
	 */
	public function unregister( $name ) {
		if ( $name instanceof Channel ) {
			$name = $name->get_name();
		}

		if ( ! $this->is_registered( $name ) ) {
			_doing_it_wrong(
				__METHOD__,
				/* translators: %s: Channel name. */
				sprintf( __( 'Channel "%s" is not registered.' ), $name ),
				'1.0.0'
			);
			return false;
		}

		$unregistered_channel = $this->registered_channels[ $name ];
		unset( $this->registered_channels[ $name ] );

		return $unregistered_channel;
	}

	/**
	 * Retrieves a registered channel.
	 *
	 * @param string $name Channel name including namespace.
	 *
	 * @return Model\Channel|null The registered channel, or null if it is not registered.
	 */
	public function get_registered( $name ) {
		if ( ! $this->is_registered( $name ) ) {
			return null;
		}

		return $this->registered_channels[ $name ];
	}

	/**
	 * Retrieves all registered channels.
	 *
	 * @return Model\Channel[] Associative array of `$channel_name => $channel` pairs.
	 */
	public function get_all_registered() {
		return $this->registered_channels;
	}

	/**
	 * Checks if a channel is registered.
	 *
	 * @param string $name Chanel name including namespace.
	 *
	 * @return bool True if the channel is registered, false otherwise.
	 */
	public function is_registered( $name ) {
		return isset( $this->registered_channels[ $name ] );
	}

	/**
	 * Utility method to retrieve the main instance of the class.
	 *
	 * The instance will be created if it does not exist yet.
	 *
	 * @return Channel_Registry The main instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
