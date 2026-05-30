..  include:: /Includes.rst.txt

..  _installation:

============
Installation
============

This extension can be installed as most of all other extensions of TYPO3 too.
It requires TYPO3 14.3 or later and PHP 8.3 to 8.5.

..  note::
    To be able to use the extension, it is **required** to configure it after the installation.

    This is described in the :ref:`configuration` section!

Composer
========

Use composer to install this extension by using

..  code-block:: bash

    composer require studiomitte/friendlycaptcha

Site set
========

Include the site set `studiomitte/friendlycaptcha` in your site
configuration if you use the optional Powermail integration. The set loads
the extension TypoScript and Page TSconfig for the site.

The EXT:form integration is registered globally because the TYPO3 Form
backend module needs the YAML configuration without a page context.
