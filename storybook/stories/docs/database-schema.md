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

- `id: BIGINT(20) UNSIGNED AUTO_INCREMENT` - The ID of the message. Primary key.

- `channel_name: VARCHAR(64)` - The scoped channel name the notification was emitted from. Indexed.

- `created_at: DATETIME` - The UTC datetime of when the message was broadcast.

- `expires_at: DATETIME|NULL` - The optional UTC datetime of when the message expires.

  Allowing notice emitters to specify when a notice expires would help signal when it is appropriate to automatically dispose of a notice. It should be best practice to provide `expires_at` for any notice that isn't high priority.

- `severity: VARCHAR(16)|NULL` - The severity of the message, examples: info, warning, alert.

  This should be a predefined list of values.

- `title: TINYTEXT|NULL` - The translated title of the notification.

- `message: TEXT|NULL` - The translated message content of the notification.

- `meta: TEXT|NULL` - JSON-encoded data that doesn’t have to be queried and may change while development of the notification system.

  It is not a `JSON` column because MariaDB aliases `JSON` to `LONGTEXT`, so `dbDelta()` would see a difference on every run.

  - `accept_label:  string|null` - The translated accept action label.

  - `accept_link:   string|null` - The URL of the accept action.

  - `dismiss_label: string|null` - The translated dismiss action label.

  - `icon:          string|null` - The icon of the notification message.

  - `is_dismissible boolean|null` - Whether the notification is dismissible.

    Most persisted notifications should be dismissible, otherwise they will not be able to be removed from the notification system by the user. But this is left as a way to maintain a compatible API with the existing notices.

The human-readable channel title is not stored. It is read from the `Channel_Registry` using `channel_name`.

## Message Queue

Join table to map messages to specific users. Could periodically be cleared depending on message `expires_at` and/or how long ago it was dismissed.

The full history of messages for every user is retained and could be easily looked up.

If a message has been orphan it can safely be deleted.

### wp_notifications_queue table

Primary key: `(user_id, message_id)`. A message is queued at most once per user.

- `message_id: BIGINT(20) UNSIGNED` - The ID of the translated message related to the notice. Indexed.

- `user_id: BIGINT(20) UNSIGNED` - The ID of the user the notice belongs to.

- `context: VARCHAR(64)` - The display context of the notice.

- `created_at: DATETIME` - The UTC datetime of when the notice was created.

- `dismissed_at: DATETIME|NULL` - The UTC datetime of when the notice was dismissed.

- `displayed_at: DATETIME|NULL` - The UTC datetime of when the notice was first displayed.

The channel name and expiry date come from the related message.

## Subscriptions

Table used to determine for whom to enqueue messages when emitting a notification from a channel.

Logic to authorize a users to subscribe to a channel would be based on a comparison of the `role` property of the channel and user.

Notifications can become overwhelming if the user isn't provided with options to snooze and/or unsubscribe from channels.

### wp_notifications_subscriptions

Primary key: `(user_id, channel_name)`. A user subscribes to a channel at most once.

- `user_id: BIGINT(20) UNSIGNED` - The ID of the user the subscription belongs to.

- `channel_name: VARCHAR(64)` - The scoped name of the channel subscribed to. Indexed.

- `created_at: DATETIME` - The UTC datetime of when the subscription was created.

- `snoozed_until: DATETIME|NULL` - The optional UTC datetime of when to resume the channel.

## Dates

All dates are stored in UTC. Write them from PHP with `gmdate( 'Y-m-d H:i:s' )`. The columns have no `DEFAULT CURRENT_TIMESTAMP()`, because that uses the database server time and `dbDelta()` sees it as a change on every run.

## Metadata

The `meta` field of the message and channel tables could be stored in another table, similar to other WordPress schemas. Though keeping it in the same table reduces the number of queries.

## Channels

The concept of channels is similar to block types in the editor. They are registered in code by plugins and the scoped name is used for channel discovery through the `Channel_Registry`. See PR [#251](https://github.com/WordPress/wp-feature-notifications/pull/251) for details about the channel registry.
