# Database Schema

### wp_notifications table
* id (int) 11 auto_increment `ID of the notification.`
* sender `Sender of the notification.`
* timestamp `Timestamp of when the notification was triggered.`
* recipients `Collection of notification recipients.`
* title_key `Notification title key.`
* message_key `Notification message key.`  (optional)
* action_link `Correctly formatted URL to an internal or external action.`
* status enum (READ, UNREAD) `Notification status.`

See the Translations document for details on title_key and message_key fields
