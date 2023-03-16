# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.7] - 2020-08-12
### Added
- Add External links translation on iframe
- Add brazilian portuguese

## [1.0.6] - 2020-07-07
### Added
- Add External links translation

### Changed
- Simplify Regex for default regex checker (HTML template and JSON LD)

### Fixed
- Fix a rare bug with comments inside nodes

## [1.0.5] - 2020-04-14
### Added
- Add callback mechanism on regex checker
- Add support for array in JSON
- Add a type 9 for title tag

### Fixed
- Fix a rare bug when some empty nodes
- Fix error when sentence is split because of comment

## [1.0.3] - 2019-12-16
### Changed
- Fixed formatter on JSON source, only apply previous change to TEXT regex

## [1.0.2] - 2019-12-16
### Changed
- Fixed formatter on text regex when it contain '#'

## [1.0.1] - 2019-12-03
### Changed
- Fixed formatter on text regex to only take word

## [0.5.21] - 2019-03-12
### Added
- Util Regex for excluded paths
### Changed
- Update translation engine

## [0.5.12] - 2018-09-19
Add escape property on dom checker

## [0.5.11] - 2018-07-31
### Changed
Better API errors management

## [0.5.10] - ????-??-??
???

## [0.5.9] - 2018-07-19
### Changed
- Cleaning tags html entities before rendering
- Fix URL and infinite loop bug

## [0.5.8] - 2018-06-26
### Changed
- Simplify code and fix all ignore nodes related problems
### Fixed
- Forgot noscript & code in Text checker

## [0.5.7] - 2018-06-12
### Changed
- Client.HttpClient: Using new header system (Weglot-Context)
- Client.Profile: Setting ignoredNodes behavior as default one
### Fixed
- Parser: fixes some checkers (Check.Placeholder / Check.Text) & fixing AbstractDomChecker in checkers list
- Parser.ignoredNodes: Fixing replacement method to allow more content (class or other attributes) in skipped tags

## [0.5.6] - 2018-06-08
### Fixed
- Factory.Translate: fixing wrong assignation

## [0.5.5] - 2018-06-08
### Fixed
- Factory.Translate: fixing issue where PHP can returns an array not in same order as the keys are

## [0.5.4] - 2018-06-05
### Fixed
- Util.Url: missing getDefault function

## [0.5.3] - 2018-06-05
### Added
- Util.Url: adding query managment
### Changed
- Better exceptions for Translate endpoint
- Improving Util.Url structure
- Better travis tests (with low deps for PHP 5.4)
### Fixed
- Unit tests where break due to no trailing slash

## [0.5.2] - 2018-05-31
### Fixed
- Util.Url: nested Url prefix was in baseUrl

## [0.5.1] - 2018-05-28
### Fixed
- Util.Url: Adding Port managment

## [0.5.0] - 2018-05-28
### Added
- Util.Url: `getForLanguage($code)` function to get given language url base on current `Url` instance
- Api.Languages: adding data for RTL (right to left) languages and access in API objects
### Changed
- Util.Url: Local caching for `currentRequestAllUrls()` function
- Updating `composer.json`

## [0.4.2] - 2018-05-21
### Fixed
- Util.Url: handling case where prefix path has trailing slash
### Added
- Tests: Unit tests for Util.Url to check when prefix path as trailing slash

## [0.4.1] - 2018-05-15
### Fixed
- Util.Url: fixing error when path is also host root

## [0.4.0] - 2018-05-15
### Added
- Util.Url: implementing Url utility class ! with all unit tests needed
- Tests: adding unit tests for Util\JsonLd class

## [0.3.1] - 2018-05-11
### Changed
- Translate: when response if not array, we throw an ApiError
### Fixed
- Parser: if simple_html_dom can't parse the HTML, we return raw HTML with no modifications

## [0.3] - 2018-05-07
### Added
- Client: Adding bundled CA cert for cURL
### Changed
- Travis: Improving script (cleaner / easier to understand)
- Client: Guzzle removed in favor of cURL implementation

## [0.2.2] - 2018-05-07
### Fixed
- Translate/Cache: Cached words aren't unique anymore (before, only one pair could work)

## [0.2.1] - 2018-04-30
### Fixed
- Parser: Excluding php tags from text checker

## [0.2] - 2018-04-26
### Added
- Util to fetch Site contents through `Site::get`
- cURL & OpenSSL versions inside user-agent for debugging purposes

### Changed
- Parser: excludeBlocks formatter is started only if we have excludeBlocks
- Parser: adding DomCheckerProvider to Parser. Like that people can add their own Checkers
- README: updated getting started to use Parser example, and adding documentation
- Caching: Improving granularity, we use cache on Endpoints to be able to cache words by words and not the full request
- Travis-CI: PHP 5.5 is no more in failures but don't run all caching tests
- CodeClimate: improving maintainability

## [0.1.2] - 2018-04-19
### Fixed
- \#7 : Parser - dynamic tooltip hover a link

## [0.1.1] - 2018-04-18
### Fixed
- ServerConfigProvider behavior changed: we do not load $_SERVER in __construct, it can disturb symfony service loading

## [0.1] - 2018-04-16
### Added
- CodeClimate configuration
- Travis configuration
- Manage API versions (through Profile class)
- Error abstraction
- Unit testing

### Changed
- Refactoring for `Parser`
- Languages endpoint: now giving full list

## [0.1-beta.2] - 2018-04-12
### Added
- examples & descriptions:
  - cached-client-translate
  - parsing-web-page
  - simple-client-languages
  - simple-client-status
  - simple-client-translate
- adding README, LICENCE, CODE_OF_CONDUCT, CONTRIBUTING and github templates

## [0.1-beta.1] - 2018-04-11
### Added
- first Client version
- first endpoints:
  - Translate Endpoint
  - Status Endpoint
  - Languages Endpoint
- client Caching through PSR-6
- importing old Parser with some refactoring
