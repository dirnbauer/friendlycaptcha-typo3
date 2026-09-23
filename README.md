# TYPO3 Extension `friendlycaptcha_official`

[![Total Downloads](https://poser.pugx.org/studiomitte/friendlycaptcha/downloads)](https://packagist.org/packages/studiomitte/friendlycaptcha)
[![TYPO3 12](https://img.shields.io/badge/TYPO3-12-orange.svg)](https://get.typo3.org/version/12)
[![TYPO3 13](https://img.shields.io/badge/TYPO3-13-orange.svg)](https://get.typo3.org/version/13)
[![TYPO3 14](https://img.shields.io/badge/TYPO3-14-orange.svg)](https://get.typo3.org/version/14)
[![License](https://poser.pugx.org/studiomitte/friendlycaptcha/license)](https://packagist.org/packages/studiomitte/friendlycaptcha)

[![Build 12](https://github.com/studiomitte/friendlycaptcha-typo3/actions/workflows/core12.yml/badge.svg)](https://github.com/studiomitte/friendlycaptcha-typo3/actions/workflows/core12.yml)
[![Build 13](https://github.com/studiomitte/friendlycaptcha-typo3/actions/workflows/core13.yml/badge.svg)](https://github.com/studiomitte/friendlycaptcha-typo3/actions/workflows/core13.yml)
[![Build 14](https://github.com/studiomitte/friendlycaptcha-typo3/actions/workflows/core14.yml/badge.svg)](https://github.com/studiomitte/friendlycaptcha-typo3/actions/workflows/core14.yml)

This extension integrations the GDPR-compliant captcha service of [**Friendly Captcha**](https://friendlycaptcha.com/) into TYPO3.

## Fork notes

This is webconsulting's fork of [studiomitte/friendlycaptcha-typo3](https://github.com/studiomitte/friendlycaptcha-typo3).
The Composer package name stays `studiomitte/friendlycaptcha`. Branch `main` is upstream `main` (currently 2.3.0) plus
a fix that upstream 2.3.0 lacks when it validates Powermail forms on TYPO3 14:

- `PowermailValidator` resolves Extbase lazy-loading proxies itself. Powermail 14 declares `Mail::$form`,
  `Form::$pages` and `Page::$fields` lazy. The validator also skips the check cleanly when a mail has no form, where
  upstream fails with "Call to a member function getPages() on null".
- It reads the plugin FlexForm and the `action` argument defensively. Powermail 14 leaves the validator's FlexForm
  empty when the plugin has none, for example when a form is rendered through TypoScript, and upstream then raises a
  warning for every missing key. A request without `tx_powermail_pi1[action]` makes upstream's `getActionName()`
  throw a `TypeError`.
- `Configuration` and `Api` cope with a missing `TYPO3_REQUEST` (CLI, scheduler, MCP writes) and with non-string
  configuration values. `Api` type-hints the Guzzle client whose `request()` method it calls.

`Tests/Unit/FieldValidator/PowermailValidatorOnPowermail14Test.php` covers these cases; run against upstream's
validator, it fails. Smaller additions:

- a German translation of the site configuration labels;
- TYPO3 14 style extension icons;
- PHPStan level 8 (`phpstan.neon`, `Build/Scripts/runTests.sh -s phpstan`);
- a dev requirement on `in2code/powermail` that resolves to [dirnbauer/powermail](https://github.com/dirnbauer/powermail)
  on TYPO3 14, so the Powermail tests run there instead of being skipped.

Installing the fork:

```json
{
    "repositories": [{"type": "vcs", "url": "https://github.com/dirnbauer/friendlycaptcha-typo3.git"}],
    "require": {"studiomitte/friendlycaptcha": "~2.3.0.1"}
}
```

Release tags have four parts, `<upstream version>.<fork revision>`: `2.3.0.1` is upstream 2.3.0 with the fork's
first revision on top. `~2.3.0.1` accepts only later fork revisions of 2.3.0, so no upstream tag can satisfy it,
whereas `^2.3` would accept any upstream 2.x tag that reached this repository. The former `v14` branch (required as
`^14.0@dev`) has been merged into `main` and is no longer maintained. The `upstream` remote is fetched with
`--no-tags`, and upstream changes are merged, never rebased.

Supported TYPO3 versions:

- 12.4 LTS
- 13.4 LTS
- 14.3 LTS

Supported form extensions:

- EXT:form
- EXT:powermail
- API to integrate it into your own plugins

### Installation
```console
composer require studiomitte/friendlycaptcha
```

Checkout the full documentation [Using Friendly Captcha](https://docs.typo3.org/p/studiomitte/friendlycaptcha/main/en-us/Using/Index.html) for all details.


## Credits

This extension was created by [Studio Mitte](https://studiomitte.com) with ♥.

[Find more TYPO3 extensions we have developed](https://www.studiomitte.com/loesungen/typo3) that provide additional features for TYPO3 sites.
