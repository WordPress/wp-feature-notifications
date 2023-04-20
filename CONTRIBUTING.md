# Contributing

We would love your input! We want to make contributing to this project as easy and transparent as possible, whether it’s:

- Reporting a bug
- Testing the plugin
- Discussing the current state, features, improvements
- Submitting a fix or a new feature

We use GitHub to host code, to track issues and feature requests, as well as accept pull requests.
By contributing, you agree that your contributions will be licensed under its [GPLv2 License](LICENSE.md).

## Development

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

## Workflow

Have an improvement, suggestion or bug? The first step is to [open an issue](https://github.com/WordPress/wp-feature-notifications/issues). New ideas and new contributors are very welcome! Please be sure to fill out all available fields, and provide as much detail as possible.

Once your issue has been opened, it will be triaged, labelled and moved to the relevant [project board](https://github.com/WordPress/wp-feature-notifications/projects?type=classic).

### Working on an issue

Please ensure that nobody else is already working on an issue before starting work, in order to avoid duplication of effort. If in doubt, it's best to ask in the issue itself! When starting work, you should assign the issue to yourself to make this as clear as possible.

If you are contributing code, be sure to follow our [Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/).

You should create one pull request for each indvidual issue you are working on. Make sure to [link it to the issue](https://docs.github.com/en/issues/tracking-your-work-with-issues/linking-a-pull-request-to-an-issue) for easy tracking. Create a draft pull request as early as possible for visibility.

Once your work is complete, and all automated checks have passed, please mark your pull request as ready for review. Anyone is free to add a review, however before a pull request can be merged it will need approval from a project maintainer.

Please tag at least one of [Sephsekla](https://github.com/Sephsekla), [erikyo](https://github.com/erikyo) or [johnhooks](https://github.com/johnhooks)to review.

## Releases

New releases should only be created from the `Trunk` branch. This is handled by a GitHub action whenever a new tag is created on this branch.

## Meetings

We hold weekyl office hours at every Wednesday at 15:00 UTC in the [#feature-notifications](https://wordpress.slack.com/messages/C2K1C71FE) channel of the [Make WordPress Slack](https://make.wordpress.org/chat/). New contributors are always welcome!

