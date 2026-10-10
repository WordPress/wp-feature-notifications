# WP Feature Notifications — 0.3.0 release plan

## Context

`wp-feature-notifications` is the WordPress feature plugin for [Trac #43484](https://core.trac.wordpress.org/ticket/43484). The last tag was **0.2.0 (April 2023)**; `develop` is 74 commits ahead of `trunk`, almost all Dependabot bumps plus one tooling-alignment commit (#559).

Two independent problems have to be solved, and they are easy to conflate:

**1. The plugin does not work.** It is an architectural skeleton. The models, factories, `Channel_Registry` and schema are well built and unit-tested — everything downstream is hollow:

- `Wpdb_Notification_Repository`'s three methods are all `// TODO: Implement query.`, and the class is never instantiated anywhere.
- `GET /wp-notifications/v1/notifications` returns `rest_ensure_response( array() )`. There are no write routes.
- The dismiss button updates the Redux store only; nothing is persisted, and there is no polling.
- All 24 REST controller PHPUnit tests are `markTestSkipped( 'TODO Implement' )`.

Milestone **0.3.0** (tracking issue #302, 17 open) already defines this release as *"a true to life preview… a functional system"*. 0.3.0 is the MVP release, not a maintenance bump.

**2. The release plumbing has three years of drift.** Four version numbers disagree, `readme.txt` has no `Stable tag` and claims `Tested up to: 6.2` (current WordPress is **7.1**), the changelog's last entry is `0.0.1`, i18n is non-functional end to end, and `test:js` is a stub that exits 0 so CI's JavaScript step is a false green.

**Outcome:** a tagged 0.3.0 GitHub release containing a plugin that actually emits, stores, displays, polls and dismisses notifications.

### Decisions taken

| Decision | Choice |
|---|---|
| Scope | Full MVP per milestone #302 |
| Distribution | GitHub release; WordPress.org noted as follow-up (Phase H) |
| Minimums | **WordPress 6.7+ / PHP 8.1+**, `Tested up to: 7.1` |

### The four silent blockers

Each of these produces a working-*looking* build that does nothing. They are why "just wire up the endpoint" will not work:

1. **`messages.id` has no `AUTO_INCREMENT`** — `$wpdb->insert()` writes `id = 0`, so the second insert in any request dies on a duplicate primary key.
2. **`Framework\Factory` resolves `self::$instance` on the abstract base**, so `Factory\Message::get_instance()` and `Factory\Notification::get_instance()` return *the same object*. Hydrating both model types in one request calls `make()` on the wrong class.
3. **`Model\Message::$meta_keys` contains `expires_at` and `severity`**, which are dedicated *columns*. `encode_meta()` therefore writes `{"date":"…","timezone_type":3,"timezone":"UTC"}` into `meta` and duplicates `severity`. Confirmed by reading `collect_meta()` — note `jsonSerialize()` happens to override both afterwards, which is why nothing has caught this.
4. **`resolvers.fetchUpdates` *returns* `hydrate()` instead of yielding it.** In `@wordpress/data` a resolver's yielded actions are dispatched and its **return value is discarded** — so even against a working endpoint, nothing would ever enter the store.

---

## Phase A — Unblock the workspace

1. `nvm use && npm ci --legacy-peer-deps && composer install`. Confirm `npm run build` emits `build/wp-notifications.{js,css,asset.php}`.
2. **Triage the 39 open PRs.** 34 are Dependabot, most superseded by #559. The five needing a human decision:

   | PR | Verdict |
   |---|---|
   | **#397** Notification polling | **Review against the §B5 design.** Non-draft, `MERGEABLE`, +116/−5, adds `src/poller.ts` on the WordPress **Heartbeat API**. See the transport decision in §B5 — this is a real fork in the road, not a rubber-stamp. |
   | **#552** `jsonSerialize` return types | Merge — prerequisite for the PHP 8.1 baseline. |
   | **#553** Untrack `composer.lock` | Reject — a distributed plugin should keep its lock file. |
   | **#383** Update wp-env + scripts | Close — draft, `CONFLICTING`, July 2023, superseded by #559. |
   | **#63** Extend notification model | Close — draft, `CONFLICTING`, **July 2021**, edits `wp-notify.php` / `class-wp-notify-*.php`, files that no longer exist. |

3. **Re-milestone four unmilestoned issues this release actually closes**: **#42** (Implement the Notification Repository) and **#38** (notification REST endpoint) duplicate Phase B; **#209** (i18n) is Phase D; **#33** (highlight hub when unread) is the hardcoded `hasUnread={ true }`.
4. **Storybook is on 7.6** and `release.yml`'s `build-docs` job depends on it — and runs *after* the release job. Upgrade it or make it non-blocking, or a docs failure lands after the release is already published.

---

## Phase B — MVP implementation

The data model: a **message** (content, one row) fanned out to N users via **queue** rows (per-user delivery state). A notification as the client sees it is `messages JOIN queue` for the current user. `Model\Notification` already matches that join exactly, so the models need no redesign.

**Identity decision:** REST `{id}` is the **message ID scoped to the current user**, with `PRIMARY KEY (user_id, message_id)` on the queue — not a surrogate `queue.id`. This matches `Model\Notification` (which has `message_id` + `user_id` and no `id`) and the TS `Notice` type as they stand, and the PK enforces the one-row-per-message-per-user invariant for free. Consequence to document: an admin reading another user's notification passes `?user_id=`.

### B1. Fix the persistence abstraction before filling it in

**Delete `includes/interface-notification.php`.** It is never loaded by the bootstrap, requires `get_sender()` / `get_recipients()` returning `Sender` and `Recipient_Collection` — types that **exist nowhere** — and is implemented by nothing. Its recipients-as-a-property shape also contradicts the queue design, where recipients are a fan-out concern. Retype the repository against `Model\*`.

**The repository interface is user-agnostic and cannot serve the MVP.** `find_by_id`, `find_by_date_range`, `find_latest` and `add` take **no `user_id` anywhere**, so the interface cannot answer "get the current user's notifications" — the one query this release exists to serve — nor "is this row mine", which is the whole authorization model. Retarget it:

```php
public function find_by_id( int $message_id, int $user_id ): ?array;   // array{notification: Model\Notification, message: Model\Message}
public function find_by_user( int $user_id, array $args = array() ): array;
public function count_by_user( int $user_id, array $args = array() ): int;   // for X-WP-Total
public function add( Model\Message $m, string $channel_name, array $user_ids, string $context = 'adminbar' );  // int|WP_Error
public function dismiss( int $message_id, int $user_id ): bool;
public function mark_displayed( int $message_id, int $user_id ): bool;
public function remove( int $message_id, int $user_id ): bool;
public function purge_orphans(): int;
```

`add()` as it stands (`add( Notification $n ): int`) cannot express fan-out. Keep `find_by_date_range` / `find_latest` and `Abstract_Notification_Repository` (adding `$user_id`) — the delegation is genuinely reusable. `find_by_user( $user_id, $args )` becomes the workhorse.

`$args`: `status`, `channel_name`, `context`, `after` (powers polling), `before`, `include_expired` (default false), `order`, `orderby`, `per_page`, `offset`.

Status → SQL, in one private method so REST and CLI agree. Note `NEW` is a **client-side transient** ("arrived this session") and server-side aliases `UNDISPLAYED`; write that into the schema docs so nobody adds a `new` column later.

| `Status_Interface` | predicate |
|---|---|
| `UNDISPLAYED` / `NEW` | `q.displayed_at IS NULL AND q.dismissed_at IS NULL` |
| `DISPLAYED` | `q.displayed_at IS NOT NULL AND q.dismissed_at IS NULL` |
| `DISMISSED` | `q.dismissed_at IS NOT NULL` |

Add a **second repository** for subscriptions (`interface-subscription-repository.php`, `class-wpdb-subscription-repository.php`) rather than overloading this one — it lets the subscription controller (#180/#377) proceed in parallel.

**Instantiation.** The repository is currently never constructed. Add a filtered accessor in `includes/load.php` and inject into controller constructors as a *required* arg, so nothing can silently build an unwired controller (and tests can inject a fake):

```php
$repository = apply_filters( 'wp_notifications_repository', new Persistence\Wpdb_Notification_Repository( $wpdb ) );
```

**Query notes that matter.** Interpolate only table names and a **whitelisted** `ORDER BY` column/direction; everything else through `$wpdb->prepare()`, including variable-length `IN` lists via generated placeholders. Include a `q.message_id` tiebreaker in `ORDER BY` — without it, pagination over rows sharing a `created_at` (which the seeder will absolutely produce) silently repeats and drops rows. Wrap `add()` in a transaction: insert the message, then one multi-row queue insert.

**Restore the downgraded sniffs before writing any SQL.** `phpcs.xml.dist:36-41` turns `WordPress.DB.PreparedSQL.NotPrepared` and `.InterpolatedNotPrepared` into warnings. The stated rationale ("so we can error check the entire codebase") no longer holds — there is essentially no SQL today, and after this release all of it lives in two files. Restore to errors plus narrowly-scoped `// phpcs:ignore … -- reason` at the ~6 legitimate interpolation sites; a blanket warning hides the seventh. Add `DirectDatabaseQuery` exclusions for `includes/persistence/*`, the activator, uninstaller and CLI. (`coding-standards.yml` runs `enable_warnings: true`, so CI behaviour is unchanged — only intent.)

### B2. Schema v2 and a migration path that can actually run

| Table | Change | Why |
|---|---|---|
| `messages.id` | → `BIGINT(20) UNSIGNED AUTO_INCREMENT` | Blocker #1 |
| `messages.channel_title` | **drop** | #372 — also remove from `$meta_keys`, or it sneaks back into the blob and #372 is half-done |
| `messages.message` | `TINYTEXT` → `TEXT` | TINYTEXT is 255 **bytes** ≈ 85 CJK/emoji characters |
| `queue` | add `context`, `created_at` | Both already exist on `Model\Notification` and in the REST schema with nowhere to store them |
| `queue` | `PRIMARY KEY (user_id, message_id)` | No PK today; `user_id` leftmost serves the dominant `WHERE q.user_id = %d` |
| `subscriptions` | `PRIMARY KEY (user_id, channel_name)`, add `created_at` | No PK; `created_at` is exposed by the model and REST schema but has no column |
| `channel_name` | `VARCHAR(50)` → `VARCHAR(64)` both tables | Join keys must match; docs are already inconsistent (32/64/65) |

Two `dbDelta` traps to avoid: **drop `DEFAULT CURRENT_TIMESTAMP()`** (the parenthesised form makes `dbDelta` see a diff and re-ALTER on every run, and MySQL's `CURRENT_TIMESTAMP` is server-local while this plugin is UTC throughout — always write `gmdate()` from PHP), and **keep `meta` as `TEXT`, not MySQL `JSON`** (MariaDB aliases `JSON` to `LONGTEXT`, so `dbDelta` sees a permanent mismatch). The docs' `meta: JSON` means "JSON-encoded"; say so in a comment.

**The migration gate is broken.** `Activator::create_tables()` reads:

```php
if ( ! $db_version ) {   // only ever true on a fresh install
```

Any site that activated 0.2.0 has `wp_notifications_db_version = '1'` and stays on v1 forever. Replace with a `version_compare` dispatch, bump `WP_FEATURE_NOTIFICATION_DB_VERSION` to `'2'`, and hook it on **`plugins_loaded` (priority 5), not just the activation hook** — upgrading a plugin does not re-fire activation. On multisite this is also *better* than the existing `switch_to_blog()` loop, which times out on large networks and misses blogs created later: options are per-blog, so each site upgrades its own tables on first request. Add a `wp_cache_add()` guard so two concurrent requests don't both run `dbDelta`.

**For v1 → v2 specifically, drop and recreate.** `dbDelta` cannot drop a column, add a `PRIMARY KEY` to an existing table, or add `AUTO_INCREMENT` — that is all four of the changes v2 needs. The alternative is ~40 lines of hand-rolled `SHOW COLUMNS`/`SHOW INDEX` guards and raw `ALTER TABLE`, exercised exactly once, on a pre-1.0 plugin with no .org release, to preserve data that is ephemeral by design. Put it behind `apply_filters( 'wp_notifications_preserve_v1_data', false )` and document it in the `readme.txt` upgrade notice. From v2 onward, real incremental migrations.

Also delete the always-false `in_array( $table, $wpdb->get_results( 'SHOW TABLES' ), true )` guard (it compares strings to `stdClass` rows, which is why nobody noticed it never worked) — `dbDelta` is idempotent. Drop the `_v1` method suffixes. `tests/phpunit/tests/test-activator.php` calls `create_tables_v1()` directly and must be updated in the same change.

### B3. The PHP emit API (#303)

**Do not honour `wp_notify( $recipients, $message )`** as documented in `storybook/stories/docs/internal-api.md`. It predates channels, which are now the organising principle — the registry, the subscriptions table and the messages table are all keyed on `channel_name`, and a signature with no channel cannot express the model. It is also an unprefixed global adjacent to core's existing `wp_notify_postauthor()`. Update the doc to describe the real API; do not leave both stories in the repo.

New `includes/notify.php`:

```php
function notify( string $channel_name, array $args = array() )   // int|WP_Error
```

Flow: resolve the channel from the registry (`WP_Error` if unregistered — this is what makes the `channel_name` column trustworthy and gives the REST push a validatable input) → require non-empty `message` → build via `Factory\Message` → resolve recipients → `$repository->add()` → `do_action( 'wp_notifications_notified', … )`.

Per #303, add `Model\Channel::emit( array $args )` as a **one-line delegate** to `notify()`. Stated trade-off: this puts a persistence-adjacent method on a value object, which is exactly why the real logic stays in `notify()` and the model gains no `wpdb` awareness and nothing to test beyond delegation. It buys the `register_channel(…)->emit(…)` DX the issue asked for at near-zero cost.

**Recipients.** Explicit `recipients` (user IDs and/or role slugs) if given; otherwise channel subscribers, excluding snoozed rows. Per #302 — *"All users will receive all notifications in the MVP"* — an empty subscription set falls back to **all users with `read_notifications`**. So in 0.3.0 the subscriptions table is opt-*out* for snoozing but not yet opt-*in* for delivery. Write that rule into `database-schema.md`, because inverting it later (empty = nobody) is a breaking behaviour change. Apply a `wp_notifications_recipients` filter last.

**No capability check inside `notify()`** — an in-process plugin already has full DB access, so a check here is theatre that only inconveniences legitimate callers. Authorization is a REST-boundary concern (§B4). State this in the docblock so nobody "fixes" it.

### B4. REST surface and the capability model

| Route | Methods | Issues |
|---|---|---|
| `/notifications` | `GET`, `POST` | #307, #381, #177, #375 |
| `/notifications/(?P<id>[\d]+)` | `GET`, `EDITABLE`, `DELETE` | #362, #176, #376, #363 |
| `/subscriptions` | `GET`, `POST` | #180, #377 |
| `/subscriptions/(?P<namespace>[a-z0-9-]+)/(?P<name>[a-z0-9-]+)` | `GET`, `PUT/PATCH`, `DELETE` | #180, #377 |
| `/channels` | `GET` | — |

`WP_REST_Server::EDITABLE` is `'POST, PUT, PATCH'`, so **one registration satisfies both #176** (which asks for `POST /notifications/{id}` with `{"status":"dismissed"}`) **and #376** (which asks for `PUT`). Worth noting on both issues. Register `'allow_batch' => array( 'v1' => true )` on the item route so the client can batch `mark displayed` across a screenful via core's `/batch/v1` instead of N requests.

**Capabilities (#382).** There are none today — `is_user_logged_in()` is the whole story. Use `map_meta_cap` rather than persisting caps to role objects (which would need its own migration and a dirty uninstall):

- **read** — `read_notifications` / `read_notification` → `read`, always scoped to `get_current_user_id()`. Reading another user's requires `manage_options`.
- **dismiss / update** — `edit_notification` → `read` when the row is yours. Dismissal must not require more than being the recipient.
- **push via REST** — `create_notifications` → `manage_options`. A REST push writes into *other people's* queues from outside the site's PHP process; unlike a server-side `notify()` call the caller has no other route to the database.

**Document the server/REST asymmetry** in `internal-api.md`: same fan-out, two threat models. Also fix the status codes — every controller currently returns **401 for both** cases; it should be 401 logged out, `rest_authorization_required_code()` (403) logged in but unauthorised.

Other required fixes:

- **Restore `Subscription_Controller::get_items_permissions_check()`** — it currently `return true`s for anonymous requests with the real check commented out. This is the one outright hole in the plugin today, mitigated only by `get_items()` returning `[]`.
- **Un-clobber the channel controller's `context` param** — `get_collection_params()` sets `context.default = 'view'` then overwrites the whole `context` key with a display filter defaulting to `'all'`. `context` is reserved by `WP_REST_Controller` for `view|edit|embed`, so `filter_response_by_context()` sees nonsense, `context=edit` is unreachable, and `'all'` isn't in the enum so core rejects the default. Rename the filter to `display_context` (and update `test_registered_query_params` in the same PR).
- **Implement `prepare_item_for_response()` in all three controllers.** Without it `_fields`, `context=embed`, `_links` and `register_rest_field` are all silently dead. `Channel_Controller::get_items()` currently returns registry objects straight through. `get_items()` must also send `X-WP-Total` / `X-WP-TotalPages` from `count_by_user()` — the "N new notifications" header needs them.
- **Invert the `readonly` flags.** `get_endpoint_args_for_item_schema( CREATABLE )` **excludes every `readonly` property**, so `POST /notifications` currently accepts no fields at all. `status` in particular must become writable — it is the entire mechanism of #176 — and `dismissed_at` must become readonly, which is the opposite of today. Keep `channel_title` in the *response* (derived from the registry) so `test_get_item_schema`'s `assertCount( 18, … )` still passes.
- **Fix wrong types**: `icon` is `integer` in both schemas but is a dashicon slug string everywhere else; `channel` is `array of integer` but channels have **no integer IDs** — they are namespaced strings. Rename to `channel_name` with `'pattern' => '^[a-z0-9-]+/[a-z0-9-]+$'`. (This resolves #177's speculation about a `channel_id`: there isn't one.)
- **Add the missing params**: `after` (`format: date-time`, the polling delta), `user_id` (default 0 = current user, gated on `manage_options`), `include_expired`.
- **Add `sanitize_callback` / `validate_callback`** — there are currently **zero** in the plugin. `accept_link` needs `'format' => 'uri'` (see Phase C).

### B5. Client work

**The store must be fixed before the endpoint returns data, or the UI throws.** This is an ordering constraint, not cleanup:

1. **`registerContext` is a selector that mutates state** (`selectors.ts:41`). Move it to an action + reducer case, and change `wp-notifications.ts` from `select(…)` to `dispatch(…)`. Today it mutates in place without notifying subscribers and defeats `useSelect` memoisation.
2. **`HYDRATE`/`ADD` spread a possibly-missing key** — `[ ...updated[ context ], notification ]` **throws a `TypeError`** for any context not pre-registered. Invisible only because the endpoint returns `[]`.
3. **`findContext` has no return on no-match** (`utils.ts`), so `DELETE`/`UPDATE` index `state[ undefined ]` — exactly what a double-dismiss will do.
4. **`CLEAR` mutates** before spreading (`reducer.ts:46`).
5. **`controls.FETCH` maps `notice.date`**, a field the REST schema never emits (it exposes `created_at`). Add a typed `src/store/normalize.ts` boundary: `created_at → date`, `is_dismissible → dismissible`, `channel_title → source`, `icon → { dashicons }`.
6. **`resolvers.fetchUpdates` returns instead of yielding `hydrate()`** — blocker #4 above.
7. **`selectors.fetchUpdates` returns the whole state purely to trigger a resolver**, which is why `wp-notifications.ts` calls a selector for a side effect.

**Recommendation:** `@wordpress/data` is at 10.46, so thunks are stable and default-on. Convert to thunks, **delete `controls.ts` and `resolvers.ts`**, and add one real resolver on `getNotices( context )`. The generator+control indirection buys nothing here — there is exactly one control wrapping one `apiFetch` — and it is precisely what made blocker #4 invisible.

**Polling (#306) — decide the transport.** PR #397 already implements this on the **Heartbeat API**, which gives suspend-on-idle, a server-controlled interval and coalescing with other admin features for free. The counter-argument: Heartbeat routes through `admin-ajax.php`, bypassing the REST layer, permission callbacks and schema this entire milestone is building. Recommendation: **`setTimeout` + REST for 0.3.0**, revisiting Heartbeat as a transport swap in 0.4.0 once the REST contract is stable — but this is a genuine judgement call and #397 is already written, so put it to the team rather than closing the PR unilaterally. Either way: 60 s default (core's Heartbeat is 15 s in the editor, 60 s elsewhere; a notification centre is not more urgent than autosave-lock), self-scheduling rather than `setInterval` so slow responses can't stack, paused on `visibilitychange`, exponential backoff to 5 min on error, and `after` making each poll a delta query.

**Persisted dismissal (#305):** `dismissNotice` thunk → optimistic `UPDATE` with `isBusy` → `PUT /notifications/{id}` → remove on success, **roll back on failure**. `isBusy` gives #305's bonus "disable the button in-flight" essentially free. Reuse the same route for `displayed_at`: on mount, batch `markDisplayed` for every rendered notice with `displayed_at === null` through `/batch/v1` — this is what makes the unread dot and the "N new" header meaningful across page loads.

**Localized data.** `window.wp_notifications_data` is read at module-evaluation time in `src/store/constants.ts` and typed in `types/global.d.ts`, but **nothing in PHP ever defines it**, so the footer's settings link goes to `''`. Use `wp_add_inline_script( …, 'before' )`, **not `wp_localize_script`** — the latter coerces every value to a string, so `pollInterval` would arrive as `"60000"`. Position `'before'` matters because the global is read before any React render. Note there is no settings page at all, so removing the footer link may be the honest MVP answer.

Also: `hasUnread={ true }` is hardcoded (`notification-hub/index.tsx:84`, also issue #33), and the `dashboard` context is registered in `store/constants.ts` but **no PHP ever outputs a `#wp-notifications-dashboard` element**, so that root never mounts — emit the container or drop the context.

### B6. WP-CLI seeder (#200)

`includes/cli/class-seed-command.php`, loaded from `includes/load.php` behind `defined( 'WP_CLI' ) && WP_CLI`:

```
wp notifications seed [--count=<n>] [--user=<id|login|email>] [--all-users]
                      [--channel=<name>] [--status=<…|mixed>] [--age=<days>] [--seed=<int>] [--porcelain]
wp notifications clear [--user=<…>] [--yes]
```

Implement it as a thin loop over `WP\Notifications\notify()` so the seeder doubles as an integration smoke test of the real write path. `--age` spreads `created_at` backwards so `splitByDate()`'s week boundary is actually exercised; `--seed` calls `mt_srand()` for reproducible screenshots.

**Do not use `fakerphp/faker`,** despite #200's suggestion. `package.json`'s `files` field governs `wp-scripts plugin-zip`, so `vendor/` is never in the zip and a runtime Faker dependency would fatal for anyone installing the release. Ship a small hard-coded corpus inside the command file — which also makes output deterministic. **Ship the command in the zip**: it is ~8 KB, only loads under `WP_CLI`, and it is how a reviewer will populate the demo from a GitHub release.

### B7. Tests

- **Un-skip the 24 REST tests.** The eight stubbed names per class exist because `WP_Test_REST_Controller_Testcase` declares them abstract. Fill them, and adopt the rule: no controller PR merges while a `markTestSkipped` remains in its class. Add explicitly: 401 logged out, 403 cross-user, 404 nonexistent, 403-as-subscriber on `POST`. `test_context_param` is the test that would have caught the channel controller's `context` clobber.
- **New: `test-wpdb-notification-repository.php`** (extends the existing `DB_TestCase`, which already does a correctly-prepared `table_exists()`) — fan-out row counts, recipient dedupe via the PK, cross-user isolation, **an injection attempt in `orderby` falling back to `created_at`**, pagination determinism across identical `created_at`, expiry exclusion, idempotent `dismiss()`.
- **New: `test-notify.php`, `test-capabilities.php`, `test-serde.php`** (the UTC round-trip — every date assertion elsewhere depends on it).
- **Migration tests** in `test-activator.php`: seed `wp_notifications_db_version = '1'`, run the upgrade, assert via `SHOW COLUMNS` that `channel_title` is gone and `id` has `auto_increment`, and via `SHOW INDEX` that both PKs exist. This is the path most likely to break on real sites.
- **JS — store only.** Given §B5 lists seven store bugs, the high-value move is reducer/normalizer/thunk tests and essentially nothing else for 0.3.0: each is a regression test for a specific bug above (`HYDRATE` into an unregistered context; `DELETE` of an unknown id; `created_at → date`; `dismissNotice` rollback on rejection). Split `test:js` into `test:unit:js` / `test:e2e` so the unit job can drop `wp-env start` from CI. Say in `CONTRIBUTING.md` that broad component and e2e coverage is out of scope, rather than leaving `test:js` lying about its status.

### B8. Sequencing

**Batch 0 — foundations, all parallel, small PRs.**
`0a` minimums bump (headers, `composer.json`, CI matrix, version constants) · `0b` delete `interface-notification.php`, retype the interface · **`0c` `Framework\Factory` per-class instances** · **`0d` `Serde` UTC + trim `$meta_keys` + drop `validate_message()`** (updates `test-model-message.php`, which pins the exact JSON) · `0e` capabilities · **`0f` store bug fixes + JS harness — pure client, can start day one.**

**Batch 1 — schema v2 + migration.** Hard blocker for everything downstream.
**Batch 2 — repositories + wiring + phpcs sniffs restored.** Needs 0c, 0d, 1.
**Batch 3 — emit API (#303).** Needs 2. First point the system can be exercised by hand.
**Batch 4 — REST.** `4a` channel controller fixes first (smallest PR that establishes the `prepare_item_for_response` + permission pattern the others copy) → `4b` notification CRUD (split read from write; the client only needs read to start) ∥ `4c` subscriptions (independent — hand to a different contributor).
**Batch 5 — CLI seeder.** Needs only 3; pull it forward, since it is what makes 4b and Batch 6 manually verifiable.
**Batch 6 — client.** `6a` normalizer + thunks (can start against a mocked `apiFetch` as soon as 4b's *schema* is agreed, not its implementation) → `6b` polling ∥ `6c` dismissal → `6d` batched `markDisplayed`.

**Critical path:** 0c/0d → 1 → 2 → 3 → 4b → 6b/6c. Everything else hangs off it.

---

## Phase C — Correctness and security fixes

Parallel with Phase B.

**Release-blocking**

| Issue | Location |
|---|---|
| `include build/wp-notifications.asset.php` is **unguarded** — fatal on every admin page for an unbuilt checkout | `includes/load.php:97` |
| Assets enqueue on the front end for every logged-in user regardless of whether the admin bar shows | `includes/load.php:103` |
| `Subscription_Controller` permission check commented out | `class-subscription-controller.php:64-73` |
| Migration gate can never fire (§B2) | `class-activator.php:117-126` |
| Shared factory singleton (§B blockers) | `class-factory.php:22,40-46` |

**Should fix**

- **Output escaping** — `includes/load.php:56-68` builds admin-bar markup with `sprintf()` and no `esc_html()`. Low severity (malicious translation only) but a straight WPCS `EscapeOutput` violation. Use `esc_html__()`.
- **`purify()` defeats itself** — `src/utils/sanitization.ts` wraps `escapeHTML()` in `dangerouslySetInnerHTML`, so markup can never render. In `notice/icon/index.tsx:48` this makes the SVG icon path **functionally broken** (the SVG renders as literal text). Decide explicitly: render `{ message }` as text and drop `dangerouslySetInnerHTML`, or add DOMPurify with a real allowlist. Do **not** simply delete `purify` — that turns it into a stored-XSS sink fed by the REST endpoint.
- **`window.location.href = acceptLink`** with no scheme validation (`notice/actions/index.tsx:38,61`). Once the endpoint returns real data a `javascript:` URL executes.
- **Timezone bug** — `Serde::maybe_deserialize_mysql_date()` calls `DateTime::createFromFormat()` with no timezone, so it uses the PHP default while WordPress stores UTC. Every timestamp the client receives will be off by the server's offset.
- **Wrong namespace import** — `includes/channels.php:10` has `use WP\Notification\Model;` (singular); the real namespace is `WP\Notifications\Model`. Harmless only because the file never uses `Model\` in executable code.
- `Channel_Registry::register()` reads `$parsed['title']` with no `array_key_exists` guard (line 110) while every other arg is guarded.
- Dead/broken: `Drawer`'s Escape shortcut does `() => blur` — returns the handler instead of calling it, so the shortcut is a no-op; `NotificationHub`'s `registerShortcut` `useEffect` has no dependency array so it re-registers every render; `Uninstaller::full_reset()` and `Runtime_Exception` are unreferenced; `NoticesArea`'s `notifications` prop is immediately overwritten by `useSelect`.

---

## Phase D — Internationalisation

Non-functional end to end. All required.

1. **PHP text domains** — ~45 `__()` calls in `includes/restapi/*` and `class-channel-registry.php` omit `'wp-feature-notifications'` and fall back to `default`. Also fix the broken string `__( 'Sorry, you must be logged check notifications.' )`.
2. **JS text domains** — all nine `__()` calls in `src/` omit the domain. Two strings in `notice-area/section-header/index.tsx:31,42` are hardcoded English and not wrapped at all.
3. **`wp_set_script_translations( 'wp_notifications', 'wp-feature-notifications' )`** — absent entirely.
4. **Add an i18n build step** — no `make-pot` script exists and `languages/` holds only `.gitkeep`. Add `make-pot` + `make-json` and generate the `.pot`.
5. **Add `languages/` to `package.json` `files`** — currently excluded from the zip, so no translation would ever ship.

Enable the `WordPress.WP.I18n` sniff (WordPress-Core does not include it) so this cannot regress.

---

## Phase E — Version, metadata and changelog

**Four locations disagree.** Note the *constant* — not the header — cache-busts the enqueued assets, so leaving it at `0.0.1` means browsers keep stale JS across releases.

| Location | Current | Target |
|---|---|---|
| Plugin header `Version:` | `0.1.0` | `0.3.0` |
| `WP_FEATURE_NOTIFICATION_PLUGIN_VERSION` | `0.0.1` | `0.3.0` |
| `package.json` `version` | `0.1.0` | `0.3.0` |
| `readme.txt` `Stable tag` | *missing* | `0.3.0` |
| latest git tag | `0.2.0` | `0.3.0` |

- **`Requires at least`** is `5.0` in the plugin header but `6.2` in `readme.txt` and `README.md`. Set all three to `6.7`.
- **`Requires PHP`** → `8.1` everywhere; add `"php": ">=8.1"` to `composer.json` `require` (there is currently **no `php` constraint at all**) and set `config.platform.php` to `8.1`.
- **Add `Tested up to: 7.1`** to `readme.txt` and `README.md`.
- **Reconcile contributor lists** — `README.md` and `readme.txt` disagree.
- **Write the changelog.** The only entry is `= 0.0.1 = Initial Release`; 0.1.0 and 0.2.0 were never documented. Add `CHANGELOG.md` as canonical, mirror a condensed version into `readme.txt`, backfill both prior tags, and include an **upgrade notice** covering the v1 table rebuild.
- `readme.txt` `Tags:` is just `feature-notifications`, which nobody searches for.

---

## Phase F — CI, build and release workflow

**`release.yml` bugs**

1. **`name: github.ref`** is a literal string, not `${{ github.ref }}` — every release is titled "github.ref".
2. **`draft: true`** — releases never publish without manual intervention. Confirm this is intended.
3. **`build-docs` runs after `release`** on Storybook 7.6 — a failure lands after the release is already out.
4. Trigger is `push` on `'*.*.*'` with `target_commitish: trunk`, and `README.md` says releases come only from `trunk` — but `develop` is 74 commits ahead. **The process requires a `develop` → `trunk` merge first**, which nothing enforces and no document spells out. Add it to `CONTRIBUTING.md`, which currently says nothing about releases.

**`.distignore` is dead code.** `wp-scripts plugin-zip` prefers `package.json`'s `files` when present and only falls back to `.distignore`. The two disagree, and `.distignore` still lists files that no longer exist (`.eslintrc.js`, `.prettierrc.js`, `.stylelintrc`). Keep `files` (it is what actually runs), delete `.distignore`, and make sure `languages/` is included.

**Test matrix** — PHP `8.1–8.4`, WP `6.7` + `latest`, and **`mysql:5.6` → `mysql:8.0`** (5.6 is a decade EOL and won't exercise the collation and index behaviour real sites see). Point `test:js` at a real jest run. Add `lint:css` to `coding-standards.yml`. Bump `actions/checkout@v3` → `v4` across all three workflows. The e2e test uses `page.$x()`, removed in Puppeteer 22, so it cannot pass as written — rewrite or delete it.

---

## Phase G — Release execution

1. Milestone #302 issues closed or explicitly deferred to 0.4.0.
2. `npm run lint:js && npm run lint:css && composer run lint && composer run test && npm run test:js` all green.
3. Versions bumped in all four locations; `CHANGELOG.md` and `readme.txt` written.
4. `.pot` regenerated.
5. Manual smoke test on a clean `wp-env` install (below).
6. Merge `develop` → `trunk` via PR.
7. Tag `0.3.0` on `trunk`; the Release workflow builds, zips and drafts.
8. Publish the draft, close the milestone, open 0.4.0 with the deferred issues.

---

## Phase H — Follow-up (not in 0.3.0)

WordPress.org submission would additionally require: a `Stable tag` matching the SVN tag; `.wordpress-org/` assets (banner 1544×500, icon 256×256, at least one screenshot — `readme.txt`'s `== Screenshots ==` is currently empty); a real `== Description ==` and FAQ; a `10up/action-wordpress-plugin-deploy` step with SVN credentials in secrets; and a plugin-review submission that will scrutinise exactly the escaping and capability gaps in Phase C.

---

## Verification

```bash
npm ci --legacy-peer-deps && composer install
npm run build                 # must emit build/wp-notifications.{js,css,asset.php}
npm run lint:js && npm run lint:css
composer run lint             # phpcs, PreparedSQL sniffs restored to errors
composer run test             # phpunit — REST tests must no longer be skipped
npm run test:js               # must actually run jest, not echo
npm run plugin-zip            # inspect: build/, includes/, languages/, readme.txt, main file
```

**Manual smoke test** (`wp-env start`, fresh DB)

1. Activate; confirm three tables + both options created, no PHP notice.
2. **Upgrade path**: install 0.2.0, activate, then upgrade in place — confirm `maybe_upgrade()` fires on `plugins_loaded` and the v2 schema lands. This is the highest-risk path and is not covered by a fresh install.
3. `wp notifications seed --count=25 --age=14`.
4. Load `/wp-admin/`: bell appears, unread dot reflects a real count (not hardcoded), drawer opens.
5. `curl` the REST routes: anonymous → 401; subscriber reading another user's → 403; subscriber `POST` → 403.
6. Dismiss a notification, hard-reload, confirm it stays dismissed.
7. Emit server-side via `notify()`; confirm the client picks it up by polling without a reload.
8. Switch site language; confirm both PHP and JS strings translate.
9. Install the built zip on a **separate** clean site — this is what catches a missing `build/` or `languages/`.
