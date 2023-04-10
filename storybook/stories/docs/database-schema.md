# Database Schema

## Key features

- Ability to target individual users and maintain a history of their notifications.
- Organization of notifications by channel.
- Subscriptions and snooze functionality.
- Table structure optimized for search by user or channel. Minimizing repetitive string data.

## Message schema

Message data is related to many users through the `wp_notifications_queue` table.

### wp_notifications_messages table

- `id: BIGINT(30)` - The ID of the notification.

- `channel_name: VARCHAR(32)` - The scoped channel name the notification was emitted from.

- `channel_title: TINYTEXT` - The channel title.

  This could possibly be stored in the `meta` column.

- `created_at: DATETIME` - The timestamp of when the message was broadcast.

- `expires_at:  DATETIME | null` - The optional timestamp of when the message expires.

  Allowing message emitters to specify when a message expires would help signal when it is appropriate to automatically dispose of a message. It should be best practice to provide `expires_at` for any message that isn't high priority.

- `severity: VARCHAR(20)` - The severity of the message, examples: info, warning, alert.

  This should be a predefined list of values.

- `title: TINYTEXT | null` - The translated title of the notification.

- `message: TINYTEXT` - The translated message content of the notification

- `meta: JSON` - Data that doesn’t have to be queried, like icon, image or action information.

## Message Queue

Join table to map messages to specific users. Could periodically be cleared depending on message `expires_at` and/or how long ago it was dismissed.

The full history of messages for every user is retained and could be easily looked up.

If a message has been orphan it can safely be deleted.

### wp_notifications_queue table

- `message_id: BIGINT(20)` - The ID of the enqueued message.

- `user_id: BIGINT(20)` - The ID of the user.

- `dismissed_at: DATETIME | null` - The timestamp of when the notification was dismissed.

- `displayed_at: DATETIME | null` - The timestamp of when the notification was first displayed.

  There could definitely be addition statuses, and if the time of the state update is deemed unnecessary, perhaps they could be a single enum rather that timestamps.

## Subscriptions

Table used to determine for whom to enqueue messages when emitting a notification from a channel.

Logic to authorize a users to subscribe to a channel would be based on a comparison of the `role` property of the channel and user.

Notifications can become overwhelming if the user isn't provided with options to snooze and/or unsubscribe from channels.

### wp_notifications_subscriptions

- `user_id: int` - The ID of the user subscribed to the channel.

- `channel_name: int` - The scoped name of the channel subscribed to.

- `snoozed_until: DATETIME | null` - The optional timestamp of when to resume the channel.

## Metadata

The `meta` field of the message and channel tables could be stored in another table, similar to other WordPress schemas. Though keeping it in the same table reduces the number of queries.

## Channels

The concept of channels is similar to block types in the editor. They are registered in code by plugins and the scoped name is used for channel discovery through the `Channel_Registry`. See PR [#251](https://github.com/WordPress/wp-feature-notifications/pull/251) for details about the channel registry.
