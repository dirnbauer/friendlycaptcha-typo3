..  include:: /Includes.rst.txt

..  _installation:

============
Installation
============

This extension is installed with Composer. Version |release| requires TYPO3
14.3 or later and PHP 8.3 to 8.5. TYPO3 12 and TYPO3 13 are no longer
supported by this branch.

..  note::
    To be able to use the extension, it is **required** to configure it after the installation.

    This is described in the :ref:`configuration` section!

Composer
========

Use Composer to install this extension:

..  code-block:: bash

    composer config repositories.friendlycaptcha vcs https://github.com/dirnbauer/friendlycaptcha-typo3
    composer require studiomitte/friendlycaptcha:"dev-v14"

The explicit `dev-v14` constraint installs the TYPO3 14 branch. Use this while
the public Packagist package still exposes older TYPO3 12/13 tags as stable
releases.

Site set
========

Include the site set `studiomitte/friendlycaptcha` in your site
configuration if you use the optional Powermail integration. The set loads
the extension TypoScript and Page TSconfig for the site.

The EXT:form integration is registered globally because the TYPO3 Form
backend module needs the YAML configuration without a page context.

No static TypoScript template include or manual Page TSconfig include is needed
for TYPO3 14. Use the site set instead.
