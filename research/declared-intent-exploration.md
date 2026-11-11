# Declared intent: exploration notes

Status: **draft for review**. No decisions have been made yet.
Related draft issue: [`issue-channel-intent.md`](./issue-channel-intent.md)

## Background

The idea of "declared intent" came up in the comments on [Exploring a design for feature notifications](https://make.wordpress.org/core/2026/10/29/exploring-a-design-for-feature-notifications/):

- **David Perez (Nov 5):** plugins should declare an intent (operational, setup, promotional) that tools like Plugin Check can check. The API should enforce limits based on it, such as deduplication, rate limits, and "promotional is never critical".
- **Matt Mullenweg (Nov 7):** called it brilliant, and suggested making it required for admin notices and super admin notices.
- **poligilad (Nov 9):** said it fits the design, which already separates operational from promotional, and that "setup" makes sense as a third value.

## Do channels already declare intent?

Not in a way that can be enforced. Channels group notifications by **topic** (for example `core/updates`), and their names are chosen freely by plugins. Core can't tell `acme/orders` from `acme/deals`. Nothing is stored that a tool can read, and there is no fixed set of values for core to enforce.

Channels are still the **right place** to declare intent. If `intent` were a required argument to `register_channel()`, it would be declared once, in a place tooling can read.

Other gaps found in the current code:
- A message can be sent to a channel that was never registered. There is no `is_registered()` check on the send path.
- `severity` accepts any value (`VARCHAR(16)`, not validated).

## Proposed intents

David's three intents leave two gaps:

1. **Activity from other people.** Comments, mentions, block notes, and posts assigned or updated by someone else. These aren't operational, and users want to mute them separately. About half of this repo's core channels fall into this group.
2. **Confirmations of the user's own actions.** "Settings saved" or "Plugin activated". These matter once admin notices are considered (see below).

Proposed set:

| Intent | Test: what triggered it? | Examples |
|---|---|---|
| `operational` | The **site or an automated process** changed something, or something needs attention to keep the site working | Update available, backup failed, scheduled post published, import finished |
| `setup` | Something is **unfinished**. It must stop once the task is done | "Connect your account", "Finish configuring payments" |
| `activity` | **Another person** did something that involves you | New comment, mention, "Alex updated your page" |
| `feedback` | **You** just did something, and this is the result | "Settings saved", "Invalid email address" |
| `promotional` | **None of the above**: the sender started it | Upsells, discounts, tips, "What's new", review requests, surveys |

### Rules for applying the intents

- **Is a person behind it?** That decides between `activity` and `operational`.
- **`promotional` covers everything else.** No separate "informational" intent, because it would become a loophole. Plugin Check can't tell an educational tip from a soft sell.
- **Intent is not urgency.** A security alert is `operational` with critical severity. Severity and intent stay separate, linked by rules such as "promotional is never critical".

### Edge cases

| Notification | Intent | Why |
|---|---|---|
| "Your licence expired, updates have stopped" | `operational` | Reports a real change in the site's state |
| "Your licence expires in 30 days, renew now" | `promotional` | Asks for money ahead of time |
| "Please rate our plugin" or "Take our survey" | `promotional` | Benefits the sender |
| "Allow usage tracking?" | `promotional` | Optional, and benefits the sender |
| A Site Health recommendation | `operational` | Low severity |
| "The page has been updated" (by you) | `feedback` | Inline only, never in the tray |
| "The page has been updated" (by another person) | `activity` | |
| "The page has been updated" (by a cron job, sync, or AI agent) | `operational` | The existing `actor` field shows what made the change |

## Where each intent can appear

| Intent | Tray (notification centre) | Inline (admin notice) |
|---|---|---|
| `operational` | ✅ | ✅ |
| `setup` | ✅ | ✅ Must be dismissible |
| `activity` | ✅ | ❌ |
| `feedback` | ❌ Never stored | ✅ Only right after the user's action |
| `promotional` | ✅ Can be muted with one switch | ⚠️ Only on the plugin's own screens, always dismissible, never error or warning |

This partly answers the post's open question about which events go where, using intent instead of reviewing every event one by one.

## Applying intent to admin notices

Today a plugin can output an admin notice in two ways:
1. Echo HTML on the `admin_notices` hook. Most plugins do this, and core can't see any intent.
2. Call `wp_admin_notice( $message, $args )`, added in WordPress 6.4. Its arguments are `type`, `dismissible`, `id`, `additional_classes`, `attributes` and `paragraph_wrap`, run through the `wp_admin_notice_args` filter.

The same problem applies to `network_admin_notices`, `user_admin_notices`, and the block editor's JavaScript notices (`createNotice()`).

### Options

**A. Add `intent` to `wp_admin_notice()`**
```php
wp_admin_notice( 'Settings saved.', array( 'type' => 'success', 'intent' => 'feedback' ) );
```
- ✅ Small, backward compatible, easy for tooling to detect.
- ❌ Notices echoed straight to `admin_notices` skip it.

**B. Register notices, the same way channels are registered**
```php
register_admin_notice( 'my-plugin/connect-account', array(
	'intent'  => 'setup',
	'screens' => array( 'dashboard', 'plugins' ),
) );
```
- ✅ Declared ahead of time, can be checked statically, and allows per-screen rules.
- ❌ A new API that plugins must adopt.

**C. Merge notices into the notifications system**
- Anything that should stick around goes to the tray through channels. Only feedback stays inline.
- The `Channel` model already has an unused `context` ("display context") property that could decide between the two.
- ✅ One system, one intent model, one set of preferences.
- ❌ The largest change, with a long migration.

**D. Contain legacy notices**
- Notices with no declared intent are collapsed into one line, such as "5 notices" (poligilad's suggestion). Errors and warnings still show in full.
- ✅ Gives plugins a reason to declare intent, without breaking anything.
- ❌ Relies on `notice-*` classes, which plugins can abuse. Plugin Check and directory review would need to catch that.

**E. Enforce through the plugin directory**
- Plugin Check flags `admin_notices` callbacks that echo HTML instead of using `wp_admin_notice()` with an intent.
- Tied to Guideline 11 (don't hijack the admin dashboard).
- This is how intent becomes "required" in practice, since core can't break existing plugins.

### Suggested path

1. **A + E:** add intent to the existing helper and enforce it through Plugin Check.
2. **D:** collapse undeclared notices.
3. **C:** the long-term direction.
4. **B:** only if per-screen rules turn out to be needed.

### Admin notice `type` compared with severity

Admin notice types (`error`, `warning`, `success`, `info`) map closely to severity. `success` only makes sense alongside `feedback`, which explains why the notification design has no "success" severity: the tray never shows feedback.

## Decisions needed

1. **Which intents?** David's three, the four for the tray (adding `activity`), or all five (adding `feedback` for admin notices)?
2. **Required or defaulted?** Make `intent` required in `register_channel()`, which breaks existing callers, or default to `operational` with a deprecation notice?
3. **Fixed or filterable?** A fixed list is easier to check and harder to abuse.
4. **Promotional off by default?**
5. **Scope:** keep admin notices out of this plugin's first issue and follow up separately, or handle both together?
6. **Validation work:** include "registered channels only" and "a fixed list of severities" in the intent issue, or split them out?
7. **Next step in the discussion:** reply on the Make/Core post to propose `activity` and `feedback` and ask David and poligilad for feedback?
