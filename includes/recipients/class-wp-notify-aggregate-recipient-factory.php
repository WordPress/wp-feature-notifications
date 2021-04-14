<?php

final class WP_Notify_Aggregate_Recipient_Factory
	extends WP_Notify_Aggregate_Factory
	implements WP_Notify_Recipient_Factory {

	/**
	 * Get the interface that this aggregate factory can instantiate
	 * implementations of.
	 *
	 * @return string Class name of the interface.
	 */
	protected function get_interface() {
		return 'WP_Notify_Recipient_Factory';
	}
}
