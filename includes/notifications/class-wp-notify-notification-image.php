<?php

/**
 * Class WP_Notify_Notification_Image
 *
 * Decorates a WP_Notify_Notification instance with an image.
 */
class WP_Notify_Notification_Image extends WP_Notify_Notification_Decorator {

	/**
	 * @var WP_Notify_Image
	 */
	private $image;

	/**
	 * WP_Notify_Notification_Image constructor.
	 *
	 * @param WP_Notify_Notification $notification
	 * @param WP_Notify_Image $image
	 */
	public function __construct( $notification, $image ) {
		parent::__construct( $notification );
		$this->image = $image;
	}

	public function jsonSerialize() {
		return array_merge(
			$this->notification->jsonSerialize(),
			array(
				WP_Notify_Base_Image::class => $this->image->jsonSerialize(),
			)
		);
	}
}
