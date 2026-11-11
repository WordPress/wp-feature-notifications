# Add "intent" and "surfaces" to channel registration

## Summary

When a plugin registers a notification channel, it must also say **why** that channel sends notifications. This is the channel's **intent**. It can also say **where** the channel's notifications may appear. These are the channel's **surfaces**.

There are five intents. Core controls the list, and it can't be filtered.

| Intent | Test: what triggered it? | Example |
|---|---|---|
| `operational` | The **site or an automated process** changed something, or something needs attention to keep the site working | An update is available, a backup failed, a scheduled post was published |
| `setup` | Something is **unfinished**. It must stop once the task is done | "Connect your account to finish setup" |
| `activity` | **Another person** did something that involves you | A new comment, "Alex updated your page" |
| `feedback` | **You** just did something, and this is the result | "Settings saved", "Invalid email address" |
| `promotional` | **None of the above**: the sender started it | Upsells, discounts, tips, review requests, surveys |

There are two surfaces for now. More can be added later without changing this API.

| Surface | Where it appears |
|---|---|
| `hub` | The notification centre in the admin bar. This is the default. |
| `admin_notice` | Inline, as an admin notice |

`email` and `push` are planned as later surfaces. See [`admin-notices-with-intent.md`](./admin-notices-with-intent.md).

## Why

"Declared intent" came up in the [design discussion on Make/Core](https://make.wordpress.org/core/2026/10/29/exploring-a-design-for-feature-notifications/). Channels already group notifications by topic, such as `core/updates`. However, a channel's name doesn't tell WordPress what kind of notification it sends. To the system, `acme/orders` and `acme/deals` look the same.

When every channel has an intent:

- **Plugin Check can check it.** Each intent is declared once, in a place that can be read without running the plugin. This is what lets the plugin guidelines on admin notices be enforced by tools.
- **WordPress can enforce rules.** For example, a promotional notification can never be marked critical.
- **Users can mute by intent.** For example, they can turn off every promotional notification with one switch. This is follow-up work.

Surfaces prepare channels for admin notices, as proposed in [`admin-notices-with-intent.md`](./admin-notices-with-intent.md).

## Rules for choosing an intent

- **Is a person behind it?** That decides between `activity` and `operational`.
- **`promotional` covers everything else.** There is no "informational" intent, because it would become a loophole.
- **Intent is not urgency.** A security alert is `operational` with critical severity.
- **One intent per channel.** A message can't change its channel's intent. If a topic covers more than one intent, register one channel per intent.

## Example

```php
register_channel(
	'my-plugin/orders',
	array(
		'title'    => __( 'Orders', 'my-plugin' ),
		'intent'   => 'operational',
		'surfaces' => array( 'hub', 'admin_notice' ),
	)
);
```

## Core channels

| Channel | Intent |
|---|---|
| `core/updates` | `operational` |
| `core/plugin-install` | `operational` |
| `core/plugin-uninstall` | `operational` |
| `core/plugin-activate` | `operational` |
| `core/plugin-deactivate` | `operational` |
| `core/plugin-updates` | `operational` |
| `core/post-new` | `activity` |
| `core/post-edit` | `activity` |
| `core/post-delete` | `activity` |
| `core/comment-new` | `activity` |
| `core/post-automated` (new) | `operational` |

Post and comment channels are for changes made by **another person**. Changes made by a cron job, sync, import or AI agent go to the new `core/post-automated` channel. Confirmations of the user's own changes are `feedback` and don't go through these channels.

## Work to be done

**Channel model and registration**
- [ ] Add `intent` and `surfaces` properties to `Model\Channel`. This replaces the unused `context` property.
- [ ] Make `intent` required in `register_channel()`. If it's missing or unknown, call `_doing_it_wrong()` and don't register the channel.
- [ ] Make `surfaces` optional, with a default of `array( 'hub' )`. If any value is unknown, call `_doing_it_wrong()` and don't register the channel.
- [ ] Define the intents and surfaces as constants in an interface, following `Status_Interface`.

**Core channels**
- [ ] Set an intent on every core channel in `includes/channels.php`, using the table above.
- [ ] Register `core/post-automated`.

**Sending notifications**
- [ ] Store the intent with each message, so it's still available after the plugin that sent it is removed. This needs a schema change to `notifications_messages`.
- [ ] Reject `promotional` messages that use a critical severity.

**REST API**
- [ ] Return `intent` and `surfaces` in channel responses.
- [ ] Return `intent` in notification responses.

**Tests and docs**
- [ ] Add PHPUnit tests for validating registration, the defaults, the severity rule and the REST responses.
- [ ] Update the developer docs and the database schema docs.

## Depends on

- [`issue-send-path-validation.md`](./issue-send-path-validation.md): messages must use registered channels, and severity must come from a fixed list. The "promotional is never critical" rule depends on the fixed list of severities.

## Out of scope

- **Muting by intent, and whether `promotional` is off by default.** This belongs with the user preferences work.
- **The `admin_notice` surface itself:** the `channel` argument for `wp_admin_notice()`, notices driven by a condition, and the intent rules for admin notices. See [`admin-notices-with-intent.md`](./admin-notices-with-intent.md).
- **The `Surface` interface and the delivery router, and the `email` and `push` surfaces.**
