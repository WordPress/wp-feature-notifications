<?php

namespace WP\Notifications\Recipients;

final class Role_Recipient implements Recipient {

	private $role;

	public function __construct( $role ) {
		$this->role = $role;
	}

	public function get_role() {
		return $this->role;
	}
}
