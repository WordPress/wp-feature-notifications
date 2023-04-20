<?php
/**
 * Notifications API:Factory abstract class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Framework;

/**
 * Abstract class representing a factory.
 *
 * @template Model
 */
abstract class Factory {

	/**
	 * Container for the main instance of the class.
	 *
	 * @var ?Model
	 */
	protected static $instance = null;

	/**
	 * Instantiates a model object.

	 * @param array|string $args Array or string of arguments for creating a model.

	 *
	 * @return Model|false A newly created instance of model or false.
	 */
	abstract public function make( $args );

	/**
	 * Utility method to retrieve the main instance of the class.
	 *
	 * The instance will be created if it does not exist yet.
	 *
	 * @return Model The main instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
