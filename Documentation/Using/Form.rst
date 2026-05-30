..  include:: /Includes.rst.txt
..  highlight:: php

..  _using-form:

========
EXT:form
========

If the extension `form` is used, the Friendly Captcha integration is automatically available
in the section **Advanced Elements**.

..  figure:: /Images/integration/form.png
    :class: with-shadow
    :alt: Field "Friendly Captcha"
    :width: 450px

    Field "Friendly Captcha"

The EXT:form integration is registered globally for TYPO3 14 because the Form
backend module does not have a site context while loading its YAML
configuration. No site set include is required for EXT:form.

The automatically used template can be found at
`EXT:friendlycaptcha_official/Resources/Private/Form/Partials/Friendlycaptcha.html`.
