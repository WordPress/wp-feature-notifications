# Research plan: survey of real plugin admin notices

Status: **planned**, written on 2026-11-24. Not started.
Background: [`original-plan-vs-declared-intent.md`](./original-plan-vs-declared-intent.md), [`declared-intent-proposal.md`](./declared-intent-proposal.md)

## Goal

Test the proposal and the comments on it against the admin notices that real plugins show, instead of against made-up examples.

## Questions to answer

| # | Question | Where it comes from | Answered by |
|---|---|---|---|
| Q1 | How many legitimate notices would a "hub only" rule block? | Original plan vs. intent, point 1 under "Where it breaks" | The `needs_inline` column |
| Q2 | Can every notice be given one of the five intents without arguing about it? Where do reviewers disagree? | David Perez: "Five is too many" | Classification, and the "unclear" notes |
| Q3 | Would three intents (`operational`, `setup`, `promotional`) lose anything that matters? | David Perez | Q2 results: how many `activity` and `feedback` notices there are, and where they appear |
| Q4 | How often do plugins mix purposes in one notice or one code path? | The bundling problem | The `mixed` column |
| Q5 | Which screens do notices appear on, and does the plugin own those screens? | Yani, poligilad | The `screens` and `own_screen` columns |
| Q6 | How many notices are drawn on the client side? | Joe Fusco | The `client_side` column |
| Q7 | How many plugins have no admin screen of their own? | poligilad | The plugin list |
| Q8 | How many notices already use `wp_admin_notice()`, and how many echo raw HTML? | How hard adoption will be, and what Plugin Check can detect | The `mechanism` column |

## Choosing the plugins

Pick **15–20 plugins** from the directory, aiming for this mix:

- **By category:** SEO, forms, backup and migration, security, caching and performance, ecommerce and payments, page builders, analytics, and social or marketing. Pick one or two by active installs in each.
- **Freemium plugins with paid upgrades.** At least five, because they're the most likely to show promotional notices.
- **At least two plugins with no admin screen of their own**, for Q7.
- **The plugins of people who commented**, if they're in the directory: All-in-One WP Migration (Yani). Also look at the Presence API feature plugin (Joe) for Q6, even though it isn't in the directory.

Record each plugin's slug, version and active installs, so the survey can be repeated. Don't single out plugins by name in anything published. Report totals, and use anonymised examples.

## Method

### 1. Set up

- Use this repository's `wp-env` environment, with the feature plugin **deactivated**, so it doesn't change any notices.
- Install one plugin at a time, or a few that don't conflict: `npx wp-env run cli wp plugin install <slug> --activate`.
- Reset the site between plugins (`npx wp-env clean all`), so notices from one plugin don't hide another's.

### 2. Static pass (read the code)

Search each plugin for:

- `admin_notices`, `all_admin_notices`, `network_admin_notices`, `user_admin_notices`
- `wp_admin_notice(` and `wp_get_admin_notice(`
- `notice notice-`, `class="notice` and `updated`/`error` class names in echoed HTML
- On the client side: `createNotice`, `core/notices`, `wp.data.dispatch`, and `Snackbar`
- Notices on a timer: `time()` or option timestamps near notice code, such as "show a review request after 7 days"

For each notice found, write down what condition shows it. The runtime pass can miss notices on a timer or a condition, and this step finds them.

### 3. Runtime pass (look at the admin)

For each plugin, visit these screens and take a screenshot of each notice:

- Dashboard, Plugins, Posts, Pages, Settings → General, Users
- The plugin's own screens, if it has any
- The block editor (for client-side notices)

Visit them in each of these states:

1. **Right after activation**
2. **After finishing setup** (or skipping it, if setup isn't possible without an account)
3. **Later on.** Fake the passage of time by editing the plugin's timestamp options, as found in the static pass.

Optionally, record a GIF of each plugin's visit.

### 4. Classify

Record one row per notice in a CSV (`research/data/notice-survey.csv`):

| Column | Values |
|---|---|
| `plugin` | Slug |
| `notice_id` | Plugin slug plus a short label |
| `text` | Shortened notice text |
| `mechanism` | `wp_admin_notice`, `raw_echo`, `js_notices_store`, `custom_js`, `other` |
| `client_side` | yes / no |
| `screens` | Screens where it appeared |
| `own_screen` | yes / no / mixed |
| `trigger` | `activation`, `condition`, `event`, `timer`, `user_action` |
| `dismissible` | `no`, `this_page_only`, `persistent` |
| `intent` | One of the five intents, or `unclear` |
| `intent_3` | The intent under David's three-intent list |
| `mixed` | yes / no: does the notice serve more than one purpose? |
| `needs_inline` | yes / no: would moving it to the hub hurt the user? |
| `breaks_rules` | Which proposal rules it breaks, if any (for example, promotional on a core screen, or promotional not using `info`) |
| `notes` | Edge cases and reasons for `unclear` |

**To check the classification, a second person should classify a sample of about 20% of the notices without seeing the first person's answers.** Record where the two disagree. Q2 depends on this.

### 5. Check the facts the earlier research relied on

- The current wording of **Guideline 11**, compared with the summary in the analysis.
- Whether `@wordpress/components` `Snackbar` can be used outside the block editor in this plugin's React setup, for poligilad's question.
- That `wp_admin_notice_args` and `wp_admin_notice_markup` still pass unknown keys through in the WordPress version being tested.

## Results to write

Put the results in `research/notice-survey-results.md`:

- Totals for each question, Q1–Q8.
- Anonymised examples of notices that were `unclear`, `mixed` or `needs_inline`.
- What the results mean for the proposal. Decide each of these:
  - Keep five intents, or reduce them to three?
  - Make the hub the first surface, or admin notices?
  - Should channels declare `screens` from the start?
  - Should client-side notices be in scope for v1?
- A draft follow-up comment for the Make/Core post that shares the data.

## How to read the results

| Result | What it suggests |
|---|---|
| Few `needs_inline` notices (under about 20%) | "Hub only, with narrow exceptions" is workable. That supports hub first. |
| Many `needs_inline` notices | The exceptions are the main case, so intent is required to enforce them. |
| Many `unclear` notices, or reviewers often disagree | The intent definitions need work before Plugin Check relies on them. |
| Few `activity` or `feedback` notices | David's three intents may be enough for admin notices, although email and push may still need all five. |
| Many `mixed` notices | Strong evidence for "one intent per channel". |
| Many notices on screens the plugin doesn't own | Supports declaring `screens` from the start (Yani, poligilad). |
| Many `raw_echo` notices | Adoption will be slow. Plugin Check's first rule should flag them. |

## Estimated effort

About one to two sessions: half a day for setup and the static pass, half a day for the runtime pass, and a few hours for classifying and writing up. Checking the classification depends on finding a second reviewer.
