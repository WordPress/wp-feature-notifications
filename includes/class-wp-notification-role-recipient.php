<?php

final class WP_Notification_Role_Recipient implements WP_Notification_Recipient {

	private $role;

	public function __construct( $role ) {
		$this->role = $role;
	}

	public function get_role() {
		return $this->role;
	}
}
