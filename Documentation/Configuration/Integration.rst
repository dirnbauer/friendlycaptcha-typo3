..  include:: /Includes.rst.txt
..  index:: Configuration
..  _configuration-integration:

===========
Integration
===========

The integration is configured in the *Sites* module.

Switch to the module *Site Management*/*Sites* and select the site that should
use Friendly Captcha.

A new tab **Friendly Captcha** is available which includes all configuration options.


..  figure:: /Images/configuration_site.png
    :class: with-shadow
    :alt: Integration in the Site module
    :width: 500px

    Integration in the Site module

..  note::
    After finishing the configuration, you are ready to use Friendly Captcha on your site.

    This is described in the :ref:`using` section!


The TYPO3 14 version stores all runtime settings in the site configuration.
Configure the site key, secret key, verify URL, JavaScript path, and the
development/test validation options there.

By default, the global verify endpoint
`https://global.frcapi.com/api/v2/captcha/siteverify` is used. If you prefer
the EU endpoint, enter `https://eu.frcapi.com/api/v2/captcha/siteverify` in
the `Verify URL` field and enable `Use EU Puzzle Endpoint`.

Working with automated tests
============================
Automated tests can skip external captcha verification by setting the
environment variable `FRIENDLYCAPTCHA_SKIP_HEADER_VALIDATION` to a string with
a minimum length of 30 characters.

Send the same string with the request header
`X-FriendlyCaptcha-Skip-Validation`. Use this only for automated tests and
never expose the value to untrusted clients.
