# Database Schema

### wp_notifications table
* id (int) 11 auto_increment `ID of the notification.`
* sender_key `Sender of the notification. As defined by the plugin or theme`
* timestamp `Timestamp of when the notification was triggered.`
* recipient_key `Single notification recipient. As defined by the plugin or theme`
* title_key (optional) `Notification title key. As defined by the plugin or theme`
* message_key `Notification message key. As defined by the plugin or theme` 
* action_link (optional) `Correctly formatted URL to an internal or external action.`
* image_key (optional) `Notification image key.`
* status enum (READ, UNREAD) `Notification status.`

See the Translations document for details on title_key and message_key fields
