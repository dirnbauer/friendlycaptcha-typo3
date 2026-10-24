<?php

declare(strict_types=1);

namespace StudioMitte\FriendlyCaptcha\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use StudioMitte\FriendlyCaptcha\Configuration;
use StudioMitte\FriendlyCaptcha\Tests\RequestTrait;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\TestingFramework\Core\BaseTestCase;

class ConfigurationTest extends BaseTestCase
{
    use RequestTrait;

    #[Test]
    public function defaultJavascriptUsesThePinnedSdk(): void
    {
        self::setupRequest();
        $configuration = new Configuration();
        $package = json_decode(file_get_contents(dirname(__DIR__, 2) . '/package.json'), true, 512, JSON_THROW_ON_ERROR);
        $filename = 'sdk@' . $package['devDependencies']['@friendlycaptcha/sdk'] . '-site.compat.min.js';

        self::assertSame('EXT:friendlycaptcha_official/Resources/Public/JavaScript/lib/' . $filename, $configuration->getJsPath());
        self::assertFileExists(dirname(__DIR__, 2) . '/Resources/Public/JavaScript/lib/' . $filename);
    }

    #[Test]
    public function explicitlyConfiguredJavascriptPathTakesPrecedence(): void
    {
        $configuration = new Configuration(new Site('custom', 1, ['friendlycaptcha_js_path' => 'EXT:site/Resources/Public/captcha.js']));
        self::assertSame('EXT:site/Resources/Public/captcha.js', $configuration->getJsPath());
    }

    #[Test]
    public function configurationIsBuiltCorrectly(): void
    {
        self::setupRequest('12345', 'ABCDEF');

        $configuration = new Configuration();
        self::assertEquals('12345', $configuration->getSiteKey());
        self::assertEquals('ABCDEF', $configuration->getSiteSecretKey());
        self::assertFalse($configuration->useEuPuzzleEndpoint());
        self::assertEquals('https://verify,https://verify2', $configuration->getVerifyUrl());
        self::assertEquals('https://verify', $configuration->getFirstVerifyUrl());
        self::assertTrue($configuration->isEnabled());
    }

    #[Test]
    public function configurationIsNotEnabledWithMissingValue(): void
    {
        self::setupRequest('12345', '');

        $configuration = new Configuration();
        self::assertFalse($configuration->isEnabled());
    }
}
