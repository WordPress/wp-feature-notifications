<?php

namespace WP\Notifications\Messages;

use WP\Notifications\Aggregate_Factory;

final class Aggregate_Message_Factory
	extends Aggregate_Factory
	implements Message_Factory {

	/**
	 * Get the interface that this aggregate factory can instantiate
	 * implementations of.
	 *
	 * @return string Class name of the interface.
	 */
	protected function get_interface() {
		return 'Message_Factory';
	}
}
