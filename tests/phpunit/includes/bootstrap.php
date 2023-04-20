<?php

require __DIR__ . '/../../../vendor/autoload.php';

$tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $tests_dir ) {
	$tests_dir = getenv( 'WP_PHPUNIT__DIR' );
}

if ( ! $tests_dir ) {
	$tests_dir = '/tmp/wordpress-tests-lib';
}

require_once $tests_dir . '/includes/functions.php';

function manually_load_plugin() {
	require __DIR__ . '/../../../wp-feature-notifications.php';
}
tests_add_filter( 'plugins_loaded', 'manually_load_plugin' );

function handle_wp_setup_failure( $message ) {
	if ( is_wp_error( $message ) ) {
		$message = $message->get_error_message();
	}

	throw new Exception( 'WordPress died: ' . $message );
}
tests_add_filter( 'wp_die_handler', 'handle_wp_setup_failure' );

/*
* Load PHPUnit Polyfills for the WP testing suite.
*/
define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', __DIR__ . '/../../../vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php' );

// load the WP testing environment.
require $tests_dir . '/includes/bootstrap.php';

remove_filter( 'wp_die_handler', 'handle_wp_setup_failure' );

require dirname( __FILE__ ) . '/class-dummy-message.php';
require dirname( __FILE__ ) . '/class-test-case.php';
