<?php

final class WPNotify_RoleRecipient implements WPNotify_Recipient {

	private $role;

	public function __construct( $role ) {
		$this->role = $role;
	}

	public function get_role() {
		return $this->role;
	}
}
