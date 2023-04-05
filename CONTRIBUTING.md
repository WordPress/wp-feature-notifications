# Contributing

We love your input! We want to make contributing to this project as easy and transparent as possible, whether it’s:

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


## Releases

New releases should only be created from the `Trunk` branch. This is handled by a GitHub action whenever a new tag is created on this branch.

## Meetings

We hold weekyl office hours at every Wednesday at 13:00 UTC in the [#feature-notifications](https://wordpress.slack.com/messages/C2K1C71FE) channel of the [Make WordPress Slack](https://make.wordpress.org/chat/). New contributors are always welcome!

