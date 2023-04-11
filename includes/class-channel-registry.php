<?php
/**
 * Notifications API:Channel_Registry class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications;

/**
 * Class used for interacting with channels.
 */
final class Channel_Registry {

	/**
	 * Registered channels, as `$name => $instance` pairs.
	 *
	 * @var Channel[]
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
	 * @see Channel::__construct()
	 *
	 * @param string|Channel $name  Channel name including namespace, or alternatively a complete
	 *                              Channel instance. In case a Channel is provided, the $args
	 *                              parameter will be ignored.
	 * @param array          $args  Optional. Array of channel arguments. Accepts any public property
	 *                              of `Channel`. See Channel::__construct() for information on
	 *                              accepted arguments. Default empty array.
	 * @return Channel|false The registered channel on success, or false on failure.
	 */
	public function register( $name, $args = array() ) {
		$channel = null;

		if ( $name instanceof Channel ) {
			$channel = $name;
			$name    = $channel->name;
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
			$channel = new Channel( $name, $args );
		}

		$this->registered_channels[ $channel->name ] = $channel;

		return $channel;
	}

	/**
	 * Unregister a channel.
	 *
	 * @param string|Channel $name Channel type name including namespace, or alternatively a complete
	 *                             Channel instance.
	 * @return Channel|false The unregistered channel on success, or false on failure.
	 */
	public function unregister( $name ) {
		if ( $name instanceof Channel ) {
			$name = $name->name;
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
	 * @return Channel|null The registered channel, or null if it is not registered.
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
	 * @return Channel[] Associative array of `$channel_name => $channel` pairs.
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
