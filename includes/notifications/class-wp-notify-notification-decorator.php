<?php

/**
 * Class WP_Notify_Notification_Decorator
 *
 * Base class for decorating notifications.
 */
abstract class WP_Notify_Notification_Decorator implements WP_Notify_Notification
{
    /**
     * @var WP_Notify_Notification
     */
    protected $notification;

    /**
     * WP_Notify_Notification_Decorator constructor.
     *
     * @param  WP_Notify_Notification $notification
     */
    public function __construct( $notification ) {
        $this->notification = $notification;
    }

    public static function json_unserialize( $string )
    {
        // TODO: Remove this method from the WP_Notify_interface.
    }

    public abstract function jsonSerialize();

    public function get_sender()
    {
        return $this->notification->get_sender();
    }

    public function get_timestamp()
    {
        return $this->notification->get_timestamp();
    }

    public function get_id()
    {
        return $this->notification->get_id();
    }

    public function get_message()
    {
        return $this->notification->get_message();
    }

    public function get_recipients()
    {
        return $this->notification->get_recipients();
    }
}