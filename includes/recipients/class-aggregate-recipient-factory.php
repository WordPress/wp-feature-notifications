<?php

namespace WP\Notifications\Recipients;

use WP\Notifications;

final class Aggregate_Recipient_Factory
	extends Notifications\Aggregate_Factory
	implements Recipient_Factory {

	/**
	 * Get the interface that this aggregate factory can instantiate
	 * implementations of.
	 *
	 * @return string Class name of the interface.
	 */
	protected function get_interface() {
		return 'Recipient_Factory';
	}
}
