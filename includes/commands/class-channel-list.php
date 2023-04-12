<?php
/**
 * A WP-CLI command for inspecting notification channels.
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Commands;

use WP_CLI;
use WP_CLI_Command;
use WP\Notifications;

/**
 * Inspect the registered notification channels.
 *
 * @when after_wp_load
 */
class Channel_List extends WP_CLI_Command {

	/**
	 * List all registered notification channels.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render output in a particular format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - csv
	 *   - json
	 *   - count
	 *   - yaml
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     # List all registered channels.
	 *     $ wp notifications channel list
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assoc_args ) {
		$channels = Notifications\Channel_Registry::get_instance()->get_all_registered();

		$formatter = new WP_CLI\Formatter(
			$assoc_args,
			'name,title,icon'
		);

		$formatter->display_items( $channels );
	}
}
