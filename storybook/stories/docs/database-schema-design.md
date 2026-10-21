# Database Schema Design

This post explains how Feature Notifications stores notifications, and why the tables look the way they do. It covers version 2 of the schema ([#615](https://github.com/WordPress/wp-feature-notifications/issues/615)). The full list of columns is in the "Database Schema" page.

The code that uses these tables is still being built. This includes emitting a notice to a channel, snoozing, the repositories and the REST endpoints. So the flow below is the design that code is built against, not something you can run today.

## The short version

There are three tables:

- `wp_notifications_messages` holds the content of a notification: title, message, severity, dates and a few display options.
- `wp_notifications_queue` says which user gets which message, and what that user did with it (displayed, dismissed).
- `wp_notifications_subscriptions` says which users follow which channel, and if they snoozed it.

Channels are not stored in the database. Plugins register them in code.

## How a notification moves through the tables

1. A plugin registers a channel, for example `my-plugin/updates`.
2. A user subscribes to the channel. This adds a row to `wp_notifications_subscriptions`.
3. The plugin emits a notice to the channel. This adds one row to `wp_notifications_messages` for each locale used by the subscribers, usually just one. Then it adds one row to `wp_notifications_queue` for every subscribed user who has not snoozed the channel, pointing to the message in that user's locale.
4. The notification hub reads the queue rows of the current user, joined with their messages. When a notice is shown, `displayed_at` is set. When the user dismisses it, `dismissed_at` is set.

## Why messages and the queue are separate tables

A notice often goes to many users. If every user had a full copy of the title and message, a site with 500 administrators would store the same text 500 times.

Instead, the text is stored once in `wp_notifications_messages`. Each user gets a small row in `wp_notifications_queue` that only has two IDs, a context and a few dates. Everything that is different per user, like "has this user dismissed it?", lives in the queue row.

This also keeps a full history per user. Dismissing a notice does not delete anything, it only sets a date.

## Why there is one message row per locale

Messages are meant to be translated when the notice is emitted, not when it is read. Reading then stays a plain query, with no translating on every admin page load. It also means the text matches the moment it was sent. If a plugin is updated later and its strings change, old notices still say what they said.

The cost is that a notice sent to users with different locales creates one message row per locale. Most sites use a single locale, so most notices create a single row.

## Why there is no channels table

Channels work like block types. Plugins register them in code on every request, and the `Channel_Registry` keeps track of them. A database table would be a second copy of data that already lives in code, and the two could get out of sync.

For the same reason, schema v2 removed the `channel_title` column from messages ([#372](https://github.com/WordPress/wp-feature-notifications/issues/372)). The title is read from the registry using `channel_name`. If a plugin changes the title of a channel, all old notices show the new title right away. Changing the `channel_name` itself is different: old messages and subscriptions keep the old name, so that needs a data migration.

One side effect: if the plugin that owns a channel is deactivated, its channel is no longer registered, but its messages are still there. The UI needs a fallback for this case, for example showing the raw channel name.

## Keys and indexes

- `messages.id` is `BIGINT(20) UNSIGNED AUTO_INCREMENT`. Version 1 had no `AUTO_INCREMENT`, so inserting a message without an ID either failed right away (strict SQL mode) or wrote `id = 0`, and then the second insert failed with a duplicate key error.
- The queue primary key is `(user_id, message_id)`. A message can be queued only once per user. `user_id` comes first because the most common read is "all notices for this user", and that read can use the primary key. A separate index on `message_id` covers cleanup and "who got this message".
- The subscriptions primary key is `(user_id, channel_name)`. A user can subscribe to a channel only once. The index on `channel_name` is used when a notice is emitted, to find all subscribers of the channel.
- User IDs are `BIGINT(20) UNSIGNED`, the same type as `wp_users.ID`.
- `channel_name` is `VARCHAR(64)` in every table. Before v2 the code used 50, and the docs said 32, 64 or 65.

Version 1 of the queue and subscriptions tables had no primary key at all, so the same row could be stored many times.

## Dates

All dates are stored in UTC and written from PHP with `gmdate( 'Y-m-d H:i:s' )`.

The columns have no `DEFAULT CURRENT_TIMESTAMP()`. That default uses the time zone of the database server, which is often not UTC and is not the site time zone either. It also makes `dbDelta()` think the column changed, so it would run an `ALTER TABLE` on every upgrade check.

## Why `meta` is JSON text

Some values are only needed for display and are never searched: the accept and dismiss labels, the accept link, the icon and `is_dismissible`. They are stored together as JSON in the `meta` column.

The column is `TEXT`, not `JSON`. MariaDB stores `JSON` as `LONGTEXT`, so `dbDelta()` would always see a difference between the table and the schema.

These values are not in a separate meta table, like `wp_postmeta`, because that would add a query or a join for every notice. If we later need to search on one of these values, it can move into its own column.

## Text sizes

`message` is `TEXT`. In version 1 it was `TINYTEXT`, which holds 255 bytes. With `utf8mb4` that is only about 85 characters of Chinese, Japanese or Korean text, which is too short for many messages. `title` stays `TINYTEXT` because titles are meant to be short.

## Expiry and cleanup

`expires_at` is on the message, not on the queue row, because a notice expires at the same time for everyone.

Queue rows can be removed after they are dismissed or expired, and a message with no queue rows left can be deleted. The plugin already has two options for this, `max_lifespan` (6 months by default) and `delete_on_dismiss`, but nothing uses them yet. There is no cleanup job today.

## Upgrades

The schema version is saved in the `wp_notifications_db_version` option, and it is now `2`. New installs create the v2 tables. Existing v1 installs are not upgraded yet. They stay on the v1 tables after a plugin update until the upgrade routine in [#616](https://github.com/WordPress/wp-feature-notifications/issues/616) lands.

## Open questions

We would like feedback on these:

- Who is allowed to subscribe to a channel? Channels have no role or capability setting yet.
- When and how should old notices be cleaned up?
- Is one message row per locale good enough for multilingual sites?

Leave a comment on [#391](https://github.com/WordPress/wp-feature-notifications/issues/391), or join the [#feature-notifications](https://wordpress.slack.com/messages/C2K1C71FE) channel on Make WordPress Slack.
