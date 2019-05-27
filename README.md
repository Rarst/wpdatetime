# WpDateTime — DateTime extension for WordPress

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Rarst/wpdatetime/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Rarst/wpdatetime/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Rarst/wpdatetime/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Rarst/wpdatetime/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Rarst/wpdatetime/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Rarst/wpdatetime/?branch=master)
[![Total Downloads](https://poser.pugx.org/rarst/wpdatetime/downloads)](https://packagist.org/packages/rarst/wpdatetime)
[![Latest Stable Version](https://img.shields.io/packagist/v/rarst/wpdatetime.svg?label=version)](https://packagist.org/packages/rarst/wpdatetime)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/rarst/wpdatetime.svg)](https://packagist.org/packages/rarst/wpdatetime)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg)](https://github.com/php-pds/skeleton)

WpDateTime is an extension of PHP’s [`DateTime`](http://php.net/manual/en/class.datetime.php) and [`DateTimeZone`](http://php.net/manual/en/class.datetimezone.php) classes for WordPress context.

It makes it easy to instance time objects from WordPress posts and produce localized output with correct format and time zone handling.
 
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
