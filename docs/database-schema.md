# Database Schema

### wp_notifications table
* id (int) 11 auto_increment `ID of the notification.`
* sender `Sender of the notification.`
* timestamp `Timestamp of when the notification was triggered.`
* recipients `Collection of notification recipients.`
* message `Notification message.`
* status enum (READ, UNREAD) `Notification status.`


