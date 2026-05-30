<?php

declare(strict_types=1);

namespace StudioMitte\FriendlyCaptcha\Tests\Unit\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\Attributes\Test;
use Psr\Log\NullLogger;
use StudioMitte\FriendlyCaptcha\Service\Api;
use StudioMitte\FriendlyCaptcha\Tests\RequestTrait;
use TYPO3\CMS\Core\Http\Client\GuzzleClientFactory;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\TestingFramework\Core\BaseTestCase;

#[AllowMockObjectsWithoutExpectations]
class ApiTest extends BaseTestCase
{
    use RequestTrait;

    #[Test]
    public function verifyUrlIsCalledWithProperData(): void
    {
        self::setupRequest();
        $GLOBALS['TYPO3_REQUEST'] = self::getRequest()
            ->withParsedBody(['frc-captcha-response' => '1234']);
        $client = $this->createClient([new Response(200, [], '{"success": true}')]);

        $factory = new RequestFactory(new GuzzleClientFactory());
        $api = new Api($factory, $client, new NullLogger());
        self::assertTrue($api->verify());
    }

    #[Test]
    public function solutionIsRetrieved(): void
    {
        self::setupRequest();
        $mockedApi = $this->getAccessibleMock(Api::class, null, [], '', false);

        self::assertSame('', $mockedApi->_call('getResponseFromRequest'));

        $GLOBALS['TYPO3_REQUEST'] = self::getRequest()
            ->withQueryParams(['frc-captcha-response' => '12345']);
        self::assertSame('12345', $mockedApi->_call('getResponseFromRequest'));

        $GLOBALS['TYPO3_REQUEST'] = self::getRequest()
            ->withParsedBody(['frc-captcha-response' => '1234']);
        self::assertSame('1234', $mockedApi->_call('getResponseFromRequest'));
    }

    /**
     * @param array<int, Response> $responses
     */
    private function createClient(array $responses): Client
    {
        $handlerStack = HandlerStack::create(
            new MockHandler([
                ...$responses,
            ])
        );
        return new Client(['handler' => $handlerStack]);
    }
}
