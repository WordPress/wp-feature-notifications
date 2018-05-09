<?php

class WP_Notification_Message_Factory {

	public static function create( $args ) {
		return new WP_Base_Notification_Message( $message );
	}
}
