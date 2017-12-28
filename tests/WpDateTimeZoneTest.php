<?php

namespace Rarst\WordPress\DateTime;

use Brain\Monkey\Functions;

/**
 * @coversDefaultClass Rarst\WordPress\DateTime\WpDateTimeZone
 */
class WpDateTimeZoneTest extends WpDateTimeTestCase {

	/**
	 * @covers ::getWpTimezone
	 */
	public function testGetWpTimezoneString() {

		Functions\expect( 'get_option' )->with( 'timezone_string' )->andReturn( 'Europe/Kiev' );

		$this->assertEquals( 'Europe/Kiev', WpDateTimeZone::getWpTimezone()->getName() );
	}

	/**
	 * @dataProvider timezoneOffsetProvider
	 *
	 * @param float  $gmt_offset Numeric offset from UTC.
	 * @param string $tz_name    Expected timezone name.
	 *
	 * @covers ::getWpTimezone
	 */
	public function testGetWpTimezoneOffset( $gmt_offset, $tz_name ) {

		Functions\expect( 'get_option' )->with( 'timezone_string' )->andReturn( false );
		Functions\expect( 'get_option' )->with( 'gmt_offset' )->andReturn( $gmt_offset );

		$this->assertEquals( $tz_name, WpDateTimeZone::getWpTimezone()->getName() );
	}

	/**
	 * Data provider to test numeric offset conversion.
	 *
	 * @return array
	 */
	public function timezoneOffsetProvider() {

		return [
			[ - 4, '-04:00' ],
			[ - 3.75, '-03:45' ],
			[ - 2.5, '-02:30' ],
			[ - 1.25, '-01:15' ],
			[ 0, '+00:00' ],
			[ 1.25, '+01:15' ],
			[ 2.5, '+02:30' ],
			[ 3.75, '+03:45' ],
			[ 4, '+04:00' ],
		];
	}
}
