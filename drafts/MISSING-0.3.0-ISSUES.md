# Missing 0.3.0 issues

Issues needed to cover `RELEASE-PLAN-0.3.0.md` that don't exist yet. Section refs point to the plan.

## Milestone housekeeping (no new issues)

- Add to 0.3.0: #42, #38, #209, #33, #607, PR #397
- Consolidate duplicates: #307/#381, #176/#376, #177/#375, #180/#377
- Update #302 checklist to reflect the full milestone

## New issues

### Foundations (Batch 0)

1. **Bump minimums to WordPress 6.7 / PHP 8.1** — plugin header, `readme.txt`, `README.md`, `composer.json` (`php` constraint + `config.platform.php`), CI matrix. (§E, §F)
2. **Delete `interface-notification.php` and retarget the repository interface to be user-scoped** — new `find_by_id/find_by_user/count_by_user/add/dismiss/mark_displayed/remove/purge_orphans` signatures. (§B1)
3. **Fix `Framework\Factory` sharing one singleton across subclasses** — use per-class instances. (Blocker #2)
4. **Fix Serde UTC deserialisation and remove column fields from `Message::$meta_keys`** — `expires_at`/`severity`/`channel_title` out of `meta`; update `test-model-message.php`. (Blocker #3, §C)
5. **Add capabilities via `map_meta_cap`** — `read_notifications`, `edit_notification`, `create_notifications`. (§B4)
6. **Fix client store bugs** — `registerContext` mutating selector, `HYDRATE`/`ADD` on unregistered context, `findContext` no-match, `CLEAR` mutation, `fetchUpdates` return-vs-yield; convert to thunks, add `normalize.ts`. (Blocker #4, §B5)

### Schema and persistence

7. **Schema v2** — `messages.id` `AUTO_INCREMENT`, `message` → `TEXT`, queue `context`/`created_at`, PKs on queue and subscriptions, `channel_name` → `VARCHAR(64)`, drop `DEFAULT CURRENT_TIMESTAMP()`. (Blocker #1, §B2)
8. **Fix migration gate and add upgrade routine** — `version_compare` dispatch on `plugins_loaded`, v1 → v2 drop-and-recreate behind `wp_notifications_preserve_v1_data` filter, remove broken `SHOW TABLES` guard, update `test-activator.php`. (§B2)
9. **Add subscription repository** — `interface-subscription-repository.php`, `class-wpdb-subscription-repository.php`. (§B1)
10. **Instantiate the repository and inject it into controllers** — filtered via `wp_notifications_repository`. (§B1)
11. **Restore `PreparedSQL` sniffs to errors** — scoped `phpcs:ignore` at legitimate sites; `DirectDatabaseQuery` exclusions for persistence/activator/uninstaller/CLI. (§B1)

### REST

12. **Fix `Subscription_Controller` permission check** — currently returns `true` for anonymous users. (§B4, §C)
13. **Channel controller fixes** — rename clobbered `context` param to `display_context`, implement `prepare_item_for_response()`, add `GET /channels`. (§B4)
14. **REST schema and response cleanup across controllers** — `prepare_item_for_response()`, `X-WP-Total` headers, invert `readonly` flags (`status` writable, `dismissed_at` readonly), fix `icon`/`channel` types, add `after`/`user_id`/`include_expired`, add sanitize/validate callbacks, 401 vs 403, `allow_batch`. (§B4)

### Client

15. **Output `wp_notifications_data` from PHP** — `wp_add_inline_script( …, 'before' )`; fix or remove the settings link. (§B5)
16. **Batch `markDisplayed` on render** — via `/batch/v1`. (§B5)
17. **Emit or drop the `dashboard` context root.** (§B5)

### Tests

18. **Un-skip the 24 REST controller tests** — plus 401/403/404 cases. (§B7)
19. **Add PHP tests** — repository, `notify()`, capabilities, serde, v1 → v2 migration. (§B7)
20. **Real JS unit tests for the store** — split `test:js` into `test:unit:js` / `test:e2e`; fix or delete the `page.$x()` e2e test. (§B7, §F)

### Correctness and security (Phase C)

21. **Guard the `asset.php` include and stop enqueuing assets on the front end.** (`includes/load.php`)
22. **Fix `purify()` / sanitisation of notification content** — decide text vs DOMPurify; fixes broken SVG icons. (`src/utils/sanitization.ts`)
23. **Validate `acceptLink` scheme before navigation.** (`notice/actions/index.tsx`)
24. **Minor fixes** — admin-bar escaping, `WP\Notification\Model` namespace typo, `Channel_Registry` `title` guard, `Drawer` Escape no-op, `registerShortcut` deps, `NoticesArea` prop, dead `full_reset()`/`Runtime_Exception`. (§C)

### Release (Phases A, E, F)

25. **Version and readme metadata** — sync to `0.3.0` in four places, `Stable tag`, `Tested up to: 7.1`, reconcile contributors, better `Tags`. (§E)
26. **Add `CHANGELOG.md`** — backfill 0.1.0/0.2.0, mirror into `readme.txt`, upgrade notice for the v1 table rebuild. (§E)
27. **Fix `release.yml`** — `${{ github.ref }}` title, confirm `draft: true`, make `build-docs` non-blocking or run it first. (§F)
28. **Upgrade or decouple Storybook 7.6.** (§A, §F)
29. **CI updates** — MySQL 8.0, PHP 8.1–8.4 × WP 6.7/latest, `lint:css` in CI, `actions/checkout@v4`. (§F)
30. **Delete `.distignore`; add `languages/` to `package.json` `files`.** (§D, §F)
31. **Document the release process in `CONTRIBUTING.md`** — `develop` → `trunk` merge before tagging. (§F)
