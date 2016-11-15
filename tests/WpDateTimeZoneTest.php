<?php

namespace Rarst\WordPress\DateTime;

use Brain\Monkey\Functions;

/**
 * @coversDefaultClass WpDateTimeZone
 */
class WpDateTimeZoneTest extends WpDateTimeTestCase {

	/**
	 * @covers ::getWpTimezone
	 */
	public function testGetWpTimezoneString() {

		Functions::expect( 'get_option' )->with( 'timezone_string' )->andReturn( 'Europe/Kiev' );

		$this->assertEquals( 'Europe/Kiev', WpDateTimeZone::getWpTimezone()->getName() );
	}

	/**
	 * @covers ::getWpTimezone
	 */
	public function testGetWpTimezoneOffset() {

		Functions::expect( 'get_option' )->with( 'timezone_string' )->andReturn( false );
		Functions::expect( 'get_option' )->with( 'gmt_offset' )->andReturn( 2 );

		$this->assertEquals( '+02:00', WpDateTimeZone::getWpTimezone()->getName() );
	}
}
