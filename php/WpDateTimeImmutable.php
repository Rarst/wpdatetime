<?php

namespace Rarst\WordPress\DateTime;

/**
 * Extension of DateTime for WordPress. Immutable version.
 */
class WpDateTimeImmutable extends \DateTimeImmutable implements WpDateTimeInterface {

	const MYSQL = 'Y-m-d H:i:s';

	use WpDateTimeTrait;

	/**
	 * Overrides upstream method to correct returned instance type to the inheriting one.
	 *
	 * {@inheritdoc}
	 *
	 * @return static
	 */
	public static function createFromMutable( $dateTime ) {

		$instance = new static( '@' . $dateTime->getTimestamp() );

		return $instance->setTimezone( $dateTime->getTimezone() );
	}
}
