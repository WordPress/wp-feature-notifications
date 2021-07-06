<?php

/**
 * Class WP_Notify_Notification_Title
 *
 * Decorates a WP_Notify_Notification instance with a title field.
 */
class WP_Notify_Notification_Title implements WP_Notify_Notification {

	/**
	 * @var WP_Notify_Notification
	 */
	private $notification;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * WP_Notify_Notification_Title constructor.
	 *
	 * @param WP_Notify_Notification $notification
	 * @param string $title
	 */
	public function __construct( $notification, $title ) {
		$this->notification = $notification;
		$this->title        = $title;
	}

	public function get_id() {
		return $this->notification->get_id();
	}

	public function get_timestamp() {
		return $this->notification->get_timestamp();
	}

	public function get_sender() {
		return $this->notification->get_sender();
	}

	public function get_recipients() {
		return $this->notification->get_recipients();
	}

	public function get_message() {
		return $this->notification->get_message();
	}

	public function jsonSerialize() {
		return array_merge(
			$this->notification->jsonSerialize(),
			array(
				'title' => $this->title,
			)
		);
	}

	public static function json_unserialize( $string ) {
		// TODO: Remove this method from the WP_Notify_interface.
	}
}
