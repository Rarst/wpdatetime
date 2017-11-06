<?php
namespace Rarst\WordPress\DateTime;

use PHPUnit_Framework_TestCase;
use Brain\Monkey;

class WpDateTimeTestCase extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown() {
		Monkey\tearDown();
		parent::tearDown();
	}
}