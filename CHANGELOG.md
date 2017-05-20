# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [Open]

### To Add

* add *hasFlags* to easy up validation if long or short flag is set (e.g. '-v|--verbose')

### To Change

* cover Parser with unit tests
* synchronize styling of the release dates

## [Unreleased]

### Added

### Changeed

## [1.4.1](https://github.com/bazzline/php_component_cli_argument/tree/1.4.1) - released at 2017-05-18

### Changed

* converted history into changelog

## [1.4.0](https://github.com/bazzline/php_component_cli_argument/tree/1.4.0) - released at 2017-01-29

### Changed

* updated minimum requirements to php 5.6

## [1.3.2](https://github.com/bazzline/php_component_cli_argument/tree/1.3.2) - released at 2016-08-07

### Changed

* updated to phpunit 5.5

## [1.3.1](https://github.com/bazzline/php_component_cli_argument/tree/1.3.1) - released at 2015-06-05

### Added

* added php 7.0 to testing environment

### Changed

* enhanced phpunit compatibility by using "~4.8||~5.4"
* moved to psr-4 autoloading
* removed php 5.3.3 testing environment
* updated to phpunit 5.4

## [1.3.0](https://github.com/bazzline/php_component_cli_argument/tree/1.3.0) - released at 2015-12-01

### Added

* added support for single argument like "a" or "-"

## [1.2.0](https://github.com/bazzline/php_component_cli_argument/tree/1.2.0) - released at 2015-11-28

### Added

* added *convertToArray()'
* added *convertToString()'
* added *getNumberOf[Arguments|Flags|Lists|Values]

### Changed

* removed *generate_api*
* replaced "setArguments" with "parseArguments" like [nette](https://github.com/nette/command-line/blob/master/src/CommandLine/Parser.php) is doing it
* updated dependency handling by being less restrictive (added lower limit for phpunit)

## [1.1.2](https://github.com/bazzline/php_component_cli_argument/tree/1.1.2) - released at 2015-11-07

### Changed

* updated dependencies

## [1.1.1](https://github.com/bazzline/php_component_cli_argument/tree/1.1.1) - released at 2015-08-18

### Changed

* updated dependencies

## [1.1.0](https://github.com/bazzline/php_component_cli_argument/tree/1.1.0) - released at 2015-07-02

### Changed

* added second argument to `Arguments::__construct()` and `Arguments::setArguments` called "removeFirstArgument"

## [1.0.2](https://github.com/bazzline/php_component_cli_argument/tree/1.0.2) - released at 2015-07-02

### Changed

* updated dependencies

## [1.0.1](https://github.com/bazzline/php_component_cli_argument/tree/1.0.1) - released at 2015-05-22

### Changed

* updated dependencies

## [1.0.0](https://github.com/bazzline/php_component_cli_argument/tree/1.0.0) - released at 2015-04-23

### Added

* initial release
