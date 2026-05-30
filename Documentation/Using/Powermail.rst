..  include:: /Includes.rst.txt
..  highlight:: php

..  _using-powermail:

=============
EXT:powermail
=============

Friendly Captcha can be used in EXT:powermail after a public TYPO3 14
compatible Powermail release is available. The integration files are included
in this extension, but `in2code/powermail` is not required by Composer until a
compatible public release exists.

Setup
-----

The TYPO3 site must depend on the Friendly Captcha site set.

Add the site set
~~~~~~~~~~~~~~~~

Add the set in the site configuration:

..  code-block:: yaml
    :caption: config/sites/<site-identifier>/config.yaml

    dependencies:
      - studiomitte/friendlycaptcha

The set loads `Configuration/Sets/Friendlycaptcha/setup.typoscript` and
`Configuration/Sets/Friendlycaptcha/page.tsconfig`.

Do not add a static TypoScript include or manual Page TSconfig include for
TYPO3 14. The site set is the supported setup path.

Usage
-----

After setup, select Friendly Captcha as a field type in a Powermail field
record.

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
