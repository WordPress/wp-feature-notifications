# Admin notices with declared intent: proposed next step

Status: **proposal**. Nothing in it has been agreed.
Builds on: [`declared-intent-exploration.md`](./declared-intent-exploration.md) and [`issue-channel-intent.md`](./issue-channel-intent.md)

## Summary

The most pressing problem is plugins from the directory that fill the admin with banners. The plugin guidelines need to be updated and enforced to stop this. To enforce a guideline with tools, every admin notice needs a **declared intent** that tools can read.

This proposal reuses the work already in wp-feature-notifications. The key idea is:

> An admin notice is one more **surface** where a channel's messages can appear. It is not a separate system.

A plugin registers a channel that declares an intent. It then shows the notice with the existing `wp_admin_notice()` and passes the channel name. The same registration later lets the message go to the notification centre, email or mobile, without a new API.

## Proposed API

```php
// Registered once, on init. Declares what the notice is for and where it may appear.
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

This combines two options from the exploration notes:
- **Option B (register notices):** the intent is declared ahead of time in one place that tools can read.
- **Option A (extend `wp_admin_notice()`):** plugins keep using the helper they already know. They only add one argument.

The `channel` argument is better than an `intent` argument on its own. From the channel, core gets:
- the intent;
- a namespace that ties the notice to its plugin;
- persistent dismissals;
- per-user muting.

All of these come from the same lookup.

## What we can reuse

| Existing piece | Location | What it gives admin notices |
|---|---|---|
| Channel registry | `includes/class-channel-registry.php` | Registration ahead of time, with names validated as `namespace/name`. This becomes the list of every notice a plugin can show. |
| `Channel::$context` (unused today) | `includes/model/class-channel.php` | Already described as a "default display context". It becomes `surfaces`: `admin_notice` and `hub` now, `email` and `push` later. |
| Display contexts in JS | `src/store/constants.ts`, `src/utils/init.tsx` | `addContext()` already draws notifications into any `#wp-notifications-{context}` container using `NoticesArea`. Admin notices become one more context, drawn in a container output on the `admin_notices` hook. |
| `Message` model | `includes/model/class-message.php` | Covers everything `wp_admin_notice()` takes, and more: title, message, severity, accept and dismiss labels, `is_dismissible`, `expires_at` and icon. The 255-character message limit blocks large HTML banners by design. |
| Per-user queue (`dismissed_at`, `displayed_at`) | `includes/database/class-schema.php` | Dismissals that persist per user. Core admin notices don't have this, so every plugin builds its own with options, user meta and AJAX. **Getting this for free is the main reason plugins would adopt the API.** |
| Status update over REST | `includes/restapi/class-notification-controller.php` (#176) | The endpoint the notice UI calls to dismiss a notice. |
| Subscriptions with `snoozed_until` | `includes/model/class-subscription.php` | Muting and snoozing per user and per channel. Adding "mute by intent" gives one switch to hide all promotional notices. |
| Channel REST controller | `includes/restapi/class-channel-controller.php` | Lists every registered channel and its intent. Admins can audit it, and it can drive a settings screen. |

## What needs to be built

### 1. `intent` and `surfaces` on channels

- Add both properties to `Model\Channel` and `register_channel()`.
- Reject unknown values with `_doing_it_wrong()`.
- Return both in the channel REST response.
- Covered by [`issue-channel-intent.md`](./issue-channel-intent.md), with `surfaces` added.

### 2. Notices driven by a condition

The current model is **event based**: something happens, a message is stored, and it is sent out to users. Most admin notices don't work like that:

- A `setup` notice should show **while** something is unfinished, and stop once it is done.
- A `feedback` notice only lasts for the current request, or the redirect after it.

Storing a message row per user for "connect your account" is the wrong shape. Instead:

- A notice is drawn when `wp_admin_notice()` is called, or when its registered `condition` callback returns true.
- The queue table only stores the per-user state, which is dismissed or displayed.
- Event-based messages, such as "backup failed", keep using the existing stored path.

### 3. Intent rules

There should be one place where the intent rules from the exploration notes are enforced at runtime:

| Intent | Admin notice rule |
|---|---|
| `operational` | Allowed anywhere. |
| `setup` | Allowed. Must be dismissible. |
| `activity` | Not shown as an admin notice. Hub only. |
| `feedback` | Only right after the user's own action. Never stored. |
| `promotional` | Only on the plugin's own screens. Always dismissible. Never `error` or `warning`. |

Rules that apply to every intent:
- `type` and `severity` must come from a fixed list. Today, nothing validates severity.
- A notice must use a registered channel. Today, nothing checks `is_registered()` on the send path.

Open problem: how does core decide which screens belong to a plugin? One option is to check them against the menu pages registered by the plugin that owns the channel's namespace.

### 4. Pluggable delivery methods (laying the groundwork)

Nothing defines a delivery method yet. Add a small interface:

```php
interface Surface {
	public function get_name(): string;
	public function supports( Model\Channel $channel ): bool;
	public function deliver( Model\Message $message, array $recipients ): void;
}
```

- The first two surfaces are `admin_notice` (new) and `hub` (exists).
- `email` and `push` are added later, with no change to the plugin-facing API.
- A router chooses the surfaces for each message, based on:
  - the channel's `surfaces`;
  - the intent rules;
  - the user's preferences.

### 5. Naming for core

`register_channel()` lives in the `WP\Notifications` namespace. A core patch would need a prefixed global name, such as `wp_register_notification_channel()`.

## How enforcement works

| Layer | What it checks |
|---|---|
| **Plugin Check** (without running code) | `admin_notices` callbacks that echo HTML, or calls to `wp_admin_notice()` with no `channel`. A `promotional` channel used with `type => 'error'` or `'warning'`. Channels that aren't namespaced to the plugin's slug. |
| **Core** (at runtime) | `_doing_it_wrong()` for unregistered channels and unknown intents. Intent rules applied when the notice is drawn, for example a promotional notice is hidden on screens that don't belong to its plugin. |
| **Directory review** | Updated Guideline 11 wording that refers to declared intent. |
| **Legacy notices** | Option D from the exploration notes: notices with no declared intent are collapsed into one line, such as "5 notices". Errors and warnings still show in full. |

## Two tracks: guidelines and core

Plugin guidelines can't require plugins to use an API that isn't in core yet. So the work runs on two tracks:

1. **Guidelines, starting now.** Update Guideline 11 with rules based on intent, for example: "promotional notices only on your own screens, always dismissible, never styled as errors." Human review enforces them at first.
2. **A small core patch, next.** Take the channel registry with `intent` and `surfaces`, plus the `channel` argument for `wp_admin_notice()`, from this plugin. Once it lands, Plugin Check can enforce the same rules automatically.

The notification centre, email and mobile delivery come after that. They build on the same registrations, and plugins don't need to change anything.

## Proposed order of work

1. Add `intent` and `surfaces` to channels ([`issue-channel-intent.md`](./issue-channel-intent.md)).
2. Validate on the send path: registered channels only, and a fixed list of severities.
3. Add the `admin_notice` surface: the `channel` argument for `wp_admin_notice()`, condition-based notices, and persistent dismissals using the queue table.
4. Add the intent rules.
5. Prototype the Plugin Check rules.
6. Collapse legacy notices.
7. Add the `Surface` interface and router. Then the email and push surfaces.

## Decisions needed

1. ~~**Intents:**~~ Decided: all five, fixed, required on every channel. See [`issue-channel-intent.md`](./issue-channel-intent.md).
2. **`channel` or `intent` on `wp_admin_notice()`:** require a registered channel, or also allow a bare `intent` for one-off notices?
3. **Screen ownership:** how does core decide which screens belong to a plugin, for the promotional rule?
4. **Feedback notices:** should they need a registered channel, or is `type => 'success'` enough to imply `feedback`?
5. **Where the core patch lives:** propose the minimal registry and `wp_admin_notice()` change as a core Trac ticket now, or prove it in this plugin first?
