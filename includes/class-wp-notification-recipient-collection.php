<?php

final class WP_Notification_Recipient_Collection implements Iterator, Countable {

	/**
	 * Internal array of recipients.
	 *
	 * @var WP_Notification_Recipient[]
	 */
	private $recipients = array();

	public function __construct( $recipients = array() ) {
		$this->recipients = $this->validate_recipients( $recipients );
	}

	private function validate_recipients( $recipients ) {
		if ( ! is_array( $recipients ) ) {
			$recipients = array( $recipients );
		}

		foreach ( $recipients as $recipient ) {
			if ( ! $recipient instanceof WP_Notification_Recipient ) {
				throw WP_Notification_Runtime_Exception::from_invalid_recipient_collection_addition( $recipient );
			}
		}

		return $recipients;
	}

	public function add( WP_Notification_Recipient $recipient ) {
		$this->recipients[] = $recipient;
	}

	public function count() {
		return count( $this->recipients );
	}

	/**
	 * Return the current recipient.
	 *
	 * @return WP_Notification_Recipient Recipient
	 */
	public function current() {
		return current( $this->recipients );
	}

	/**
	 * Move forward to next recipient
	 *
	 * @return void Any returned value is ignored.
	 */
	public function next() {
		next( $this->recipients );
	}

	/**
	 * Return the key of the current recipient
	 *
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key() {
		return key( $this->recipients );
	}

	/**
	 * Checks if current position is valid
	 *
	 * @return boolean The return value will be casted to boolean and then
	 *                 evaluated. Returns true on success or false on failure.
	 */
	public function valid() {
		// TODO: Implement valid() method.
	}

	/**
	 * Rewind the Iterator to the first recipient.
	 *
	 * @return void Any returned value is ignored.
	 */
	public function rewind() {
		reset( $this->recipients );
	}
}
