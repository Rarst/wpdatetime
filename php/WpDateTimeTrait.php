<?php

namespace Rarst\WordPress\DateTime;

/**
 * Concrete implementation, shared by regular and Immutable WpDateTime classes.
 *
 * Implements WpDateTimeInterface. Expects using class to implement \DateTimeInterface.
 *
 * @see \DateTimeInterface
 * @see WpDateTimeInterface
 * @method \DateTimeZone getTimezone()
 */
trait WpDateTimeTrait {

	/**
	 * Create instance from WordPress–style Post input.
	 *
	 * Defaults to current WordPress time zone.
	 *
	 * @param int|object|\WP_Post $post  WordPress Post to create instance for.
	 * @param string              $field Post field to use `date` or `modified`.
	 *
	 * @return boolean|static Object instance or `false` on failure.
	 */
	public static function createFromPost( $post, $field = 'date' ) {

		if ( ! in_array( $field, [ 'date', 'modified' ], true ) ) {
			return false;
		}

		$post_date = get_post_field( "post_{$field}_gmt", $post );

		$wp_timezone     = WpDateTimeZone::getWpTimezone();
		$create_timezone = new \DateTimeZone( 'UTC' );

		// Missing GMT data, fall back to usual field and assume WP timezone.
		if ( empty( $post_date ) ) {
			$post_date       = get_post_field( "post_{$field}", $post );
			$create_timezone = $wp_timezone;
		}

		if ( empty( $post_date ) ) {
			return false;
		}

		try {
			$wp_time = new static( $post_date, $create_timezone );
			$wp_time->setTimezone( $wp_timezone );

			return $wp_time;
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Formats date in current WordPress locale, but uses object’s time zone.
	 *
	 * @param string $format date()–compatible format to use.
	 *
	 * @return string Formatted date string.
	 */
	public function formatI18n( $format ) {

		$timezone_filter = [ $this->getTimezone(), 'getName' ];

		// Fix shorthands bug in date_i18n(), see https://core.trac.wordpress.org/ticket/20973.
		$format = preg_replace( '/(?<!\\\\)c/', DATE_W3C, $format );
		$format = preg_replace( '/(?<!\\\\)r/', DATE_RFC2822, $format );

		add_filter( 'pre_option_timezone_string', $timezone_filter, 10, 0 );
		$date_i18n = date_i18n( $format, $this->getTimestamp() + $this->getOffset(), true );
		remove_filter( 'pre_option_timezone_string', $timezone_filter, 10, 0 );

		return $date_i18n;
	}
}
