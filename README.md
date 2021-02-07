# Notification Center for WordPress (Feature Project)

See [Trac ticket #43484](https://core.trac.wordpress.org/ticket/43484).

## Developing on a local environment

Any WAMP/MAMP/LAMP local environment with a WordPress installation will be suited for local development.

### Running unit tests

You need a local installation of [Composer](https://getcomposer.org/doc/00-intro.md).

Then you need to install PHP dependencies.

```bash
composer install
```

And you can run the tests from the PHPUnit package:

```bash
vendor/bin/phpunit
```

## Developing with wp-env

The [wp-env package](https://developer.wordpress.org/block-editor/packages/packages-env/) was developed with the Gutenberg project as a quick way to create a standard WordPress environment using Docker. It is also published as the `@wordpress/env` npm package.

You can use it for contributing to the WP Notify project, but you need to install it on your computer first. read the [prerequisites](https://developer.wordpress.org/block-editor/packages/packages-env/#prerequisites) and the [install as a global package](https://developer.wordpress.org/block-editor/packages/packages-env/#installation-as-a-global-package) from its manual.

### Running unit tests

An npm script is provided in order to start the PHP unit tests:

```bash
npm run test-unit-php
```
