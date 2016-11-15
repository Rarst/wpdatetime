<?php

namespace Rarst\WordPress\DateTime;

/**
 * Extension of DateTime for WordPress. Immutable version.
 */
class WpDateTimeImmutable extends \DateTimeImmutable implements WpDateTimeInterface {

	const MYSQL = 'Y-m-d H:i:s';

	use WpDateTimeTrait;
}
