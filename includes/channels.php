<?php
/**
 * Functions related to registering channels.
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications;

/**
 * Register a channel.
 *
 * @param Channel $channel A complete Channel instance.
 *
 * @return Channel|false The registered channel on success, or false on failure.
 */
function register_channel( $channel ) {
	return Channel_Registry::get_instance()->register( $channel );
}

/**
 * Unregister a channel.
 *
 * @since 5.0.0
 *
 * @param string|Channel $name Channel name including namespace, or alternatively a complete
 *                             Channel instance.
 * @return Channel|false The unregistered channel on success, or false on failure.
 */
function unregister_block_type( $name ) {
	return Channel_Registry::get_instance()->unregister( $name );
}
