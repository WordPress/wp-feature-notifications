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
	 * Container for the main instance of each factory subclass, keyed by class name.
	 *
	 * @var array<class-string<static>, static>
	 */
	protected static $instances = array();

	/**
	 * Instantiates a model object.
	 *
	 * @param array|string $args Array or string of arguments for creating a model.
	 *
	 * @return Model|false A newly created instance of model or false.
	 */
	abstract public function make( $args );

	/**
	 * Utility method to retrieve the main instance of the class.
	 *
	 * The instance will be created if it does not exist yet. Each subclass
	 * has its own instance.
	 *
	 * @return static The main instance.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instances[ static::class ] ) ) {
			self::$instances[ static::class ] = new static();
		}

		return self::$instances[ static::class ];
	}
}
