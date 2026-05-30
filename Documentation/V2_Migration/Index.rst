..  include:: /Includes.rst.txt

..  _v2_migration:

================================
Migration to Friendly Captcha V2
================================

If you are using an earlier version of the extension with **Friendly Captcha
V1**, migrate to **Friendly Captcha V2** before using the TYPO3 14 branch.

* Enable **V2** in your application at https://friendlycaptcha.com/.
* Update the extension to the TYPO3 14 compatible version.
* In the TYPO3 backend, open *Site Management*/*Sites* and switch to the
  **Friendly Captcha** tab.
* Configure **Use EU Puzzle Endpoint** if you want to use the EU puzzle
  endpoint.
* Configure **Verify URL** with
  `https://global.frcapi.com/api/v2/captcha/siteverify` or
  `https://eu.frcapi.com/api/v2/captcha/siteverify`.
* Keep or adjust **JavaScript Path**. The bundled default is
  `EXT:friendlycaptcha_official/Resources/Public/JavaScript/lib/sdk@0.1.26-site.compat.min.js`.

The TYPO3 14 branch also removes TYPO3 12 and TYPO3 13 support. Optional
Powermail TypoScript and Page TSconfig are loaded through the site set
`studiomitte/friendlycaptcha`.
