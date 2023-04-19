# Database Schema

## Key features

- Ability to target individual users and maintain a history of their notifications.
- Organization of notifications by channel.
- Subscriptions and snooze functionality.
- Table structure optimized for search by user or channel. Minimizing repetitive string data.

## Message schema

Message data is related to many users through the `wp_notifications_queue` table.

Message are translated at the time of emission to the preferences of the users subscribed to the channel. A single notice emission may have multiple translated message row entries.

### wp_notifications_messages table

- `id: BIGINT(30)` - The ID of the notification.

- `channel_name: VARCHAR(32)` - The scoped channel name the notification was emitted from.

- `created_at: DATETIME` - The timestamp of when the message was broadcast.

- `title: TINYTEXT` - The translated title of the notification.

- `message: TINYTEXT` - The translated message content of the notification.

- `meta: JSON` - Data that doesn’t have to be queried and may change while development of the notification system.

  - `accept_label:  string|null` - The translated accept action label.

  - `accept_link:   string|null` - The URL of the accept action.

  - `channel_title: string|null` - The translated, human-readable title of the channel the message was emitted from.

  - `dismiss_label: string|null` - The translated dismiss action label.

  - `icon:          string|null` - The icon of the notification message.

  - `is_dismissible boolean|null` - Whether the notification is dismissible.

    Most persisted notifications should be dismissible, otherwise they will not be able to be removed from the notification system by the user. But this is left as a way to maintain a compatible API with the existing notices.

  - `severity:      string|null` - The severity of the message, examples: info, warning, alert.

    This should be a predefined list of values.

## Message Queue

Join table to map messages to specific users. Could periodically be cleared depending on message `expires_at` and/or how long ago it was dismissed.

The full history of messages for every user is retained and could be easily looked up.

If a message has been orphan it can safely be deleted.

### wp_notifications_queue table

- `message_id: BIGINT(20)` - The ID of the translated message related to the notice.

- `user_id: BIGINT(20)` - The ID of the user the notice belongs to.

- `channel_name: VARCHAR(64)` - The namespaced channel name the notice was emitted from.

- `context: VARCHAR(64)` - The display context of the notice.

- `created_at: DATETIME|NULL` - The datetime of when the notice was create.

- `dismissed_at: DATETIME|NULL` - The datetime of when the notice was dismissed.

- `displayed_at: DATETIME|NULL` - The datetime of when the notice was first displayed.

- `expires_at:  DATETIME|NULL` - The optional datetime of when the message expires.

  Allowing notice emitters to specify when a notice expires would help signal when it is appropriate to automatically dispose of a notice. It should be best practice to provide `expires_at` for any notice that isn't high priority.

## Subscriptions

Table used to determine for whom to enqueue messages when emitting a notification from a channel.

Logic to authorize a users to subscribe to a channel would be based on a comparison of the `role` property of the channel and user.

Notifications can become overwhelming if the user isn't provided with options to snooze and/or unsubscribe from channels.

### wp_notifications_subscriptions

- `user_id: BIGINT(20)` - The ID of the user the subscription belongs to.

- `channel_name: VARCHAR(65)` - The scoped name of the channel subscribed to.

- `created_at: DATETIME|NULL` - The datetime of when the subscription was create.

- `snoozed_until: DATETIME|NULL` - The optional timestamp of when to resume the channel.

## Metadata

The `meta` field of the message and channel tables could be stored in another table, similar to other WordPress schemas. Though keeping it in the same table reduces the number of queries.

## Channels

The concept of channels is similar to block types in the editor. They are registered in code by plugins and the scoped name is used for channel discovery through the `Channel_Registry`. See PR [#251](https://github.com/WordPress/wp-feature-notifications/pull/251) for details about the channel registry.
