# Database Schema

## Message schema

Messages data is stored separately from channel data and can be linked to many users through
the `wp_notify_queue` table.

### wp_notify_messages table

* `id: int` - The ID of the notification
* `channel_id: int` - The ID of the channel this message was emitted from
* `created_at: timestamp` - The timestamp of when the message was broadcast
* `updated_at: timestamp` - The timestamp of when the message was last updated
* `expires_at:  timestamp | null` - The optional timestamp of when the message expires
* `priority: int (maybe enum)` - The priority of the message, aka INFO, WARNING, DANGER
* `title: varchar(128) | null` - The title of the notification
* `content: text` - The notification message content
* `meta: JSON` - data that doesn’t have to be queried, like icon or image information

## Channel schema

Core should have a set registered channels.

Plugins can register channels of their own.

Every user has a channel attached to directly to their profile. Allows for notifications targeted to a single user. How would that be discoverable? An entry in the `wp_user_meta` table? Or a message has a null `channel_id` but is still enqueued? I like the last option best.

### wp_ notify_channels table

* `id: int` - The ID of the channel
* `created_at: timestamp` - The timestamp of when the channel was created
* `updated_at: timestamp` - The timestamp of when the channel was last updated
* `source: varchar(256)` - The source of the channel, aka. the name of the plugin
* `name: varchar(256)` - The name of the channel
* `description: text` - The description of the channel
* `parent_id: int | null` The ID of the parent channel
* `role: varchar(128)` The minimum required role to subscribe to messages from the channel
* `meta: JSON` - Data that doesn’t have to be queried, like icon or images

## Message Queue

Join table used to map messages to specific users. Could periodically be cleared depending on message `expires_at` and/or how long ago it was dismissed.

The full history of message for every user is retained and could be easily looked up.

If a message has been orphan it can safely be deleted.

### wp_notify_queue table

* `message_id: int` - The ID of the enqueued message
* `user_id: int` - The ID of the user
* `dismissed_at: timestamp | null` - The timestamp of when the notification was dismissed

## Subscriptions

Join table used determine for whom to enqueue messages for a specific channel.

Logic to authorize a users to subscribe to a channel would be based on a comparison of the `role` property of the channel and the user.

### wp_ notify_subscriptions

* `user_id: int` - The ID of the user subscribed to the channel
* `channel_id: int` - The ID of the channel subscribed to
* `snoozed_until: timestamp | null` - The optional timestamp of when to resume the channel
