<?php

namespace WP\Notifications\Commands;

use WP_CLI;
use WP\Notifications;

/**
 * Class Channel_List
 *
 * A WP-CLI command for inspecting notification channels.
 *
 * @package wordpress/wp-feature-notifications
 */
class Channel_List {

	/**
	 * List registered notification channels.
	*
	 * ## EXAMPLES
	 *
	 *     wp notifications channel list
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assoc_args ) {
		$channels = Notifications\Channel_Registry::get_instance()->get_all_registered();
		WP_CLI\Utils\format_items(
			'table',
			$channels,
			'name,title'
		);
	}
}
