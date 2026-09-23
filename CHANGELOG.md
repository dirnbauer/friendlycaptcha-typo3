# Changelog of the fork

This file lists the releases of [dirnbauer/friendlycaptcha-typo3](https://github.com/dirnbauer/friendlycaptcha-typo3).
Upstream's own changes are in the
[upstream releases](https://github.com/studiomitte/friendlycaptcha-typo3/releases).

## 2.3.0.1 - 2026-09-23

Merges upstream 2.3.0, which supports TYPO3 14.3 itself, and reduces the fork to
what upstream still lacks.

### Kept

- `PowermailValidator` resolves Extbase lazy-loading proxies, reads the plugin
  FlexForm and the `action` argument defensively, and skips mails without a
  form instead of failing.
- `Configuration` and `Api` cope with a missing `TYPO3_REQUEST` and with
  non-string configuration values; `Api` type-hints the Guzzle client it calls.
- German translation of the site configuration labels (now in upstream's XLIFF
  format, keyed to upstream's source strings).
- TYPO3 14 style extension icons.

### Added

- `PowermailValidatorOnPowermail14Test`, which covers the cases above and fails
  against upstream's validator.
- PHPStan level 8 over `Classes/` and the fork's tests (`phpstan.neon`,
  `runTests.sh -s phpstan`, a step in the TYPO3 14 workflow).
- The dev requirement on `in2code/powermail` resolves to dirnbauer/powermail on
  TYPO3 14, so the Powermail tests run there instead of being skipped.

### Removed, because upstream 2.3.0 covers it or it was never needed

- The TYPO3 14-only port. TYPO3 12.4 and 13.4 are supported again, as upstream
  supports them, and `ext_emconf.php` is back.
- The `studiomitte/friendlycaptcha` site set. Upstream 2.3.0 adds the Powermail
  TypoScript and page TSconfig itself; the static template and the page
  TSconfig include option are back.
- The rewritten documentation, the XLIFF 2.0 conversion with reworded labels,
  and a copied DDEV Solr add-on.
- The `v14` branch: `main` is the only maintained branch.

## 14.0.0 - 2026-05-29 (branch `v14`, superseded by 2.3.0.1)

The fork's own TYPO3 14 port, made before upstream supported TYPO3 14: TYPO3
14.3 and PHP 8.3 to 8.5 only, TypoScript and page TSconfig in a site set,
PHPStan at level max, PHPUnit 12 and testing-framework 9.
