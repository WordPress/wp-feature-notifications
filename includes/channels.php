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
 * @param string|Channel $name Channel name including namespace, or alternatively a complete
 *                             Channel instance.
 * @return Channel|false The unregistered channel on success, or false on failure.
 */
function unregister_channel( $name ) {
	return Channel_Registry::get_instance()->unregister( $name );
}

// Register core notification channels.

add_action(
	'init',
	function () {
		register_channel(
			new Channel(
				'core/updates',
				array(
					'title'       => __( 'WordPress Updates', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'WordPress core update events.', 'wp-feature-notifications' ),
				)
			)
		);

		register_channel(
			new Channel(
				'core/plugin-install',
				array(
					'title'       => __( 'Plugin Install', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'Plugin install events.', 'wp-feature-notifications' ),
				)
			)
		);

		register_channel(
			new Channel(
				'core/plugin-uninstall',
				array(
					'title'       => __( 'Plugin Uninstall', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'Plugin uninstall events.', 'wp-feature-notifications' ),
				)
			)
		);

		register_channel(
			new Channel(
				'core/plugin-activate',
				array(
					'title'       => __( 'Plugin Activate', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'Plugin activation events.', 'wp-feature-notifications' ),
				)
			)
		);

		register_channel(
			new Channel(
				'core/plugin-deactivate',
				array(
					'title'       => __( 'Plugin Deactivate', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'Plugin deactivation events.', 'wp-feature-notifications' ),
				)
			)
		);

		register_channel(
			new Channel(
				'core/plugin-updates',
				array(
					'title'       => __( 'Plugin Update', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'Plugin update events.', 'wp-feature-notifications' ),
				)
			)
		);

		register_channel(
			new Channel(
				'core/post-new',
				array(
					'title'       => __( 'New Post', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'Post creation events.', 'wp-feature-notifications' ),
				)
			)
		);

		register_channel(
			new Channel(
				'core/post-edit',
				array(
					'title'       => __( 'Edit Post', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'Post edit events.', 'wp-feature-notifications' ),
				)
			)
		);

		register_channel(
			new Channel(
				'core/post-delete',
				array(
					'title'       => __( 'Delete Post', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'Post delete events.', 'wp-feature-notifications' ),
				)
			)
		);

		register_channel(
			new Channel(
				'core/comment-new',
				array(
					'title'       => __( 'New Comment', 'wp-feature-notifications' ),
					'icon'        => 'wordpress',
					'description' => __( 'Comment creation events.', 'wp-feature-notifications' ),
				)
			)
		);
	}
);
