<?php

namespace Rarst\WordPress\DateTime;

use Brain\Monkey\Functions;
use Brain\Monkey\Filters;

/**
 * @coversDefaultClass Rarst\WordPress\DateTime\WpDateTimeTrait
 */
class WpDateTimeTest extends WpDateTimeTestCase {

	/**
	 * @covers ::createFromPost()
	 */
	public function testCreateFromPost() {

		Functions\when( 'get_post_field' )->alias( function ( $field, $post ) {
			return empty( $post->$field ) ? false : $post->$field;
		} );

		Functions\expect( 'get_option' )->with( 'timezone_string' )->andReturn( 'Europe/Kiev' );

		$datetime   = ( new \DateTimeImmutable( '@' . time() ) )->setTimezone( new \DateTimeZone( 'Europe/Kiev' ) );
		$kiev_mysql = $datetime->format( WpDateTime::MYSQL );
		$utc_mysql  = $datetime->setTimezone( new \DateTimeZone( 'UTC' ) )->format( WpDateTime::MYSQL );

		$post        = (object) [ 'post_date' => $kiev_mysql ];
		$wp_datetime = WpDateTimeImmutable::createFromPost( $post );

		$this->assertEquals( 'Europe/Kiev', $wp_datetime->getTimezone()->getName() );
		$this->assertEquals( $datetime, $wp_datetime );

		$post        = (object) [ 'post_date_gmt' => $utc_mysql ];
		$wp_datetime = WpDateTimeImmutable::createFromPost( $post );

		$this->assertEquals( 'Europe/Kiev', $wp_datetime->getTimezone()->getName() );
		$this->assertEquals( $datetime, $wp_datetime );

		$post        = (object) [ 'post_modified_gmt' => $utc_mysql ];
		$wp_datetime = WpDateTimeImmutable::createFromPost( $post, 'modified' );

		$this->assertEquals( $datetime, $wp_datetime );

		$post = (object) [ 'post_date_gmt' => 'invalid date' ];

		$this->assertFalse( WpDateTimeImmutable::createFromPost( $post ) );
		$this->assertFalse( WpDateTimeImmutable::createFromPost( 'invalid post' ) );
		$this->assertFalse( WpDateTimeImmutable::createFromPost( new \stdClass(), 'invalid_field' ) );
	}

	/**
	 * @covers ::createFromFormat()
	 */
	public function testCreateFromFormat() {

		$wp_date_time = WpDateTime::createFromFormat( WpDateTime::MYSQL, 'invalid date' );

		$this->assertFalse( $wp_date_time );

		$mysql_date_string = '2017-11-06 16:49:00';
		$wp_date_time      = WpDateTime::createFromFormat( WpDateTime::MYSQL, $mysql_date_string );

		$this->assertInstanceOf( WpDateTime::class, $wp_date_time );

		$wp_date_time_immutable = WpDateTimeImmutable::createFromFormat( WpDateTime::MYSQL, $mysql_date_string );

		$this->assertInstanceOf( WpDateTimeImmutable::class, $wp_date_time_immutable );
	}

	/**
	 * @covers ::formatI18n()
	 * @covers ::formatDate()
	 * @covers ::formatTime()
	 */
	public function testFormatI18n() {

		$wp_datetime = new WpDateTimeImmutable( null, new \DateTimeZone( 'Europe/Kiev' ) );

		Functions\when( 'date_i18n' )->alias( function ( $format, $wp_timestamp ) use ( $wp_datetime ) {

			$unix_timestamp = $wp_timestamp - $wp_datetime->getOffset();

			$this->assertEquals( $wp_datetime->getTimestamp(), $unix_timestamp );
			$this->assertNotEquals( 'c', $format );
			$this->assertNotEquals( 'r', $format );
			$this->assertTrue( has_filter( 'pre_option_timezone_string', 'DateTimeZone->getName()' ) );

			$format = preg_replace( '/(?<!\\\\)U/', $wp_timestamp, $format );
			$format = preg_replace( '/(?<!\\\\)B/', $wp_datetime->setTimestamp( $wp_timestamp )->format( 'B' ), $format );

			return $wp_datetime->setTimestamp( $unix_timestamp )->format( $format );
		} );

		Filters\expectAdded( 'pre_option_timezone_string' )->zeroOrMoreTimes();

		$this->assertEquals( $wp_datetime->format( DATE_W3C ), $wp_datetime->formatI18n( 'c' ) );
		$this->assertEquals( $wp_datetime->format( DATE_RFC2822 ), $wp_datetime->formatI18n( 'r' ) );

		$this->assertEquals( $wp_datetime->format( 'U' ), $wp_datetime->formatI18n( 'U' ) );
		$this->assertEquals( $wp_datetime->format( 'B' ), $wp_datetime->formatI18n( 'B' ) );

		$offset = sprintf( '%+03d:00', $wp_datetime->getOffset() / 3600 );

		$this->assertEquals(
			$wp_datetime->setTimezone( new \DateTimeZone( $offset ) )->format( DATE_W3C ),
			$wp_datetime->setTimezone( new \DateTimeZone( $offset ) )->formatI18n( DATE_W3C )
		);

		$date_format = 'F j, Y';
		Functions\when( 'get_option' )->justReturn( $date_format );
		$this->assertEquals( $wp_datetime->format( $date_format ), $wp_datetime->formatDate() );

		$time_format = 'g:i a';
		Functions\when( 'get_option' )->justReturn( $time_format );
		$this->assertEquals( $wp_datetime->format( $time_format ), $wp_datetime->formatTime() );

		$this->assertFalse( has_filter( 'pre_option_timezone_string', 'DateTimeZone->getName()' ) );
	}
}
