<?php

interface WPNotify_SenderFactory {

	/**
	 * Create a new instance of notification sender
	 *
	 * @param string $name
	 *
	 * @return WPNotify_Sender
	 */
	public function create( string $name ): WPNotify_Sender;
}
