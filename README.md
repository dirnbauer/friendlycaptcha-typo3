# TYPO3 Extension `friendlycaptcha_official`

[![Total Downloads](https://poser.pugx.org/studiomitte/friendlycaptcha/downloads)](https://packagist.org/packages/studiomitte/friendlycaptcha)
[![TYPO3 14](https://img.shields.io/badge/TYPO3-14-orange.svg)](https://get.typo3.org/version/14)
[![License](https://poser.pugx.org/studiomitte/friendlycaptcha/license)](https://packagist.org/packages/studiomitte/friendlycaptcha)

[![Build 14](https://github.com/dirnbauer/friendlycaptcha-typo3/actions/workflows/core14.yml/badge.svg)](https://github.com/dirnbauer/friendlycaptcha-typo3/actions/workflows/core14.yml)

This extension integrates the GDPR-compliant captcha service of [**Friendly Captcha**](https://friendlycaptcha.com/) into TYPO3.

Supported versions:

- TYPO3 14.3+
- PHP 8.3, 8.4, and 8.5

TYPO3 12 and TYPO3 13 are no longer supported by this branch.

Supported form extensions:

- EXT:form
- EXT:powermail, once a public TYPO3 14 compatible release is available
- API to integrate it into your own plugins

### Installation

```console
composer require studiomitte/friendlycaptcha
```

Configure the extension in the TYPO3 site configuration after installation.
The Friendly Captcha site configuration tab contains the site key, secret key,
verify URL, JavaScript path, and development/test validation options.

Include the `studiomitte/friendlycaptcha` site set in your TYPO3 site when you
use the optional Powermail integration. The set loads the Powermail TypoScript
and Page TSconfig. EXT:form is registered globally by TYPO3's Form backend and
does not need a site set include.

The development setup uses DDEV with PHP 8.4 and tests the TYPO3 14 branch
against PHP 8.3, 8.4, and 8.5. Static analysis runs with PHPStan at maximum
level.

Checkout the full documentation [Using Friendly Captcha](https://docs.typo3.org/p/studiomitte/friendlycaptcha/14-dev/en-us/Using/Index.html) for all details.


## Credits

This extension was created by [Studio Mitte](https://studiomitte.com) with ♥.

[Find more TYPO3 extensions we have developed](https://www.studiomitte.com/loesungen/typo3) that provide additional features for TYPO3 sites.
