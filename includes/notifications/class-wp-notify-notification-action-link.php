<?php

/**
 * Class WP_Notify_Notification_Action_Link
 *
 * Decorates a WP_Notify_Notification instance with an action link.
 */
class WP_Notify_Notification_Action_Link extends WP_Notify_Notification_Decorator {

	/**
	 * @var WP_Notify_Action_Link
	 */
	private $action_link;

	/**
	 * WP_Notify_Notification_Action_Link constructor.
	 *
	 * @param WP_Notify_Base_Notification $base_notification
	 * @param WP_Notify_Action_Link $action_link
	 */
	public function __construct( $base_notification, $action_link ) {
		parent::__construct( $base_notification );
		$this->action_link = $action_link;
	}

	public function jsonSerialize() {
		return array_merge(
			$this->notification->jsonSerialize(),
			array(
				WP_Notify_Action_Link::class => $this->action_link->jsonSerialize(),
			)
		);
	}
}
