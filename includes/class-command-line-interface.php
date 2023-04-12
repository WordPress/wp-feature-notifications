<?php

namespace WP\Notifications;

use WP_CLI;
use WP\Notifications\Commands;

/**
 * Class Commands
 *
 * Initialize the notifications plugin WP_CLI commands.
 *
 * @package wp-feature-notifications
 */
class Command_Line_Interface {

	/**
	 * Add the plugin's `wp-cli` commands.
	 */
	static function boot() {
		// Add CLI commands
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			self::add_commands();
		}
	}

	/**
	 * Add CLI commands.
	 */
	static function add_commands() {
		WP_CLI::add_command( 'notifications channel list', Commands\Channel_List::class );
		WP_CLI::add_command( 'notifications seed-users', Commands\Seed_Users::class );
	}
}
