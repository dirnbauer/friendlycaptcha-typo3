..  include:: /Includes.rst.txt

..  _introduction:

============
Introduction
============

..  tip::

    Checkout the information and demo at https://friendlycaptcha.com/ before trying
    this extension.

..  _what-it-does:

What does it do?
================

This extension integrates **Friendly Captcha** into TYPO3 by using the
official API. Version 14 supports TYPO3 14.3 or later and PHP 8.3 to 8.5.
It only supports **Friendly Captcha V2**.

The TYPO3 14 branch is Composer-only and no longer ships an `ext_emconf.php`
file. The extension version is provided by the Composer metadata as `14-dev`.
TYPO3 12 and TYPO3 13 support was removed.

Currently it supports the following form solutions:

* System extension "form" (`typo3/cms-form`)
* Community extension "powermail" (`in2code/powermail`), once a public
  TYPO3 14 compatible release is available

Additionally the extension can be used in your own extensions.

..  _screenshots:

Screenshots
===========

..  figure:: /Images/demo_official.png
    :class: with-shadow
    :alt: Official demo of Friendly Captcha
    :width: 450px

    Official demo of Friendly Captcha
