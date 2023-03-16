# weglot-translation-definitions

## Project structure

- The `data` folder is meant to only contain data files (preferrably JSON)
- The root folder can contain files mean to

The project may itself contain test files and be subject to CI validation in the future, to test for the integrity of the data files.

## Javascript

### Publish a new version

You can run `yarn version` and select a new version. Alternatively, run `yarn version --{type}` where type=(major|minor|patch).
This will update both the `package.json`, create a git tag following the standard `v1.0.0` for version `1.0.0`, and push that tag to origin.

### Reference this package in other projects

Check which version you'd like to use if you don't want to use the last version (master branch). Add an entry in the dependencies:

```
{
  "weglot-translation-definitions": "weglot/weglot-translation-definitions#v1.1.0"
}

```

You can also a command with the following structure:
`yarn add weglot/weglot-translation-definitions#v1.1.0`

## Routine scripts

### Update available languages

If you have HTTPie on your system, run the following command to update the list of available languages:

`http --pretty=format https://api.weglot.com/public/languages > data/available-languages.json`

You can also run `yarn update-languages`.

## PHP

### Add the dependency

In your `composer.json#repositories`, add the following entry:

```json
{
  "type": "vcs",
  "url": "https://github.com/weglot/weglot-translation-definitions"
}
```

In your `composer.json#require`, add the following line:

```json
"weglot/translation-definitions": "dev-master",
```

If you wish to use a specific version ([check available tags here](https://github.com/weglot/weglot-translation-definitions/tags)), just replace `master` with the tag name, like so:

```json
"weglot/translation-definitions": "dev-v1.3.2",
```
### Update the package

`composer update weglot/translation-definitions:dev-master`


### Usage

This assumes you've added the dependency through `composer.json`

```php

use Weglot\TranslationDefinitions;

print_r(TranslationDefinitions::$languages);
// Array
// (
//   [0] => Array
//     (
//       [value] => af
//       [label] => Afrikaans
//     )

//   [1] => Array
//     (
//       [value] => sq
//       [label] => Albanian
//     )
//  etc.

print_r(TranslationDefinitions::$cases);
// Array
// (
//   [v1] => Array
//     (
//       [0] => Array
//         (
//           [name] => Simple tag #1
//           [body] => <p>Hello, <b>this is</b> a test!</p>
//           [expected] => Array
//             (
//               [0] => Array
//                 (
//                   [t] => 1
//                   [w] => Hello,
//                 )

//               [1] => Array
//                 (
//                   [t] => 1
//                   [w] => this is
//                 )
//         etc.

print_r(TranslationDefinitions::$mergeNodesList);
// Array
// (
//   [v1] => Array
//     (
//     )

//   [v2] => Array
//     (
//       [0] => ABBR
//       [1] => ACRONYM
//       [2] => B
//       [3] => BDO
//       [4] => BIG
//       [5] => CITE
//       [6] => EM
//       [7] => I
//       [8] => KBD
//       [9] => Q
//  etc.
```
