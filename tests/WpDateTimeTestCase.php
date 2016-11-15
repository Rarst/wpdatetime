<?php
namespace Rarst\WordPress\DateTime;

use PHPUnit_Framework_TestCase;
use Brain\Monkey;

class WpDateTimeTestCase extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		parent::setUp();
		Monkey::setUpWP();
	}

	protected function tearDown() {
		Monkey::tearDownWP();
		parent::tearDown();
	}
}