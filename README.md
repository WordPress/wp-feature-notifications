# WP Notify - A Notification Center for WordPress (Feature Project)

A feature plugin for WordPress, which aims to create a new (better) way to manage and deliver notifications to the relevant audience.

Want to get involved? Join our weekly office hours every Wednesday at 14:00 UTC in the [#feature-notifications](https://wordpress.slack.com/messages/C2K1C71FE) channel of the [Make WordPress Slack](https://make.wordpress.org/chat/).

To get started, please take a look at our [project wiki](https://github.com/WordPress/wp-feature-notifications/wiki)

See also [Trac ticket #43484](https://core.trac.wordpress.org/ticket/43484).

## Development
We love your input! We want to make contributing to this project as easy and transparent as possible, whether it’s:

- Reporting a bug
- Testing the plugin
- Discussing the current state, features, improvements
- Submitting a fix or a new feature

We use GitHub to host code, to track issues and feature requests, as well as accept pull requests.
By contributing, you agree that your contributions will be licensed under its GPLv2 License.

Prerequisites:
- [NodeJS](https://nodejs.org/en/download/)
- [Composer](https://getcomposer.org/download/)
- [wp-env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) (optional)
- [Docker](https://docs.docker.com/get-docker/) (optional)

Installation:
```bash
$ git clone https://github.com/WordPress/wp-feature-notifications.git
$ cd wp-feature-notifications
$ npm i && composer install
$ wp-env start
```

We take advantage of [wp-script](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/) to compile scripts and styles for this plugin. 
You will mainly need these two commands:

`npm run build` - Transforms your code according to the configuration provided, so it’s ready for production and optimized for the best performance.  
`npm run start `- Transforms your code according to the configuration provided, so it’s ready for development. The script will automatically rebuild if you make changes to the code, and you will see the build errors in the console.

README! the ./build folder should not be committed!
