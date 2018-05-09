<?php

final class WP_Notification_User_Recipient implements WP_Notification_Recipient {

	private $user_id;

	public function __construct( $user_id ) {
		$this->user_id = $user_id;
	}

	public function get_user_id() {
		return $this->user_id;
	}
}
