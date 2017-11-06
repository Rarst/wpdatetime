<?php

namespace Rarst\WordPress\DateTime;

/**
 * @coversDefaultClass Rarst\WordPress\DateTime\WpDateTimeImmutable
 */
class WpDateTimeImmutableTest extends WpDateTimeTestCase {

	public function testImmutable() {

		$utc  = new \DateTimeZone( 'UTC' );
		$kiev = new \DateTimeZone( 'Europe/Kiev' );

		$wp_datetime = new WpDateTime( 'now', $utc );
		$this->assertSame( $wp_datetime, $wp_datetime->setTimezone( $kiev ) );

		$wp_datetime_immutable = new WpDateTimeImmutable( 'now', $utc );
		$this->assertNotSame( $wp_datetime_immutable, $wp_datetime_immutable->setTimezone( $kiev ) );
	}

	/**
	 * @covers ::createFromMutable()
	 */
	public function testCreateFromMutable() {

		$datetime = new \DateTime( 'now' );

		$wp_datetime_immutable = WpDateTimeImmutable::createFromMutable( $datetime );

		$this->assertInstanceOf( WpDateTimeImmutable::class, $wp_datetime_immutable );
	}
}
