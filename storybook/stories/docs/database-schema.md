# Database Schema

## Key features

- Ability to target individual users and maintain a history of their notifications.
- Organization of notifications by channel.
- Subscriptions and snooze functionality.
- Table structure optimized for search by user or channel. Minimizing repetitive string data.

## Message schema

Message data is stored separately from channel data and can be linked to many users through the `wp_notifications_queue` table.

### wp_notifications_messages table

- `id: int` - The ID of the notification.
- `channel_id: int` - The ID of the channel this message was emitted from.
- `created_at: timestamp` - The timestamp of when the message was broadcast.
- `updated_at: timestamp` - The timestamp of when the message was last updated.

  Maybe unnecessary, but it could be good to know if a message has been modify after a user dismissed it.

- `expires_at:  timestamp | null` - The optional timestamp of when the message expires.

  Allowing message emitters to specify when a message expires would help signal when it is appropriate to automatically dispose of a message. It should be best practice to provide `expires_at` for any message that isn't high priority.

- `priority: int (maybe enum)` - The priority of the message, aka. INFO, WARNING, DANGER.

  This should be a predefined list of values.

- `title_key: varchar(128) | null` - The translation key for the title of the notification.

- `message_key: varchar(128)` - The translation key for the notification message content

- `meta: JSON` - data that doesn’t have to be queried, like icon or image information.

## Channel schema

Core should have a set registered channels.

Plugins can register channels of their own.

### wp_notifications_channels table

- `id: int` - The ID of the channel
- `created_at: timestamp` - The timestamp of when the channel was created.
- `updated_at: timestamp` - The timestamp of when the channel was last updated.
- `source: varchar(128)` - The source of the channel, aka. the name of the plugin.
- `name_key: varchar(128)` - The translation key for the name of the channel.
- `description_key: varchar(128)` - The translation key for the description of the channel.
- `role: varchar(128)` The minimum required role to subscribe to messages from the channel.
- `meta: JSON` - Data that doesn’t have to be queried, like icon or images.

## Message Queue

Join table used to map messages to specific users. Could periodically be cleared depending on message `expires_at` and/or how long ago it was dismissed.

The full history of messages for every user is retained and could be easily looked up.

If a message has been orphan it can safely be deleted.

### wp_notifications_queue table

- `message_id: int` - The ID of the enqueued message.
- `user_id: int` - The ID of the user.
- `dismissed_at: timestamp | null` - The timestamp of when the notification was dismissed.
- `displayed_at: timestamp | null` - The timestamp of when the notification was first displayed.

  There could definitely be addition statuses, and if the time of the state update is deemed unnecessary, perhaps they could be a single enum rather that timestamps.

## Subscriptions

Join table used to determine for whom to enqueue messages when emitting to a channel.

Logic to authorize a users to subscribe to a channel would be based on a comparison of the `role` property of the channel and user.

Notifications can become overwhelming if the user isn't provided with options to snooze and/or unsubscribe from channels.

### wp_notifications_subscriptions

- `user_id: int` - The ID of the user subscribed to the channel.
- `channel_id: int` - The ID of the channel subscribed to.
- `snoozed_until: timestamp | null` - The optional timestamp of when to resume the channel.

## Metadata

The `meta` field of the message and channel tables could be stored in another table, similar to other WordPress schemas. Though keeping it in the same table reduces the number of queries.

A message could be of a type defined in code that has metadata attached to it, similar to Gutenberg block registration. Attaching icons could be done in code, calling something like `wp_notifications_register_channel` or `wp_notifications_register_message_type`.

Use case: icon, color, template
