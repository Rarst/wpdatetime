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

		$this->assertFalse( WpDateTime::createFromPost( 'invalid post' ) );
		$this->assertFalse( WpDateTime::createFromPost( new \stdClass(), 'invalid_field' ) );

		$utc      = new \DateTimeZone( 'UTC' );
		$datetime = new \DateTimeImmutable( 'now', new \DateTimeZone( 'Europe/Kiev' ) );

		$post        = (object) [ 'post_date_gmt' => $datetime->setTimezone( $utc )->format( WpDateTime::MYSQL ) ];
		$wp_datetime = WpDateTime::createFromPost( $post );

		$this->assertEquals( 'Europe/Kiev', $wp_datetime->getTimezone()->getName() );
		$this->assertEquals( $datetime->format( DATE_W3C ), $wp_datetime->format( DATE_W3C ) );

		$post        = (object) [ 'post_modified_gmt' => $datetime->setTimezone( $utc )->format( WpDateTime::MYSQL ) ];
		$wp_datetime = WpDateTime::createFromPost( $post, 'modified' );

		$this->assertEquals( 'Europe/Kiev', $wp_datetime->getTimezone()->getName() );
		$this->assertEquals( $datetime->format( DATE_W3C ), $wp_datetime->format( DATE_W3C ) );

		$post        = (object) [ 'post_date' => $datetime->format( WpDateTime::MYSQL ) ];
		$wp_datetime = WpDateTime::createFromPost( $post );

		$this->assertEquals( 'Europe/Kiev', $wp_datetime->getTimezone()->getName() );
		$this->assertEquals( $datetime->format( DATE_W3C ), $wp_datetime->format( DATE_W3C ) );

		$post = (object) [ 'post_date_gmt' => 'invalid date' ];

		$this->assertFalse( WpDateTime::createFromPost( $post ) );
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

			return $wp_datetime->setTimestamp( $unix_timestamp )->format( $format );
		} );

		Filters\expectAdded( 'pre_option_timezone_string' )->times( 5 );

		$this->assertEquals( $wp_datetime->format( DATE_W3C ), $wp_datetime->formatI18n( 'c' ) );
		$this->assertEquals( $wp_datetime->format( DATE_RFC2822 ), $wp_datetime->formatI18n( 'r' ) );

		$this->assertEquals(
			$wp_datetime->setTimezone( new \DateTimeZone( '+02:00' ) )->format( DATE_W3C ),
			$wp_datetime->setTimezone( new \DateTimeZone( '+02:00' ) )->formatI18n( DATE_W3C )
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
