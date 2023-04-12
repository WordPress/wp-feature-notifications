<?php

namespace WP\Notifications\Commands;

use Exception;

use WP_CLI;
use Faker;

/**
 * Class Seed_Users
 *
 * A WP-CLI command for seeding the database with users.
 *
 * @package wordpress/wp-feature-notifications
 */
class Seed_Users {

	/**
	 * TODO is a constructor necessary?
	 */
	public function __construct() {}

	/**
	 * Seed the WordPress database with test users.
	 *
	 * ## OPTIONS
	 *
	 * [--count=<count>]
	 * : Number of users to generate
	 * default: 10
	 *
	 * [--preview=<preview>]
	 * : Preview generated data
	 * default: false'
	 *
	 * ## EXAMPLES
	 *
	 *     wp notifications seed-users --count=10 --preview=true
	 *
	 * @when after_wp_load
	 */
	public function __invoke( $args, $assoc_args ) {
		global $wpdb;

		$faker = Faker\Factory::create();

		// Get CLI args
		$count   = WP_CLI\Utils\get_flag_value( $assoc_args, 'count', $default = 10 );
		$preview = WP_CLI\Utils\get_flag_value( $assoc_args, 'preview', $default = false );

		try {
			$users = array();

			for ( $i = 0; $i < $count; $i++ ) {
				array_push(
					$users,
					array(
						'user_login' => $faker->unique()->userName(),
						'user_email' => $faker->unique()->email(),
						'user_pass'  => 'password',
						'first_name' => $faker->firstName(),
						'last_name'  => $faker->lastName(),
						'role'       => 'author',
					)
				);
			}
		} catch ( Exception $e ) {
			return WP_CLI::error( $e->getMessage() );
		}

		if ( $preview ) {
			WP_CLI\Utils\format_items(
				'table',
				$users,
				'user_login,user_email,user_pass,role'
			);
		} else {
			$progress = WP_CLI\Utils\make_progress_bar( 'Generating ', $count );

			try {
				/**
				 * @var int[]
				 */
				$user_ids = array();

				foreach ( $users as $user ) {
					$user_id = wp_insert_user(
						wp_slash(
							$user
						)
					);

					if ( is_wp_error( $user_id ) ) {
						throw $user_id;
					}

					array_push( $user_ids, $user_id );

					$progress->tick();
				}

				$progress->finish();
			} catch ( Exception $e ) {

				WP_CLI::error( $e->getMessage() );
			}
		}
	}
}
