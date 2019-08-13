<?php

final class WPNotify_AggregateRecipientFactory
	extends WPNotify_AggregateFactory
	implements WPNotify_RecipientFactory {

	/**
	 * Get the interface that this aggregate factory can instantiate
	 * implementations of.
	 *
	 * @return string Class name of the interface.
	 */
	protected function get_interface() {
		return 'WPNotify_RecipientFactory';
	}
}
