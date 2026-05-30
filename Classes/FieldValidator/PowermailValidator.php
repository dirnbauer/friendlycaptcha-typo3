<?php

declare(strict_types=1);

namespace StudioMitte\FriendlyCaptcha\FieldValidator;

use In2code\Powermail\Domain\Model\Mail;
use In2code\Powermail\Domain\Validator\AbstractValidator;
use StudioMitte\FriendlyCaptcha\Service\Api;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PowermailValidator extends AbstractValidator
{
    public function isValid(mixed $mail): void
    {
        if (!$mail instanceof Mail) {
            return;
        }
        if (!$this->isFormWithCaptchaField($mail) || $this->isCaptchaCheckToSkip()) {
            return;
        }

        $friendlyCaptchaService = GeneralUtility::makeInstance(Api::class);
        if (!$friendlyCaptchaService->verify()) {
            $this->addError(
                $this->translateErrorMessage('message.invalid', 'friendlycaptcha_official'),
                1689157219,
            );
        }
    }

    protected function isFormWithCaptchaField(Mail $mail): bool
    {
        foreach ($mail->getForm()->getPages() as $page) {
            foreach ($page->getFields() as $field) {
                if ($field->getType() === 'friendlycaptcha') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Captcha check should be skipped on createAction if there was a confirmationAction where the captcha was
     * already checked before
     * Note: $this->flexForm is only available in powermail 3.9 or newer
     */
    protected function isCaptchaCheckToSkip(): bool
    {
        $action = $this->getActionName();
        $confirmationActive = ($this->flexForm['settings']['flexform']['main']['confirmation'] ?? '0') === '1';
        $optinActive = ($this->flexForm['settings']['flexform']['main']['optin'] ?? '0') === '1';
        if (($action === 'create' || $action === 'checkCreate') && $confirmationActive) {
            return true;
        }

        if ($action === 'optinConfirm' && $optinActive) {
            return true;
        }

        return false;
    }

    /**
     * @return string "confirmation" or "create"
     */
    protected function getActionName(): string
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if (!$request instanceof ServerRequest) {
            return '';
        }
        $queryParams = $request->getQueryParams();
        $pluginVariables = $queryParams['tx_powermail_pi1'] ?? [];
        if (!is_array($pluginVariables)) {
            $pluginVariables = [];
        }

        $requestBody = $request->getParsedBody();
        $postVariables = [];
        if (is_array($requestBody) && isset($requestBody['tx_powermail_pi1'])) {
            $postVariables = $requestBody['tx_powermail_pi1'];
            if (!is_array($postVariables)) {
                $postVariables = [];
            }
        }

        ArrayUtility::mergeRecursiveWithOverrule($pluginVariables, $postVariables);
        $action = $pluginVariables['action'] ?? '';
        return is_scalar($action) || $action instanceof \Stringable ? (string)$action : '';
    }
}
