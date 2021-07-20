<?php

/**
 * Class WP_Notify_Composite_Notification
 *
 * Allows a notification to be extended with optional fields
 */
class WP_Notify_Composite_Notification extends WP_Notify_Base_Notification
{
    const FIELD_TITLE = 'title';

    private $additional_fields = array();

    public function add( $name, $value ) {
        $this->additional_fields[ $name ] = $value;
    }

    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            $this->additional_fields
        );
    }
}