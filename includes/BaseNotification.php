<?php

class WPNotify_BaseNotification implements WPNotify_Notification {

	/**
	 * ID of the notification.
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * Sender of the notification.
	 *
	 * @var WPNotify_Sender
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
	 * @var WPNotify_RecipientCollection
	 */
	protected $recipients;

	/**
	 * Notification message.
	 *
	 * @var WPNotify_Message
	 */
	protected $message;

	/**
	 * Notification status.
	 *
	 * @var string
	 */
	protected $status = WPNotify_Status::UNREAD;

	/**
	 * Instantiates a WPNotify_Base_Notification object.
	 *
	 * @param WPNotify_Sender              $sender     Sender that sent the
	 *                                                 notification.
	 * @param WPNotify_RecipientCollection $recipients Recipients that should
	 *                                                 receive the
	 *                                                 notification.
	 * @param WPNotify_Message             $message    Message of the
	 *                                                 notification.
	 * @param mixed                        $timestamp  Optional. Timestamp of
	 *                                                 when the notification
	 *                                                 was triggered. Defaults
	 *                                                 to the moment of
	 *                                                 instantiation.
	 * @param int                          $id         Optional. ID of the
	 *                                                 notification. Defaults
	 *                                                 to -1.
	 */
	public function __construct(
		WPNotify_Sender $sender,
		WPNotify_RecipientCollection $recipients,
		WPNotify_Message $message,
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
	 * @return WPNotify_Sender Sender of the notification.
	 */
	public function get_sender() {
		return $this->sender;
	}

	/**
	 * Gets the recipients for the notification.
	 *
	 * @return WPNotify_RecipientCollection Notification recipients.
	 */
	public function get_recipients() {
		return $this->recipients;
	}

	/**
	 * Gets the message for the notification.
	 *
	 * @return WPNotify_Message Notification message.
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

		return new self( new WPNotify_BaseSender(), $recipients, $message );
	}
}
