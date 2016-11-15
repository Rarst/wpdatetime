<?php

namespace Rarst\WordPress\DateTime;

/**
 * Extension of DateTimeZone for WordPress.
 */
class WpDateTimeZone extends \DateTimeZone {

	/**
	 * Determine time zone from WordPress options and return as object.
	 *
	 * @return self
	 */
	public static function getWpTimezone() {

		$timezone_string = get_option( 'timezone_string' );

		if ( ! empty( $timezone_string ) ) {
			return new self( $timezone_string );
		}

		$offset  = get_option( 'gmt_offset' );
		$hours   = (int) $offset;
		$minutes = ( $offset - floor( $offset ) ) * 60;
		$offset  = sprintf( '%+03d:%02d', $hours, $minutes );

		return new self( $offset );
	}
}
