<?php

namespace Rarst\WordPress\DateTime;

/**
 * Extension of DateTimeZone for WordPress.
 */
class WpDateTimeZone extends \DateTimeZone {

	/**
	 * Determine time zone from WordPress options and return as object.
	 *
	 * @return static
	 */
	public static function getWpTimezone() {

		if ( function_exists( 'wp_timezone' ) ) {
			_deprecated_function( __FUNCTION__, '5.3', 'wp_timezone()' );
		}

		$timezone_string = get_option( 'timezone_string' );

		if ( ! empty( $timezone_string ) ) {
			return new static( $timezone_string );
		}

		$offset  = get_option( 'gmt_offset' );
		$sign    = $offset < 0 ? '-' : '+';
		$hours   = (int) $offset;
		$minutes = abs( ( $offset - (int) $offset ) * 60 );
		$offset  = sprintf( '%s%02d:%02d', $sign, abs( $hours ), $minutes );

		return new static( $offset );
	}
}
