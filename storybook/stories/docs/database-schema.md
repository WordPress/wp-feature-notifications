# Database Schema

## Key features

* Ability to target individual users and maintain a history of their messages.
* Organization of notifications by channel.
* Subscriptions and snooze functionality. Notifications can become overwhelming if the user isn't provided with options to snooze and/or unsubscribe from channels.
* Table structure optimized for search by user or channel. Minimizing repetitive string data.

## Possibilities

Create a messaging system for users inside of WordPress. It could give collaborators the ability to have message threads for a specific posts and/or workflows. This seems like a powerful feature, possibly an even a more important use case than plugin notifications.

## Message schema

Messages data is stored separately from channel data and can be linked to many users through
the `wp_notifications_queue` table.

### wp_notifications_messages table

* `id: int` - The ID of the notification
* `channel_id: int` - The ID of the channel this message was emitted from.
* `created_at: timestamp` - The timestamp of when the message was broadcast.
* `updated_at: timestamp` - The timestamp of when the message was last updated.

  Maybe unnecessary, but it could be good to know if a message has been modify after a user dismissed it. This is probably most import if users are given the ability to post messages to channels. They will want the ability to edit their messages.

* `expires_at:  timestamp | null` - The optional timestamp of when the message expires.

  Allowing message emitters to specify when a message expires would help signal when it is appropriate to automatically dispose of a message. It should be best practice to provide `expires_at` for any message that isn't high priority.

* `priority: int (maybe enum)` - The priority of the message, aka. INFO, WARNING, DANGER.

  This should be a predefined list of values.

* `title_key: varchar(128) | null` - The translation key for the title of the notification.

  The title should be a very condensed explanations of the notification. In some context, it will be used to show the notification in a list view.

* `content_key: varchar(128)` - The translation key for the notification message content

* `meta: JSON` - data that doesn’t have to be queried, like icon or image information.

  The `meta` field could be stored in another table, similar to other WordPress schemas. Another possibly is to use a concept of message type. A message could be of a type that has metadata attached to it, like icon.

## Channel schema

Core should have a set registered channels.

Plugins can register channels of their own.

### wp_notifications_channels table

* `id: int` - The ID of the channel
* `created_at: timestamp` - The timestamp of when the channel was created.
* `updated_at: timestamp` - The timestamp of when the channel was last updated.
* `source: varchar(128)` - The source of the channel, aka. the name of the plugin.
* `name_key: varchar(128)` - The translation key for the name of the channel.
* `description_key: varchar(128)` - The translation key for the description of the channel.
* `role: varchar(128)` The minimum required role to subscribe to messages from the channel.
* `meta: JSON` - Data that doesn’t have to be queried, like icon or images

  Another way to add metadata to a channel could be the registration process in PHP and JS. Attaching icons could be done in code when calling something like `wp_notifications_register_channel`.

## Message Queue

Join table used to map messages to specific users. Could periodically be cleared depending on message `expires_at` and/or how long ago it was dismissed.

The full history of messages for every user is retained and could be easily looked up.

If a message has been orphan it can safely be deleted.

### wp_notifications_queue table

* `message_id: int` - The ID of the enqueued message.
* `user_id: int` - The ID of the user.
* `dismissed_at: timestamp | null` - The timestamp of when the notification was dismissed.
* `displayed_at: timestamp | null` - The timestamp of when the notification was first displayed.

  There could definitely be addition statuses, and if the time of the state update is deemed unnecessary, perhaps they could be a single enum rather that timestamps.

## Subscriptions

Join table used determine for whom to enqueue messages for a specific channel.

Logic to authorize a users to subscribe to a channel would be based on a comparison of the `role` property of the channel and the user.

### wp_notifications_subscriptions

* `user_id: int` - The ID of the user subscribed to the channel.
* `channel_id: int` - The ID of the channel subscribed to.
* `snoozed_until: timestamp | null` - The optional timestamp of when to resume the channel.
