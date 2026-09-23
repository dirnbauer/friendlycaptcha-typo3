<?php

declare(strict_types=1);

namespace StudioMitte\FriendlyCaptcha\Tests\Unit\FieldValidator;

use In2code\Powermail\Domain\Model\Field;
use In2code\Powermail\Domain\Model\Form;
use In2code\Powermail\Domain\Model\Mail;
use In2code\Powermail\Domain\Model\Page;
use PHPUnit\Framework\Attributes\Test;
use StudioMitte\FriendlyCaptcha\FieldValidator\PowermailValidator;
use StudioMitte\FriendlyCaptcha\Service\Api;
use StudioMitte\FriendlyCaptcha\Tests\RequestTrait;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\TestingFramework\Core\BaseTestCase;

/**
 * Fork regression tests: the situations Powermail 14 creates in which the
 * upstream validator either throws or skips the captcha check.
 */
final class PowermailValidatorOnPowermail14Test extends BaseTestCase
{
    use RequestTrait;

    protected function setUp(): void
    {
        if (!\Composer\InstalledVersions::isInstalled('in2code/powermail')) {
            self::markTestSkipped('in2code/powermail is not installed.');
        }
        parent::setUp();
        self::setupRequest();
    }

    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        unset($GLOBALS['TYPO3_REQUEST']);
        parent::tearDown();
    }

    #[Test]
    public function captchaFieldBehindLazyLoadingProxiesIsFound(): void
    {
        $this->withPostedAction('create');
        $form = $this->createFormWithFieldType('friendlycaptcha');
        $formProxy = $this->createMock(LazyLoadingProxy::class);
        $formProxy->expects(self::once())->method('_loadRealInstance')->willReturn($form);
        $mail = self::createConfiguredStub(Mail::class, ['getForm' => $formProxy]);

        $this->expectVerification(false);
        $validator = $this->createValidator();
        $validator->expects(self::once())->method('addError')->with('a translation', 1689157219);

        $validator->_call('isValid', $mail);
    }

    #[Test]
    public function missingPluginFlexFormDoesNotBreakValidation(): void
    {
        // Powermail 14 leaves $flexForm empty when the plugin has no FlexForm,
        // for example when the form is rendered through TypoScript.
        $this->withPostedAction('create');
        $mail = self::createConfiguredStub(Mail::class, ['getForm' => $this->createFormWithFieldType('friendlycaptcha')]);

        $this->expectVerification(true);
        $validator = $this->createValidator();
        $validator->expects(self::never())->method('addError');

        $validator->_call('isValid', $mail);
    }

    #[Test]
    public function missingActionParameterDoesNotBreakValidation(): void
    {
        // Upstream returns $pluginVariables['action'] from a string method,
        // which is a TypeError when the action is not a request parameter.
        $mail = self::createConfiguredStub(Mail::class, ['getForm' => $this->createFormWithFieldType('friendlycaptcha')]);

        $this->expectVerification(true);
        $validator = $this->createValidator();
        $validator->expects(self::never())->method('addError');

        $validator->_call('isValid', $mail);
    }

    #[Test]
    public function mailWithoutFormSkipsTheCheck(): void
    {
        $this->withPostedAction('create');
        $mail = self::createConfiguredStub(Mail::class, ['getForm' => null]);

        $api = $this->getAccessibleMock(Api::class, ['verify'], [], '', false);
        $api->expects(self::never())->method('verify');
        GeneralUtility::addInstance(Api::class, $api);
        $validator = $this->createValidator();
        $validator->expects(self::never())->method('addError');

        $validator->_call('isValid', $mail);
    }

    private function withPostedAction(string $action): void
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        self::assertInstanceOf(ServerRequest::class, $request);
        $GLOBALS['TYPO3_REQUEST'] = $request->withParsedBody(['tx_powermail_pi1' => ['action' => $action]]);
    }

    private function expectVerification(bool $result): void
    {
        $api = $this->getAccessibleMock(Api::class, ['verify'], [], '', false);
        $api->expects(self::once())->method('verify')->willReturn($result);
        GeneralUtility::addInstance(Api::class, $api);
    }

    /**
     * @return PowermailValidator&\PHPUnit\Framework\MockObject\MockObject&\TYPO3\TestingFramework\Core\AccessibleObjectInterface
     */
    private function createValidator(): PowermailValidator
    {
        $validator = $this->getAccessibleMock(PowermailValidator::class, ['addError', 'translateErrorMessage'], [], '', false);
        $validator->method('translateErrorMessage')->willReturn('a translation');
        return $validator;
    }

    private function createFormWithFieldType(string $fieldType): Form
    {
        $field = self::createConfiguredStub(Field::class, ['getType' => $fieldType]);
        $fields = new ObjectStorage();
        $fields->attach($field);
        $page = self::createConfiguredStub(Page::class, ['getFields' => $fields]);

        $pages = new ObjectStorage();
        $pages->attach($page);
        return self::createConfiguredStub(Form::class, ['getPages' => $pages]);
    }
}
