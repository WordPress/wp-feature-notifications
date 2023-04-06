<?php

final class WP_Notify_Role_Recipient implements WP_Notify_Recipient {

	private $role;

	public function __construct( $role ) {
		$this->role = $role;
	}

	public function get_role() {
		return $this->role;
	}
}
