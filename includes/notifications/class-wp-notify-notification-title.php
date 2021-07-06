<?php

/**
 * Class WP_Notify_Notification_Title
 *
 * Decorates a WP_Notify_Notification instance with a title field.
 */
class WP_Notify_Notification_Title extends WP_Notify_Notification_Decorator {

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
		parent::__construct( $notification );
		$this->title        = $title;
	}

    public function jsonSerialize() {
		return array_merge(
			$this->notification->jsonSerialize(),
			array(
				'title' => $this->title,
			)
		);
	}

}
