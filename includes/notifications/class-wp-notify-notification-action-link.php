<?php

/**
 * Class WP_Notify_Notification_Action_Link
 *
 * Decorates a WP_Notify_Notification instance with an action link.
 */
class WP_Notify_Notification_Action_Link extends WP_Notify_Notification_Decorator {
    /**
     * @var string
     */
    private $href;

    /**
     * @var string
     */
    private $text;

    /**
     * WP_Notify_Notification_Action_Link constructor.
     *
     * @param WP_Notify_Base_Notification $base_notification
     * @param string $href
     * @param string $text
     */
    public function __construct( $base_notification, $href, $text )
    {
        parent::__construct( $base_notification );
        $this->href = $href;
        $this->text = $text;
    }

    public function jsonSerialize()
    {
        return array_merge(
            $this->notification->jsonSerialize(),
            array(
                'action_link_href' => $this->href,
                'action_link_text' => $this->text
            )
        );
    }
}