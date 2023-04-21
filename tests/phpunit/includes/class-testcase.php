<?php

namespace WP\Notifications\Tests;

use PHPUnit_Adapter_TestCase;

class TestCase extends PHPUnit_Adapter_TestCase {

	/**
	 * Asserts that the contents of two un-keyed, single arrays are the same, without accounting for the order of elements.
	 *
	 * Copied from WP_UnitTestCase_Base.
	 *
	 * @param array  $expected Expected array.
	 * @param array  $actual   Array to check.
	 * @param string $message  Optional. Message to display when the assertion fails.
	 */
	public function assertSameSets( $expected, $actual, $message = '' ) {
		$this->assertIsArray( $expected, $message . ' Expected value must be an array.' );
		$this->assertIsArray( $actual, $message . ' Value under test is not an array.' );

		sort( $expected );
		sort( $actual );
		$this->assertSame( $expected, $actual, $message );
	}

}
