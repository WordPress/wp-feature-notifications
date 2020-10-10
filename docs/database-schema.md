# Database Schema

### wp_notifications table
* id (int) 11 auto_increment `ID of the notification.`
* sender `Sender of the notification.`
* timestamp `Timestamp of when the notification was triggered.`
* recipients `Collection of notification recipients.`
* title `Notification message.` (optional)
* message `Notification message.`
* action_link `Correctly formatted URL to an internal or external action.`
* status enum (READ, UNREAD) `Notification status.`


