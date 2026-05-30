<?php

declare(strict_types=1);

namespace In2code\Powermail\Domain\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator as ExtbaseAbstractValidator;

abstract class AbstractValidator extends ExtbaseAbstractValidator
{
    /**
     * @var array<string, array<string, array<string, array<string, string>>>>
     */
    protected array $flexForm = [
        'settings' => [
            'flexform' => [
                'main' => [
                    'confirmation' => '0',
                    'optin' => '0',
                ],
            ],
        ],
    ];
}
