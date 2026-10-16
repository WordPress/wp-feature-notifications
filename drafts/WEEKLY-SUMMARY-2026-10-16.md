# Weekly summary: 2026-10-16

_Covers 2026-10-09 to 2026-10-16 in [WordPress/wp-feature-notifications](https://github.com/WordPress/wp-feature-notifications)._

## Highlights

- **0.3.0 MVP work is underway.** Eight new issues break the release plan into concrete tasks ([#612](https://github.com/WordPress/wp-feature-notifications/issues/612)–[#619](https://github.com/WordPress/wp-feature-notifications/issues/619)), and PRs are already open for three of them.
- **Dependabot backlog cut down.** 5 dependency PRs merged and 13 stale ones closed by Dependabot, tracked in [#607](https://github.com/WordPress/wp-feature-notifications/issues/607).
- **Tooling fixes in review.** Broken and no-op npm scripts were reported and fixed in the same week ([#631](https://github.com/WordPress/wp-feature-notifications/pull/631), [#632](https://github.com/WordPress/wp-feature-notifications/pull/632)).
- **REST API security.** An auth enforcement PR ([#606](https://github.com/WordPress/wp-feature-notifications/pull/606)) and a notification state endpoint ([#611](https://github.com/WordPress/wp-feature-notifications/pull/611)) are open for review.

## Merged

**Dependency updates:** 5 Dependabot bumps merged: symfony/process ([#582](https://github.com/WordPress/wp-feature-notifications/pull/582)), http-proxy-middleware ([#572](https://github.com/WordPress/wp-feature-notifications/pull/572)), simple-git ([#557](https://github.com/WordPress/wp-feature-notifications/pull/557)), node-forge ([#554](https://github.com/WordPress/wp-feature-notifications/pull/554)), re-resizable ([#547](https://github.com/WordPress/wp-feature-notifications/pull/547)).

## New pull requests

- [#626](https://github.com/WordPress/wp-feature-notifications/pull/626) Make notification repository user-scoped, fixes [#612](https://github.com/WordPress/wp-feature-notifications/issues/612) (@elphizu)
- [#624](https://github.com/WordPress/wp-feature-notifications/pull/624) Framework: Store one Factory instance per subclass, fixes [#613](https://github.com/WordPress/wp-feature-notifications/issues/613) (@huzaifaalmesbah)
- [#627](https://github.com/WordPress/wp-feature-notifications/pull/627) Model: Handle dates in UTC and keep column fields out of message meta, fixes [#614](https://github.com/WordPress/wp-feature-notifications/issues/614) (@huzaifaalmesbah)
- [#606](https://github.com/WordPress/wp-feature-notifications/pull/606) REST API: Enforce authentication checks on endpoints and add unit tests, fixes [#382](https://github.com/WordPress/wp-feature-notifications/issues/382) (@AshishJii)
- [#611](https://github.com/WordPress/wp-feature-notifications/pull/611) Add endpoint to update notification state by ID, fixes [#376](https://github.com/WordPress/wp-feature-notifications/issues/376) (@AshishJii)
- [#632](https://github.com/WordPress/wp-feature-notifications/pull/632) Tooling: Fix the test:php, php-install, lint:css and docGen npm scripts, fixes [#629](https://github.com/WordPress/wp-feature-notifications/issues/629) (@huzaifaalmesbah)
- [#631](https://github.com/WordPress/wp-feature-notifications/pull/631) Tooling: Make lint:pkg-json and lint:md:docs lint real files, fixes [#630](https://github.com/WordPress/wp-feature-notifications/issues/630) (@huzaifaalmesbah)
- [#609](https://github.com/WordPress/wp-feature-notifications/pull/609) Update npm lockfile to resolve Dependabot PRs, part of [#607](https://github.com/WordPress/wp-feature-notifications/issues/607) (@AshishJii)
- [#628](https://github.com/WordPress/wp-feature-notifications/pull/628) GitHub: Add a Props Bot workflow (@huzaifaalmesbah)
- [#625](https://github.com/WordPress/wp-feature-notifications/pull/625) GitHub: Update the pull request template (@huzaifaalmesbah)

**Dependency updates:** 5 new Dependabot PRs: compression ([#623](https://github.com/WordPress/wp-feature-notifications/pull/623)), source-map-js ([#622](https://github.com/WordPress/wp-feature-notifications/pull/622)), handlebars ([#621](https://github.com/WordPress/wp-feature-notifications/pull/621)), proxy-addr ([#620](https://github.com/WordPress/wp-feature-notifications/pull/620)), joi ([#610](https://github.com/WordPress/wp-feature-notifications/pull/610)).

## Issues

### Opened

0.3.0 tasks from the release plan (@jonathanbossenger):

- [#612](https://github.com/WordPress/wp-feature-notifications/issues/612) Delete interface-notification.php and make the repository interface user-scoped (PR [#626](https://github.com/WordPress/wp-feature-notifications/pull/626))
- [#613](https://github.com/WordPress/wp-feature-notifications/issues/613) Fix Framework\Factory sharing one singleton across subclasses (PR [#624](https://github.com/WordPress/wp-feature-notifications/pull/624))
- [#614](https://github.com/WordPress/wp-feature-notifications/issues/614) Fix Serde UTC deserialisation and remove column fields from Message::$meta_keys (PR [#627](https://github.com/WordPress/wp-feature-notifications/pull/627))
- [#615](https://github.com/WordPress/wp-feature-notifications/issues/615) Database schema v2
- [#616](https://github.com/WordPress/wp-feature-notifications/issues/616) Fix the database migration gate and add a v1 → v2 upgrade routine
- [#617](https://github.com/WordPress/wp-feature-notifications/issues/617) Instantiate the notification repository and inject it into REST controllers
- [#618](https://github.com/WordPress/wp-feature-notifications/issues/618) REST schema and response cleanup for the notifications controller
- [#619](https://github.com/WordPress/wp-feature-notifications/issues/619) Fix client store bugs and convert to thunks

Other:

- [#607](https://github.com/WordPress/wp-feature-notifications/issues/607) Clean up Dependabot PRs (@jonathanbossenger)
- [#608](https://github.com/WordPress/wp-feature-notifications/issues/608) Explore notifications in AI-assisted and automated WordPress workflows (@Luehrsen)
- [#629](https://github.com/WordPress/wp-feature-notifications/issues/629) npm scripts test:php, php-install, lint:css and docGen are broken (@huzaifaalmesbah)
- [#630](https://github.com/WordPress/wp-feature-notifications/issues/630) npm scripts lint:pkg-json and lint:md:docs lint nothing (@huzaifaalmesbah)

## Discussion and activity

- [#302](https://github.com/WordPress/wp-feature-notifications/issues/302) Minimum Viable Product - v0.3.0
- [#55](https://github.com/WordPress/wp-feature-notifications/issues/55) Notification Levels - Prioritise notifications by importance
- [#56](https://github.com/WordPress/wp-feature-notifications/issues/56) Notification Timeout
- [#85](https://github.com/WordPress/wp-feature-notifications/issues/85) Demo Feedback: Move legacy notifications to hub
- [#377](https://github.com/WordPress/wp-feature-notifications/issues/377) Implement Subscription Controller
- [#382](https://github.com/WordPress/wp-feature-notifications/issues/382) Add authentication to REST endpoints (PR [#606](https://github.com/WordPress/wp-feature-notifications/pull/606))

## Milestones

- [0.3.0](https://github.com/WordPress/wp-feature-notifications/milestone/3): 1 closed / 25 open

## Needs attention

- **Ten human-authored PRs are awaiting review** and none were merged this week. The three Batch 0 PRs ([#624](https://github.com/WordPress/wp-feature-notifications/pull/624), [#626](https://github.com/WordPress/wp-feature-notifications/pull/626), [#627](https://github.com/WordPress/wp-feature-notifications/pull/627)) unblock the rest of 0.3.0.
- 0.3.0 tasks [#615](https://github.com/WordPress/wp-feature-notifications/issues/615)–[#619](https://github.com/WordPress/wp-feature-notifications/issues/619) have no PRs yet.
