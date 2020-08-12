# WpDateTime — DateTime extension for WordPress

WpDateTime is an extension of PHP’s [`DateTime`](http://php.net/manual/en/class.datetime.php) and [`DateTimeZone`](http://php.net/manual/en/class.datetimezone.php) classes for WordPress context.

It makes it easy to instance time objects from WordPress posts and produce localized output with correct format and time zone handling.

## Retired

The project is **retired** in favor of native WordPress 5.3+ functions:

- `WpDateTime::createFromPost()` — [`get_post_datetime()`](https://developer.wordpress.org/reference/functions/get_post_datetime/)
- `WpDateTime->formatI18n()` — [`wp_date()`](https://developer.wordpress.org/reference/functions/wp_date/)
- `WpDateTimeZone::getWpTimezone()` — [`wp_timezone()`](https://developer.wordpress.org/reference/functions/wp_timezone/)

## Installation

Require as [Composer](https://getcomposer.org/) package in your project:

```bash
composer require rarst/wpdatetime
```

## Usage

### `WpDateTime` and `WpDateTimeImmutable`

Classes extend `DateTime` and `DateTimeImmutable` respectively and retain their full functionality.

You can use shared `WpDateTimeInterface` to hint for both.

#### Methods

- `WpDateTime::createFromPost()` creates object instance from WP post. Time zone defaults to current WP setting.
- `WpDateTime->formatI18n()` outputs formatted and localized date in object’s time zone.
- `WpDateTime->formatDate()` outputs in current WP date format.
- `WpDateTime->formatTime()` outputs in current WP time format.

#### Example

```php
use Rarst\WordPress\DateTime\WpDateTime;

$date = WpDateTime::createFromPost( get_post() );

printf(
	'Posted on: <time datetime="%s">%s</time>',
	$date->format( DATE_RFC3339 ),
	$date->formatDate()
);
// Posted on: <time datetime="2014-11-07T15:36:31+02:00">Ноябрь 7, 2014</time>
```

### `WpDateTimeZone`

Class extends `DateTimeZone`.

#### Methods

- `WpDateTimeZone::getWpTimezone()` static method creates object instance from current WordPress settings. Defaults to `timezone_string` option and falls back to `gmt_offset` one.

#### Example

```php
use Rarst\WordPress\DateTime\WpDateTimeZone;

// Timezone string.
var_dump( WpDateTimeZone::getWpTimezone()->getName() );
// string(11) "Europe/Kiev"

// GMT offset.
var_dump( WpDateTimeZone::getWpTimezone()->getName() );
// string(6) "+02:00"
```

## Tests

Tests use [Brain Monkey](https://brain-wp.github.io/BrainMonkey/) (included in dependencies) and PHPUnit 7 (not included).

```bash
composer install
phpunit
```

## License

MIT
