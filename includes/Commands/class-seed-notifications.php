<?php

namespace WP\Notifications\Commands;

use Exception;
use WP_CLI;
use Faker\Factory;
use WP\Notifications;

/**
 * Class Seed_Notifications
 *
 * A WP-CLI command for seeding the database with notifications.
 *
 * @package wordpress/wp-feature-notifications
 */
class Seed_Notifications {

	/**
	 * @var Notifications\Factory
	 */
	private $notification_factory;

	/**
	 * @var Notifications\Repository
	 */
	private $notification_repository;

	/**
	 * @param Notifications\Factory    $notification_factory
	 * @param Notifications\Repository $notification_repository
	 */
	public function __construct() {
	}

	/**
	 * Seed the WP Notifications database with notifications.
	 *
	 * ## OPTIONS
	 *
	 * [--count=<count>]
	 * : Number of donations to generate
	 * default: 10
	 *
	 * [--preview=<preview>]
	 * : Preview generated data
	 * default: false
	 *
	 * [--params=<params>]
	 * : Additional params
	 * default: ''
	 *
	 * ## EXAMPLES
	 *
	 *     wp notifications seed --count=50 --preview --params=severity=error
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assoc_args ) {
		global $wpdb;

		// Get CLI args
		$count      = WP_CLI\Utils\get_flag_value( $assoc_args, 'count', $default = 10 );
		$preview    = WP_CLI\Utils\get_flag_value( $assoc_args, 'preview', $default = false );
		$additional = WP_CLI\Utils\get_flag_value( $assoc_args, 'params', $default = '' );

		// Additional params
		parse_str( $additional, $params );

		try {
			$notifications = $this->notification_factory->make( $count ); // TODO actually generate the test data.
		} catch ( Exception $e ) {
			return WP_CLI::error( $e->getMessage() );
		}

		if ( $preview ) {
			WP_CLI\Utils\format_items(
				'table',
				$notifications,
				array_keys( $this->notification_repository->definition() )
			);
		} else {
			$progress = WP_CLI\Utils\make_progress_bar( 'Generating ', $count );

			// Start DB transaction
			$wpdb->query( 'START TRANSACTION' );

			try {
				foreach ( $notifications as $notification ) {
					$this->notification_repository->insert( $notification, $params );
					$progress->tick();
				}

				$wpdb->query( 'COMMIT' );

				$progress->finish();
			} catch ( Exception $e ) {
				$wpdb->query( 'ROLLBACK' );

				WP_CLI::error( $e->getMessage() );
			}
		}
	}
}
