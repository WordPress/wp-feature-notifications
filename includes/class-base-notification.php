<?php

namespace WP\Notifications;

use ReflectionClass;
use WP\Notifications\Messages\Message;
use WP\Notifications\Recipients\Recipient_Collection;
use WP\Notifications\Senders\Sender;

class Base_Notification implements Notification {

	/**
	 * ID of the notification.
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * Sender of the notification.
	 *
	 * @var Sender
	 */
	protected $sender;

	/**
	 * Timestamp of when the notification was triggered.
	 *
	 * @var int
	 */
	protected $timestamp;

	/**
	 * Collection of notification recipients.
	 *
	 * @var Recipient_Collection
	 */
	protected $recipients;

	/**
	 * Notification message.
	 *
	 * @var Message
	 */
	protected $message;

	/**
	 * Notification status.
	 *
	 * @var string
	 */
	protected $status = Status::UNREAD;

	/**
	 * Instantiates a Base_Notification object.
	 *
	 * @param Sender               $sender     Sender that sent the
	 *                                                  notification.
	 * @param Recipient_Collection $recipients Recipients that should
	 *                                                 receive the
	 *                                                 notification.
	 * @param Message              $message    Message of the
	 *                                                  notification.
	 * @param mixed                          $timestamp  Optional. Timestamp of
	 *                                                   when the notification
	 *                                                   was triggered. Defaults
	 *                                                   to the moment of
	 *                                                   instantiation.
	 * @param int                            $id         Optional. ID of the
	 *                                                   notification. Defaults
	 *                                                   to -1.
	 */
	public function __construct(
		Sender $sender,
		Recipient_Collection $recipients,
		Message $message,
		$timestamp = null,
		$id = - 1
	) {
		$this->sender     = $sender;
		$this->recipients = $recipients;
		$this->message    = $message;
		$this->timestamp  = $this->validate_timestamp( $timestamp );
		$this->id         = $id;
	}

	/**
	 * Get the ID of the notification.
	 *
	 * @return int ID of the notification, -1 if none was attributed yet.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the timestamp of the notification.
	 *
	 * @return int Timestamp of the notification.
	 */
	public function get_timestamp() {
		return $this->timestamp;
	}

	/**
	 * Validate a timestamp.
	 *
	 * @param mixed $timestamp Timestamp to validate.
	 *
	 * @return int Validated timestamp.
	 */
	protected function validate_timestamp( $timestamp ) {
		if ( null === $timestamp ) {
			$timestamp = time();
		}

		return $timestamp;
	}

	/**
	 * Get the sender of the notification.
	 *
	 * @return Sender Sender of the notification.
	 */
	public function get_sender() {
		return $this->sender;
	}

	/**
	 * Gets the recipients for the notification.
	 *
	 * @return Recipient_Collection Notification recipients.
	 */
	public function get_recipients() {
		return $this->recipients;
	}

	/**
	 * Gets the message for the notification.
	 *
	 * @return Message Notification message.
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * Get the current status of the notification.
	 *
	 * @return string Status of the notification.
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Set the status of the current notification.
	 *
	 * @param string $status Status to set the notification to.
	 *
	 * @return $this
	 */
	public function set_status( $status ) {
		$this->status = $status;

		return $this;
	}

	/**
	 * Specifies data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode, which is a
	 *               value of any type other than a resource.
	 */
	public function jsonSerialize() {
		return array(
			get_class( $this->recipients ) => $this->recipients,
			get_class( $this->message )    => $this->message,
			get_class( $this->sender )     => $this->sender,
		);
	}

	/**
	 * Creates a new instance from JSON-encoded data.
	 *
	 * @param string $json JSON-encoded data to create the instance from.
	 *
	 * @return self
	 */
	public static function json_unserialize( $json ) {
		$data = json_decode( $json );

		reset( $data );
		$recipients_class = key( $data );
		$recipients       = new $recipients_class( current( $data ) );

		next( $data );
		$message_class = key( $data );
		$message       = new $message_class( current( $data ) );

		next( $data );
		$sender_class      = key( $data );
		$sender_reflection = new ReflectionClass( $sender_class );
		$sender            = $sender_reflection->newInstanceArgs( array_values( (array) current( $data ) ) );

		return new self( $sender, $recipients, $message );
	}
}
