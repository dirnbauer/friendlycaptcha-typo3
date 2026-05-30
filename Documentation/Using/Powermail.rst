..  include:: /Includes.rst.txt
..  highlight:: php

..  _using-powermail:

=============
EXT:powermail
=============

Friendly Captcha can be used in EXT:powermail after a public TYPO3 14
compatible Powermail release is available.

Setup
-----

The TYPO3 site must depend on the Friendly Captcha site set.

Add the site set
~~~~~~~~~~~~~~~~

Add the set in the site configuration:

..  code-block:: yaml

    dependencies:
      - studiomitte/friendlycaptcha

The set loads `Configuration/Sets/Friendlycaptcha/setup.typoscript` and
`Configuration/Sets/Friendlycaptcha/page.tsconfig`.

Usage
-----

It is now possible to select Friendly Captcha as a field type in a powermail field record

..  figure:: /Images/integration/powermail-field.png
    :class: with-shadow
    :alt: Add Friendly Captcha to your form
    :width: 450px

    Add Friendly Captcha to your form

Result
------

The result should look like this:

..  figure:: /Images/integration/powermail-result.png
    :class: with-shadow
    :alt: Result
    :width: 450px

    Result
