# The original notifications plan and declared intent

Status: **analysis**, written on 2026-11-24.
Related: [`declared-intent-proposal.md`](./declared-intent-proposal.md), [`plan-notice-survey.md`](./plan-notice-survey.md)

## Summary

The comments on [the declared intent proposal on Make/Core](https://make.wordpress.org/core/2026/11/16/wordpress-notifications-and-declared-intent-proposal-for-the-path-forward/) raise several problems. This analysis answers two questions:

1. Would the original plans for the notifications system have solved those problems?
2. Would the original plan, with stricter guidelines and enforcement, have stopped plugins abusing admin notices?

The short answers:

1. **About half of them.** The problems that come from the proposal's shortcuts (core notices are exempt, and v1 uses `wp_admin_notice()` filters with no core patch) are ones the original would have avoided. The most important objection, that plugins can skip declaring, applies to the original as well.
2. **Partly.** The original gives promotional messages somewhere to go, controls how notices are drawn, and lets users mute channels. Tools still can't enforce exceptions, and nothing stops promotional messages being bundled into operational channels. Both need a declared category, which is intent. The original also depended on a large core merge that never happened.

**Conclusion:** the original plan and declared intent are not rivals. Each one fills the other's gap. Shipping intent first, as the proposal does, is still the right order.

## What the original plan was

From the code in this repository:

- **A hub** in the admin bar, and **display contexts** that core controls (`adminbar`, `dashboard`, or any `#wp-notifications-{context}` container). See `src/store/constants.ts` and `src/utils/init.tsx`.
- **Core draws every notification.** Plugins send data, not markup. The `Message` model limits messages to 255 characters.
- **Channels** registered ahead of time, with core channels such as `core/updates` in `includes/channels.php`. Core was meant to be the first user.
- **Per-user state:** dismissals in the queue table, and muting or snoozing per channel (`Subscription::$snoozed_until`).
- **A client-side store** with `addNotice`, `removeNotice` and `updateNotice` (`src/store/actions.ts`).
- `Channel::$context` was a *default* display context, which suggests messages could override it. That override was never built.

## Part 1: The comments compared with the original plan

### Where the original plan would have helped

| Comment | Problem | What the original already had |
|---|---|---|
| Adam Silverstein | Why is core exempt? | Core was meant to be the first user. Core channels are already registered. The exemption is a new choice, and the reply on Make/Core already accepted dropping it. |
| Joe Fusco | Client-side notices (Presence's Heartbeat banner) never reach the PHP filters | The JS store already has `addNotice`. Joe's other problem, that `activity` can't appear as an admin notice, only exists because of the new rules table. |
| poligilad | Where do plugins with no admin screens put promotional notices? | The hub. This is the clearest gap in the new proposal. |
| poligilad | Use a Snackbar for success messages | The plugin already renders React on every admin screen. A Snackbar is therefore not limited to the block editor, which is what the reply on Make/Core said. |
| poligilad, Yani | Where notices appear, and placement per message | Partly. Core controlled placement through contexts. Overriding the context per message was implied but never built. |

### Where it wouldn't have helped

- **David Perez: undeclared notices bypass everything.** The original was opt-in too. Plugins could always keep echoing into `admin_notices`.
- **Yani: "location is a fact, intent is a claim".** The original had no idea of screen ownership or review. A plugin could add its own context container on the Dashboard.
- **"Five is too many", and reporting misuse.** The original had no intents, so these questions didn't come up.
- **Static declarations** (Yani's idea of a `block.json`-style file). `register_channel()` was always a runtime call.

### What this means

The new proposal makes the admin-notice surface its starting point. Because of that, it inherits that surface's problems: it's server-side only, it has no destination for plugins without screens, and it has to exempt core. The original's strength was a destination that core controls. Its weakness was that nothing made plugins use it.

## Part 2: Would the original plan, stricter guidelines and enforcement have worked?

### Where the theory holds

- **"Did it use the API?" can be verified.** With a guideline such as "plugins must send notifications through the API", Plugin Check only has to find raw `admin_notices` callbacks that echo HTML. Whether a plugin used the API is a fact. Intent is only a claim, so this check is harder to game.
- **Core decides how notices are drawn.** Inside the API, plugins can't add full-width HTML banners.
- **Users can enforce it themselves.** Users can mute or snooze a hub channel. Declared intent has nothing as direct.

### Where it breaks

1. **Exceptions need a category.** "Hub only" is too strict. Some notices legitimately belong inline: a setup prompt after activation, "backup failed", or "settings saved" on the plugin's own screen. Once exceptions are allowed, someone has to decide which messages qualify. A tool can only do that with a declared category, which is intent.
2. **Promotional messages can hide in operational channels.** A plugin can send upsells through `acme/orders`. A user who mutes that channel also loses the order alerts. Per-channel muting only works if a channel can't mix purposes, which is what "one intent per channel" makes sure of.
3. **Guidelines add little without new tools.** Guideline 11 already asks plugins not to hijack the dashboard, and to keep upsells limited and dismissible. Reviewers can't enforce that across every plugin and every update. Tools could, but tools need signals they can read. *(The Guideline 11 wording here is from memory. Check it in the survey.)*
4. **It depends on a core merge that never happened.** Guidelines can't require an API that isn't in core. The theory needs the hub UI, the database tables, the REST API and the React app to be merged first. That step has stalled since this repository started in 2018, so the theory has never been tested.

### Comparison

| | Original + guidelines | Intent alone (current proposal) | Both |
|---|---|---|---|
| "Used the API" can be checked | ✅ | ✅ | ✅ |
| Somewhere for promotional messages to go | ✅ Hub | ❌ Until a hub exists | ✅ |
| Exceptions can be enforced by tools | ❌ | ✅ | ✅ |
| Bundling can't defeat muting | ❌ | ✅ | ✅ |
| Can ship without a large core merge | ❌ | ✅ | ⚠️ Only if intent ships first |

## A possible direction: hub first

A version that combines both plans would answer most of the comments:

1. **Hub first.** Make the hub, not admin notices, the v1 surface for intent, and have core adopt channels (Adam).
2. **A guideline rule:** `promotional` messages go to the hub or the plugin's own screens, never to inline notices on other screens (Yani, poligilad).
3. **One store for both sides.** Admin notices become a context fed by the PHP filters and the JS store (Joe).

David's bypass problem remains for Plugin Check and directory review to handle, under either design.

## What this analysis hasn't tested

These claims are based on reasoning, not data. The most important one to test is **point 1 under "Where it breaks"**: how many legitimate notices would a "hub only" rule block? [`plan-notice-survey.md`](./plan-notice-survey.md) describes the research to find out.
