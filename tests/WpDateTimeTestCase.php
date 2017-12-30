<?php
namespace Rarst\WordPress\DateTime;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;

class WpDateTimeTestCase extends TestCase {

	protected function setUp() {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown() {
		Monkey\tearDown();
		parent::tearDown();
	}
}