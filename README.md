# WordPress Feature Project - Notifications

> A feature plugin for WordPress, which aims to create a new (better) way to manage and deliver notifications to the relevant audience.

- Contributors: schlessera, psykro, raaaahman, danbilauca, Sephsekla, erikyo, JasonTheAdams, johnhooks
- Tags: feature-notifications
- Requires at least: 6.2
- Tested up to: 6.2
- Requires PHP: 7.4
- License: GPLv2 or later
- License URI: https://www.gnu.org/licenses/gpl-2.0.html

See also [Trac ticket #43484](https://core.trac.wordpress.org/ticket/43484).

## Contributing to the project

Want to get involved? Join our weekly office hours every Wednesday at 15:00 UTC in the [#feature-notifications](https://wordpress.slack.com/messages/C2K1C71FE) channel of the [Make WordPress Slack](https://make.wordpress.org/chat/).

Please be sure to read our [contribution guidelines](CONTRIBUTING.md) before getting started.

### Prerequisites

- [NodeJS](https://nodejs.org/en/download/)
- [Composer](https://getcomposer.org/download/)
- [wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) (optional)
- [Docker](https://docs.docker.com/get-docker/) (optional)

We recommend using [nvm](https://github.com/nvm-sh/nvm) to ensure a compatible node version.

### Installation

```bash
$ git clone https://github.com/WordPress/wp-feature-notifications.git
$ cd wp-feature-notifications
$ nvm use && npm i && composer install
$ wp-env start
```

We take advantage of [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) to compile scripts and styles for this plugin.
You will mainly need these two commands:

`npm run build` - Transforms your code according to the configuration provided, so it’s ready for production and optimized for the best performance.
`npm run start`- Transforms your code according to the configuration provided, so it’s ready for development. The script will automatically rebuild if you make changes to the code, and you will see the build errors in the console.

## Issue Workflow

### Creating an issue

Have an improvement, suggestion or bug? The first step is to [open an issue](https://github.com/WordPress/wp-feature-notifications/issues). New ideas and new contributors are very welcome! Please be sure to fill out all available fields, and provide as much detail as possible.

Once your issue has been opened, it will be triaged, labelled and moved to the relevant [project board](https://github.com/WordPress/wp-feature-notifications/projects?type=classic).

> [!IMPORTANT]
> If your issue is a security vulnerabilty, please practice responsible disclosure and submit this at https://github.com/WordPress/wp-feature-notifications/security/advisories/new.


###  Working on an issue

Please ensure that nobody else is already working on an issue before starting work, in order to avoid duplication of effort. If in doubt, it's best to ask in the issue itself! When starting work, **you should assign the issue to yourself** to make this as clear as possible.

If you are contributing code, be sure to follow our [Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) for both JavaScript and PHP.

You should create one pull request for each indvidual issue you are working on. Make sure to [link it to the issue](https://docs.github.com/en/issues/tracking-your-work-with-issues/linking-a-pull-request-to-an-issue) for easy tracking. Create a draft pull request as early as possible for visibility; your code doesn't have to be finished to create this!

Once your work is complete, and all automated checks have passed, please mark your pull request as ready for review. Anyone is free to add a review, however before a pull request can be merged it will need approval from a project maintainer.

Please tag at least one of [Sephsekla](https://github.com/Sephsekla), [erikyo](https://github.com/erikyo) or [johnhooks](https://github.com/johnhooks)to review.

### Merging a pull request

Once your pull request is approved, it will be merged by a maintainer. Thank you for your contribution to the project!

## Releases

New releases should only be created from the `Trunk` branch. This is handled by a GitHub action whenever a new tag is created on this branch.

A new release should only be created by a project maintainer after discussion with the team.

## Meetings

We hold weekly office hours at every Wednesday at 15:00 UTC in the [#feature-notifications](https://wordpress.slack.com/messages/C2K1C71FE) channel of the [Make WordPress Slack](https://make.wordpress.org/chat/). New contributors are always welcome!

We also hold a monthly planning meeting via Google Meet. This is currently held on the last Tuesday of every month at 14:00 UTC.
