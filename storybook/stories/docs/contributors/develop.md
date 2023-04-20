# Code contributions

In order to make any code contributions, you will need to install [Git](https://git-scm.com/) on your computer.

## Developing on a local environment

Any WAMP/MAMP/LAMP local environment with a WordPress installation will be suited for local development.

### Running PHP unit tests

**Warning**: For running tests, you need a **dedicated test database**. This is important to separate it from your production databases because the tests will drop the complete database each time they are run!

You also will need a local copy of the [WordPress/wordpress-develop](https://github.com/WordPress/wordpress-develop) repository.

```bash
# Through HTTPS
git clone https://github.com/WordPress/wordpress-develop.git
# or
# Through SSH
git clone git@github.com:WordPress/wordpress-develop.git
```

You will then need to add a `WP_TESTS_DIR` **environment variable** that points to the WordPress tests library.

For **Unix** operating systems:

In your `~/.profile` file, add the following line:

```bash
export WP_TESTS_DIR="$HOME/path/to/wordpress-develop/tests/phpunit"
```

Then in `wordpress-develop`, copy the `wp-tests-config-sample.php` into a `wp-tests-config.php` file.

Change the following lines to match your test database:

```php
define( 'DB_NAME', 'my-dedicated-test-database' );
define( 'DB_USER', 'user' );
define( 'DB_PASSWORD', 'password' );
define( 'DB_HOST', 'localhost' );
```

You also need a local installation of [Composer](https://getcomposer.org/doc/00-intro.md). This will let you install the development dependencies, including [PHPUnit](https://phpunit.de/).

```bash
composer install
```

And you can run the tests from the PHPUnit package:

```bash
vendor/bin/phpunit
```

## Developing with wp-env

The [wp-env package](https://developer.wordpress.org/block-editor/packages/packages-env/) was developed with the Gutenberg project as a quick way to create a standard WordPress environment using Docker. It is also published as the `@wordpress/env` npm package.

You can use it for contributing to the WP Feature Notifications project, but you need to install it on your computer first. read the [prerequisites](https://developer.wordpress.org/block-editor/packages/packages-env/#prerequisites) and the [install as a global package](https://developer.wordpress.org/block-editor/packages/packages-env/#installation-as-a-global-package) from its manual.

### Running PHP unit tests

An npm script is provided in order to start the PHP unit tests:

```bash
npm run test-unit-php
```
