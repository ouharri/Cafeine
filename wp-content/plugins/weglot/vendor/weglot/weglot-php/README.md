<!-- logo -->
<img src="https://cdn.weglot.com/logo/logo-hor.png" height="40" />

# PHP library

<!-- tags -->
[![WeglotSlack](https://weglot-community.now.sh/badge.svg)](https://weglot-community.now.sh/)
[![Latest Stable Version](https://poser.pugx.org/weglot/weglot-php/v/stable)](https://packagist.org/packages/weglot/weglot-php)
[![BuildStatus](https://travis-ci.com/weglot/weglot-php.svg?branch=develop)](https://travis-ci.com/weglot/weglot-php)
[![Code Climate](https://codeclimate.com/github/weglot/weglot-php/badges/gpa.svg)](https://codeclimate.com/github/weglot/weglot-php)
[![License](https://poser.pugx.org/weglot/weglot-php/license)](https://packagist.org/packages/weglot/weglot-php)

## Overview
This library allows you to quickly and easily use the Weglot API via PHP. It handle all communication with Weglot API and gives you a [fully functional Parser](#getting-started) to handle HTML pages easily.

## Requirements
- PHP version 5.6 and later
- Weglot API Key, starting at [free level](https://dashboard.weglot.com/register?origin=9)

## Installation
You can install the library via [Composer](https://getcomposer.org/). Run the following command:

```bash
composer require weglot/weglot-php
```

To use the library, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once __DIR__. '/vendor/autoload.php';
```

## Getting Started

Simple usage of `Parser`:

```php
// Url to parse
$url = 'https://foo.bar/baz';

// Config with $_SERVER variables
$config = new ServerConfigProvider();

// Fetching url content
$content = '...';

// Client
$client = new Client(getenv('WG_API_KEY'));
$parser = new Parser($client, $config);

// Run the Parser
$translatedContent = $parser->translate($content, 'en', 'de');
```

For more details, check at [corresponding example](./examples/parsing-web-page/run.php) or at [documentation](https://weglot.github.io/documentation/#parser).

## Examples

For more usage examples, such as: other endpoints, caching, parsing.

You can take a look at: [examples](./examples) folder. You'll find a short README with details about each example.

## Reference

### Client

The Client is all the classes related with communication with the Weglot API

#### Client & Profile

`Weglot\Client\Client` is the main class of this library.
Basically it manage requests, and that's all.

With this class we have `Weglot\Client\Profile` which represent particularities based on API Key length.
Today we have 2 types of API Keys:
- 35-char API Keys: Normal API Keys with no custom functions enabled.
- 36-char API Keys: Adding `ignoredNodes` behavior, basically we skip some tags from being parsed as sentences (such as `strong`, `em`, ...) to make bigger sentences.

#### API

Includes all the objects to talk with the API (as input or output).
- `Weglot\Client\Api\WordEntry`: Define a single sentence within an API Object
- `Weglot\Client\Api\WordCollection`: Define multiple `WordEntry`
- `Weglot\Client\Api\LanguageEntry`: Define a single language within an API Object
- `Weglot\Client\Api\LanguageCollection`: Define multiple `LanguageEntry`
- `Weglot\Client\Api\TranslateEntry`: Define a translate interface to use as input/output of `/translate` endpoint

Here is some quick example of using some of theses:
```php
// creating some WordEntry objects
$firstWord = new WordEntry('This is a blue car', WordType::TEXT);
$secondWord = new WordEntry('This is a black car', WordType::TEXT);

// then create our TranslateEntry object to use later with /translate
$translateEntry = new TranslateEntry([
    'language_from' => 'en',
    'language_to' => 'de',
    'title' => 'Weglot | Translate your website - Multilingual for WordPress, Shopify, ...',
    'request_url' => 'https://weglot.com/',
    'bot' => BotType::HUMAN
]);
$translateEntry->getInputWords()->addMany([$firstWord, $secondWord]);
```

Like you just saw, you can find quick Enums to set API-related data easier, like theses:
- `Weglot\Client\Api\Enum\WordType`: Used to provide context over where the text we wish to translate comes from.
- `Weglot\Client\Api\Enum\BotType`: Used to define the source of a request.

#### Caching

We manage Cache with one main interface: `Weglot\Client\Caching\CacheInterface` it use [PSR-6 RFC](https://www.php-fig.org/psr/psr-6/) internally.

We added one implementation of this interface within the `Weglot\Client\Caching\Cache` class which takes a PSR-6 `CacheItemPoolInterface` as store.
You can set this one from a Client method as following:
```php
$client = new Client('wg_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
$client->setCacheItemPool($myCacheItemPool);
```
The given `$myCacheItemPool` can be any PSR-6 compliant object. I suggest to consult [php-cache](http://www.php-cache.com/en/latest/#cache-pool-implementations) cache pool implementations that should contains the library you need to plug your cache.

#### Endpoint

Here are the class to talk with API endpoints. Today we have 3 of them:
- `Weglot\Client\Endpoint\Translate`: Used to make requests with the `/translate` endpoint
- `Weglot\Client\Endpoint\Status`: Used to make requests with the `/status` endpoint
- `Weglot\Client\Endpoint\LanguagesList`: Which is used to get all languages-related data from a "fake" API

Following our example from API Objects, here is how to use `Translate` class:
```php
$client = new Client('wg_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
$translateEntry = new TranslateEntry(...); // Check API part for more details about this one

$translate = new Translate($translateEntry, $client);
$translated = $translate->handle();
```
Following this example, the `$translated` object will contain a `TranslateEntry` object with all data returned from the API.
You can find [a full working example here](./examples/simple-client-translate/)

#### Factory

Theses are classes used between the endpoints classes and the returned API object.
It handles all conversion from the API that return JSON to the well formated API object.

No example here since it's only used internally.

#### HttpClient

Another internal set of classes.

Theses are composed by a simple interface to manage requests: `Weglot\Client\HttpClient`
And an implementation with cURL as the HTTP provider: `Weglot\Client\CurlClient` (this class is actually heavily inspired by Stripe's CurlClient)

### Parser

The Parser is a big part in our developer kits.

It's used to match sentences to translate from DOM and to make clean API objects to send them after through the Client.

There is no documentation for the Parser at the moment since we plan a heavy rework in next month on it, we'll make sure there is one after this rework.

### Util

There is some short classes to manage simple utilities such as:

#### Weglot\Util\JsonLd
Manage all actions related to recover & storing data in JsonLd structures

#### Weglot\Util\Server
All $_SERVER related utilities

```php
$fullUrl = Server::fullUrl($_SERVER);
// will return the current url seen by $_SERVER, for example: https://weglot.com/es/pricing
```

#### Weglot\Util\Site
Used to get contents from a distant website

#### Weglot\Util\Text
All classic Text utilities such as `contains()`

#### Weglot\Util\Url

And we've `Weglot\Util\Url` which is one of the cornerstone of url managment in our library. By fetching current url and Weglot configuration, it can serve several purposes:
- Know which language we have on the current url
- Generate urls for other languages
- Know if current url is translable or not (based on excludedUrls)
- Get all translated urls based on current url
- Generate hreflang tags based on all translated urls

Here is some quick examples:
```php
$url = new Url('https://weglot.com/es/pricing', 'en', ['fr', 'es', 'de']);

$currentLang = $url->detectCurrentLanguage();
// $currentLang will contain 'es'

$frUrl = $url->getForLanguage('fr');
// $frUrl will contain 'https://weglot.com/fr/pricing'

$translable = $url->isTranslable();
// $translable will contain true since we have no excluded urls

$url->setExcludedUrls(['/pricing']);
$translable = $url->isTranslable();
// $translable will contain false since we added `/pricing` to the excluded urls

$urls = $url->currentRequestAllUrls();
/**
 * $urls will contain following array:
 * Array(
 *   'en' => 'https://weglot.com/pricing',
 *   'fr' => 'https://weglot.com/fr/pricing',
 *   'es' => 'https://weglot.com/es/pricing',
 *   'de' => 'https://weglot.com/de/pricing'
 * );
 */

$hreflang = $url->generateHrefLangsTags()
/**
 * $hreflang will contain following string:
 * <link rel="alternate" href="https://weglot.com/pricing" hreflang="en"/>
 * <link rel="alternate" href="https://weglot.com/fr/pricing" hreflang="fr"/>
 * <link rel="alternate" href="https://weglot.com/es/pricing" hreflang="es"/>
 * <link rel="alternate" href="https://weglot.com/de/pricing" hreflang="de"/>
 */
```

##Tests
Only parser
`./vendor/codeception/codeception/codecept run -g parser`

Full test
`./vendor/codeception/codeception/codecept run`

## About
`weglot-php` is guided and supported by the Weglot Developer Team.

`weglot-php` is maintained and funded by Weglot SAS.
The names and logos for `weglot-php` are trademarks of Weglot SAS.

## License
[The MIT License (MIT)](LICENSE.txt)
