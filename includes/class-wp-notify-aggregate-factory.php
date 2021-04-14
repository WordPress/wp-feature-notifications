<?php

abstract class WP_Notify_Aggregate_Factory {

	/**
	 * Array of factories that this aggregate factory represents.
	 *
	 * @var array
	 */
	private $factories;

	/**
	 * Instantiate an aggregate factory.
	 *
	 * @param array $factories Array of factories that the aggregate factory
	 *                         wraps.
	 */
	public function __construct( $factories = array() ) {
		if ( ! is_array( $factories ) ) {
			$factories = array( $factories );
		}

		foreach ( $factories as $factory ) {
			$this->register( $factory );
		}
	}

	/**
	 * Register a new factory implementation with the aggregate factory.
	 *
	 * @param object $factory Factory to register.
	 */
	public function register( $factory ) {
		if ( ! is_a( $factory, $this->get_interface() ) ) {
			// TODO: Throw exception.
		}

		$factory_class = get_class( $factory );

		if ( ! array_key_exists( $factory_class, $this->factories ) ) {
			$this->factories[ $factory_class ] = $factory;
		}
	}

	/**
	 * Create a new instance of the interface the factory represents.
	 *
	 * @param mixed  $value Value to use for creation.
	 * @param string $type  Optional. Type to use for creation.
	 *
	 * @return mixed
	 */
	public function create( $value, $type = null ) {
		foreach ( $this->factories as $factory ) {
			if ( $factory->accepts( $type ) ) {
				return $factory->create( $value, $type );
			}
		}

		// TODO: Throw exception.
	}

	/**
	 * Whether the factory accepts a given type for instantiation.
	 *
	 * @param string $type Type that should be instantiated.
	 *
	 * @return bool Whether the factory accepts the given type.
	 */
	public function accepts( $type ) {
		foreach ( $this->factories as $factory ) {
			if ( $factory->accepts( $type ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get the interface that this aggregate factory can instantiate
	 * implementations of.
	 *
	 * @return string Class name of the interface.
	 */
	abstract protected function get_interface();
}
