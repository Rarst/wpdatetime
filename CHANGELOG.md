# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Unreleased

## 0.3.1 - 2019-05-27

### Fixed
- test issue with summer time
- incorrect conversion for -00:30 time zone

## 0.3 - 2018-03-16

### Changed
- directory structure to PDS skeleton

### Fixed
- incorrect timestamp `U` and Swatch time `B` formats when returned by `date_i18n()`

## 0.2.2 - 2018-02-01

### Changed
- updated unit tests to PHPUnit 6.

### Fixed
- setting timezone on immutable instance created from UTC post time.

## 0.2.1 - 2017-12-28

### Fixed
- Math for converting negative GMT offset values. 

## 0.2 - 2017-11-06

### Added
- Helper `formatDate()` and `formatTime()` methods for quick output in current WP configuration formats. 

### Changed
- Updated tests and Brain Monkey to version 2.1.

### Fixed
- Overrode upstream methods to return instances of inherited type.
- Instance `static` (not `self`) for correct behavior on subclassing.

## 0.1 - 2016-11-15

### Added
- Initial release.