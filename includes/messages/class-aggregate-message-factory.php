<?php

final class WP_Notify_Aggregate_Message_Factory
	extends WP_Notify_Aggregate_Factory
	implements WP_Notify_Message_Factory {

	/**
	 * Get the interface that this aggregate factory can instantiate
	 * implementations of.
	 *
	 * @return string Class name of the interface.
	 */
	protected function get_interface() {
		return 'WP_Notify_Message_Factory';
	}
}
