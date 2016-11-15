# WpDateTime — DateTime extension for WordPress

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

- `WpDateTime::createFromPost()` static method creates object instance from WordPress post. Time zone defaults to current WordPress setting.
- `WpDateTime->formatI18n()` method outputs formatted and localized date in object’s time zone.

#### Example

```php
use Rarst\WordPress\DateTime\WpDateTime;

$date = WpDateTime::createFromPost( get_post() );

echo sprintf(
	'Posted on: <time datetime="%s">%s</time>',
	$date->format( DATE_RFC3339 ),
	$date->formatI18n( get_option( 'date_format' ) )
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

var_dump( WpDateTimeZone::getWpTimezone()->getName() );
// string(11) "Europe/Kiev"
```

## Tests

Tests use [Brain Monkey](https://brain-wp.github.io/BrainMonkey/) (included in dependencies) and PHPUnit (not included).

```bash
composer install
phpunit
```

## License

MIT