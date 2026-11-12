# Declared intent: proposal for the path forward

Status: **proposal**. Decisions were made on 2026-11-11 and 2026-11-12. See [Decisions](#decisions).
Issue for the first step: [`issue-channel-intent.md`](./issue-channel-intent.md)

## Summary

The most pressing problem is plugins from the directory that fill the admin with banners. The plugin guidelines need to be updated and enforced to stop this. To enforce a guideline with tools, every plugin notification and admin notice needs a **declared intent** that tools can read.

This proposal reuses the work already in wp-feature-notifications:

- Every notification channel declares an **intent**: why it sends notifications.
- An admin notice is one more **surface** where a channel's messages can appear. It is not a separate system.
- **WordPress Core's own admin notices are exempt.** Only plugins must declare intent. This keeps changes to core small.

## Background

The idea of "declared intent" came up in the comments on [Exploring a design for feature notifications](https://make.wordpress.org/core/2026/10/29/exploring-a-design-for-feature-notifications/):

- **David Perez (Nov 5):** plugins should declare an intent (operational, setup, promotional) that tools like Plugin Check can check. The API should enforce limits based on it, such as deduplication, rate limits, and "promotional is never critical".
- **Matt Mullenweg (Nov 7):** called it brilliant, and suggested making it required for admin notices and super admin notices.
- **poligilad (Nov 9):** said it fits the design, which already separates operational from promotional, and that "setup" makes sense as a third value.

Channels group notifications by **topic**, such as `core/updates`, and plugins choose their names freely. To WordPress, `acme/orders` and `acme/deals` look the same. Channels are still the right place to declare intent: it is declared once, in a place tools can read without running the plugin.

## Intents

David's three intents leave two gaps: activity from other people (comments, mentions), and confirmations of the user's own actions ("Settings saved"). So there are five intents. Core controls the list, and it can't be filtered.

| Intent | Test: what triggered it? | Examples |
|---|---|---|
| `operational` | The **site or an automated process** changed something, or something needs attention to keep the site working | Update available, backup failed, scheduled post published, import finished |
| `setup` | Something is **unfinished**. It must stop once the task is done | "Connect your account", "Finish configuring payments" |
| `activity` | **Another person** did something that involves you | New comment, mention, "Alex updated your page" |
| `feedback` | **You** just did something, and this is the result | "Settings saved", "Invalid email address" |
| `promotional` | **None of the above**: the sender started it | Upsells, discounts, tips, "What's new", review requests, surveys |

### Rules for choosing an intent

- **Is a person behind it?** That decides between `activity` and `operational`.
- **`promotional` covers everything else.** There is no "informational" intent, because it would become a loophole. Plugin Check can't tell an educational tip from a soft sell.
- **Intent is not urgency.** A security alert is `operational` with the `alert` severity.
- **One intent per channel.** A message can't change its channel's intent. If a topic covers more than one intent, register one channel per intent.

### Edge cases

| Notification | Intent | Why |
|---|---|---|
| "Your licence expired, updates have stopped" | `operational` | Reports a real change in the site's state |
| "Your licence expires in 30 days, renew now" | `promotional` | Asks for money ahead of time |
| "Please rate our plugin" or "Take our survey" | `promotional` | Benefits the sender |
| "Allow usage tracking?" | `promotional` | Optional, and benefits the sender |
| A Site Health recommendation | `operational` | Low severity |
| "The page has been updated" (by you) | `feedback` | Inline only, never in the hub |
| "The page has been updated" (by another person) | `activity` | |
| "The page has been updated" (by a cron job, sync, or AI agent) | `operational` | The existing `actor` field shows what made the change |

## Rules for each intent

Severities are a fixed list: `alert`, `info`, `warning` and `success`.

| Intent | Hub (notification centre) | Admin notice | Severity |
|---|---|---|---|
| `operational` | ✅ | ✅ | Any except `success` |
| `setup` | ✅ | ✅ Must be dismissible | Any except `success` |
| `activity` | ✅ | ❌ | Any except `success` |
| `feedback` | ❌ Never stored | ✅ Only right after the user's own action | Any. The only intent that may use `success` |
| `promotional` | ✅ | ⚠️ Hidden on core screens, always dismissible | `info` only, or none |

- **Promotional severity.** Limiting promotional messages to `info` replaces David's "promotional is never critical". There's no need to define "critical", and the rule is the same on every surface.
- **Promotional screens.** Core can't tell which plugin added a menu page, so "only on the plugin's own screens" can't be checked at runtime. Instead, promotional notices are hidden on a fixed list of **core screens**, such as the Dashboard, Plugins, Posts and Settings. If plugins start promoting on each other's screens, channels could later declare `screens`, and Plugin Check could compare them with the plugin's own `add_menu_page()` calls.

## Core notices are exempt

**WordPress Core's own admin notices are allowed by default. Only plugins must declare intent.**

- Core notices never pass a `channel`, so none of the intent rules apply to them.
- Plugin Check only scans plugins, so the exemption costs nothing on the static side.
- Core can adopt channels for its own notices later, if it wants to.

A notice without a channel could come from core or from a plugin. Telling them apart at runtime would need backtraces or callback file paths, which are slow and fragile. So undeclared plugin notices are left to Plugin Check and directory review. They are not collapsed or hidden at runtime.

## Proposed API

```php
// Registered once, on init. Declares what the channel is for and where it may appear.
register_channel(
	'acme/connect-account',
	array(
		'title'    => __( 'Account connection', 'acme' ),
		'intent'   => 'setup',
		'surfaces' => array( 'admin_notice', 'hub' ),
	)
);

// Shown with the existing core helper (WordPress 6.4+), plus one new argument.
wp_admin_notice(
	__( 'Connect your Acme account to finish setup.', 'acme' ),
	array(
		'channel'     => 'acme/connect-account',
		'type'        => 'info',
		'dismissible' => true,
	)
);
```

Every `wp_admin_notice()` call from a plugin needs a `channel`, including feedback. Feedback can also be an error, such as "Invalid email address", so `type => 'success'` can't imply the intent. A plugin can register one general channel, such as `acme/feedback`, for these.

A `channel` is better than a bare `intent` argument. From the channel, WordPress gets the intent, a namespace that ties the notice to its plugin, persistent dismissals and per-user muting, all from one lookup.

### No core patch is needed for v1

`wp_get_admin_notice()` passes its arguments through `wp_parse_args()`, which keeps keys it doesn't know. So the `channel` key already reaches two existing filters (checked against WordPress 6.7.2, `wp-includes/functions.php`):

| Filter | What the feature plugin does with it |
|---|---|
| `wp_admin_notice_args` | Looks up the channel's intent. Applies rules such as forcing `dismissible`, and maps `type => 'error'` to the severity `alert`. |
| `wp_admin_notice_markup` | Returns `''` to hide a notice that breaks a rule, for example a promotional notice on a core screen. |

On sites without the feature plugin, the `channel` key is silently ignored, so plugins can adopt it right away. A later core patch would only need to merge the channel registry and document the `channel` argument. In core, `register_channel()` would need a prefixed global name, such as `wp_register_notification_channel()`.

## What we can reuse

| Existing piece | Location | What it gives us |
|---|---|---|
| Channel registry | `includes/class-channel-registry.php` | Registration ahead of time, with names validated as `namespace/name`. |
| `Channel::$context` (unused today) | `includes/model/class-channel.php` | Already described as a "default display context". It becomes `surfaces`. |
| Display contexts in JS | `src/store/constants.ts`, `src/utils/init.tsx` | `addContext()` already draws notifications into any `#wp-notifications-{context}` container. Admin notices become one more context. |
| `Message` model | `includes/model/class-message.php` | Covers everything `wp_admin_notice()` takes, and more. The 255-character message limit blocks large HTML banners by design. |
| Per-user queue (`dismissed_at`, `displayed_at`) | `includes/database/class-schema.php` | Dismissals that persist per user. Core admin notices don't have this, so every plugin builds its own. **Getting this for free is the main reason plugins would adopt the API.** |
| Notification REST controller | `includes/restapi/class-notification-controller.php` | The endpoint the notice UI calls to dismiss a notice. |
| Subscriptions with `snoozed_until` | `includes/model/class-subscription.php` | Muting and snoozing per user and per channel. "Mute by intent" can build on it. |
| Channel REST controller | `includes/restapi/class-channel-controller.php` | Lists every registered channel and its intent, for auditing and a settings screen. |

## What needs to be built

1. **Intent and surfaces on channels, and send-path validation.** Covered by [`issue-channel-intent.md`](./issue-channel-intent.md).
2. **The `admin_notice` surface.** The `wp_admin_notice_args` and `wp_admin_notice_markup` filters, and persistent dismissals using the queue table.
3. **Notices driven by a condition.** The current model is event based: something happens, and a message is stored. Most admin notices don't work like that. A `setup` notice should show *while* something is unfinished, and a `feedback` notice only lasts for one request. These notices are drawn when `wp_admin_notice()` is called, or when a registered `condition` callback returns true. The queue table only stores per-user state, such as dismissed. Event-based messages, such as "backup failed", keep using the stored path.
4. **Intent rules at runtime**, as in the table above.
5. **Plugin Check rules** (prototype).
6. **A `Surface` interface and router**, so `email` and `push` can be added later without changing the plugin-facing API:

```php
interface Surface {
	public function get_name(): string;
	public function supports( Model\Channel $channel ): bool;
	public function deliver( Model\Message $message, array $recipients ): void;
}
```

## How enforcement works

| Layer | What it checks |
|---|---|
| **Plugin Check** (without running code) | `admin_notices` callbacks that echo HTML. Every `wp_admin_notice()` call with no `channel`. A `promotional` channel used with any type other than `info`. Channels that aren't namespaced to the plugin's slug. |
| **Runtime** (the feature plugin) | `_doing_it_wrong()` for unregistered channels, unknown intents and severities that break the rules. Intent rules applied when a notice is drawn. Notices without a channel are left alone, because they may come from core. |
| **Directory review** | Updated Guideline 11 wording that refers to declared intent. |

## Two tracks: guidelines and code

Plugin guidelines can't require an API that isn't in core yet. So the work runs on two tracks:

1. **Guidelines, starting now.** Update Guideline 11 with rules based on intent, for example: "promotional notices never on core screens, always dismissible, never styled as errors." Human review enforces them at first.
2. **This plugin, next.** Prove the registry, intent rules and dismissals here. No core patch is needed for v1. Once plugins can rely on the API, Plugin Check can enforce the same rules automatically.

The notification centre, email and mobile delivery build on the same registrations, and plugins don't need to change anything.

## Alternatives considered

- **A bare `intent` argument on `wp_admin_notice()`.** Rejected: a channel carries the intent plus a namespace, dismissals and muting.
- **A separate `register_admin_notice()` API.** Rejected: channels already do this job.
- **Moving all notices into the hub.** Kept as the long-term direction, but too large a change for now.
- **Collapsing undeclared notices at runtime** (poligilad's "5 notices" idea). Dropped: it needs core and plugin notices to be told apart at runtime.

## Decisions

Decided on 2026-11-11:

1. **Intents:** all five: `operational`, `setup`, `activity`, `feedback` and `promotional`.
2. **Required:** a channel with no intent is rejected.
3. **Fixed:** the list of intents can't be filtered.
4. **One intent per channel.** A message can't change its channel's intent.
5. **Scope:** channel intent comes first. Admin notices follow.

Decided on 2026-11-12:

6. **Core notices are exempt.** Only plugins must declare intent.
7. **Undeclared notices** are enforced statically only, by Plugin Check and directory review.
8. **Every `wp_admin_notice()` call from a plugin needs a `channel`**, including feedback.
9. **Promotional severity:** `info` only, or none, on every surface.
10. **`success`:** kept, but only for `feedback` channels.
11. **Promotional screens:** hidden on core screens, allowed elsewhere.
12. **Where the code lives:** prove it in this plugin first. No core patch for v1.

## Still open

- **Promotional off by default?** Moved to the user preferences work.
- **Next step in the discussion:** reply on the Make/Core post to propose `activity` and `feedback` and the core exemption, and ask David and poligilad for feedback?
