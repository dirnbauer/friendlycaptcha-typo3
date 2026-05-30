<?php

declare(strict_types=1);

namespace StudioMitte\FriendlyCaptcha;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Configuration
{
    public const DEFAULT_JS_PATH = 'EXT:friendlycaptcha_official/Resources/Public/JavaScript/lib/sdk@0.1.26-site.compat.min.js';

    protected string $siteKey = '';
    protected string $siteSecretKey = '';
    protected bool $useEuPuzzleEndpoint = false;
    protected string $verifyUrl = '';
    protected string $jsPath = '';
    protected bool $skipDevValidation = false;

    public function __construct(?Site $site = null)
    {
        if ($site === null) {
            $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
            if ($request instanceof ServerRequest) {
                $site = $request->getAttribute('site');
            }
        }
        if (!$site instanceof Site) {
            return;
        }
        /** @var array<string, mixed> $siteConfiguration */
        $siteConfiguration = $site->getConfiguration();
        $this->siteKey = $this->getStringValue($siteConfiguration, 'friendlycaptcha_site_key');
        $this->siteSecretKey = $this->getStringValue($siteConfiguration, 'friendlycaptcha_secret_key');
        $this->useEuPuzzleEndpoint = (bool)($siteConfiguration['friendlycaptcha_use_eu_puzzle_endpoint'] ?? false);
        $this->verifyUrl = $this->getStringValue($siteConfiguration, 'friendlycaptcha_verify_url');
        $this->jsPath = $this->getStringValue($siteConfiguration, 'friendlycaptcha_js_path');
        $this->skipDevValidation = (bool)($siteConfiguration['friendlycaptcha_skip_dev_validation'] ?? false);
    }

    public function isEnabled(): bool
    {
        return $this->siteKey !== '' && $this->siteSecretKey !== '' && $this->verifyUrl !== '' && !$this->hasSkipHeaderValidation();
    }

    public function getSiteKey(): string
    {
        return $this->siteKey;
    }

    public function getSiteSecretKey(): string
    {
        return $this->siteSecretKey;
    }

    public function useEuPuzzleEndpoint(): bool
    {
        return $this->useEuPuzzleEndpoint;
    }

    public function getVerifyUrl(): string
    {
        return $this->verifyUrl;
    }

    public function getFirstVerifyUrl(): string
    {
        $urls = GeneralUtility::trimExplode(',', $this->verifyUrl, true);
        return $urls[0] ?? '';
    }

    public function getJsPath(): string
    {
        return $this->jsPath ?: self::DEFAULT_JS_PATH;
    }

    public function hasSkipDevValidation(): bool
    {
        return Environment::getContext()->isDevelopment() && $this->skipDevValidation;
    }

    public function hasSkipHeaderValidation(): bool
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        $validationName = $_ENV['FRIENDLYCAPTCHA_SKIP_HEADER_VALIDATION'] ?? '';
        if (!is_string($validationName)) {
            return false;
        }
        if (!$request instanceof ServerRequest || strlen($validationName) < 30) {
            return false;
        }
        return $request->hasHeader('X-FriendlyCaptcha-Skip-Validation') && in_array($validationName, $request->getHeader('X-FriendlyCaptcha-Skip-Validation'), true);
    }

    /**
     * @param array<string, mixed> $siteConfiguration
     */
    private function getStringValue(array $siteConfiguration, string $key): string
    {
        $value = $siteConfiguration[$key] ?? '';
        return is_scalar($value) || $value instanceof \Stringable ? trim((string)$value) : '';
    }
}
