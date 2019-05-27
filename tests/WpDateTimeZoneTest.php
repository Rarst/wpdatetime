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
			[ - 12, '-12:00' ],
			[ - 11.5, '-11:30' ],
			[ - 11, '-11:00' ],
			[ - 10.5, '-10:30' ],
			[ - 10, '-10:00' ],
			[ - 9.5, '-09:30' ],
			[ - 9, '-09:00' ],
			[ - 8.5, '-08:30' ],
			[ - 8, '-08:00' ],
			[ - 7.5, '-07:30' ],
			[ - 7, '-07:00' ],
			[ - 6.5, '-06:30' ],
			[ - 6, '-06:00' ],
			[ - 5.5, '-05:30' ],
			[ - 5, '-05:00' ],
			[ - 4.5, '-04:30' ],
			[ - 4, '-04:00' ],
			[ - 3.5, '-03:30' ],
			[ - 3, '-03:00' ],
			[ - 2.5, '-02:30' ],
			[ - 2, '-02:00' ],
			[ '-1.5', '-01:30' ],
			[ - 1.5, '-01:30' ],
			[ - 1, '-01:00' ],
			[ - 0.5, '-00:30' ],
			[ 0, '+00:00' ],
			[ '0', '+00:00' ],
			[ 0.5, '+00:30' ],
			[ 1, '+01:00' ],
			[ 1.5, '+01:30' ],
			[ '1.5', '+01:30' ],
			[ 2, '+02:00' ],
			[ 2.5, '+02:30' ],
			[ 3, '+03:00' ],
			[ 3.5, '+03:30' ],
			[ 4, '+04:00' ],
			[ 4.5, '+04:30' ],
			[ 5, '+05:00' ],
			[ 5.5, '+05:30' ],
			[ 5.75, '+05:45' ],
			[ 6, '+06:00' ],
			[ 6.5, '+06:30' ],
			[ 7, '+07:00' ],
			[ 7.5, '+07:30' ],
			[ 8, '+08:00' ],
			[ 8.5, '+08:30' ],
			[ 8.75, '+08:45' ],
			[ 9, '+09:00' ],
			[ 9.5, '+09:30' ],
			[ 10, '+10:00' ],
			[ 10.5, '+10:30' ],
			[ 11, '+11:00' ],
			[ 11.5, '+11:30' ],
			[ 12, '+12:00' ],
			[ 12.75, '+12:45' ],
			[ 13, '+13:00' ],
			[ 13.75, '+13:45' ],
			[ 14, '+14:00' ],
		];
	}
}
