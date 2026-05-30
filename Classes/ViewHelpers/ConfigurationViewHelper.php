<?php

declare(strict_types=1);

namespace StudioMitte\FriendlyCaptcha\ViewHelpers;

use StudioMitte\FriendlyCaptcha\Configuration;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ConfigurationViewHelper extends AbstractViewHelper
{
    /**
     * @return array{siteKey: string, verifyUrl: string, useEuPuzzleEndpoint: bool, jsPath: string, enabled: bool}
     */
    public function render(): array
    {
        $configuration = new Configuration();
        return [
            'siteKey' => $configuration->getSiteKey(),
            'verifyUrl' => $configuration->getVerifyUrl(),
            'useEuPuzzleEndpoint' => $configuration->useEuPuzzleEndpoint(),
            'jsPath' => $configuration->getJsPath(),
            'enabled' => $configuration->isEnabled(),
        ];
    }
}
