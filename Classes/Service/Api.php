<?php

declare(strict_types=1);

namespace StudioMitte\FriendlyCaptcha\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;
use StudioMitte\FriendlyCaptcha\Configuration;
use TYPO3\CMS\Core\Http\ServerRequest;

class Api
{
    protected RequestFactoryInterface $factory;
    protected ClientInterface $client;
    protected LoggerInterface $logger;
    protected Configuration $configuration;

    public function __construct(
        RequestFactoryInterface $factory,
        ClientInterface $client,
        LoggerInterface $logger
    ) {
        $this->factory = $factory;
        $this->client = $client;
        $this->logger = $logger;
        $this->configuration = new Configuration();
    }

    public function verify(string $response = ''): bool
    {
        if ($this->configuration->hasSkipDevValidation()) {
            return true;
        }
        if ($this->configuration->hasSkipHeaderValidation()) {
            return true;
        }

        $response = $response ?: $this->getResponseFromRequest();
        if (!$response || !$this->configuration->isEnabled()) {
            return false;
        }

        $options = [
            'headers' => [
                'Cache-Control' => 'no-cache',
                'X-API-Key' => $this->configuration->getSiteSecretKey(),
            ],
            'allow_redirects' => true,
            'form_params' => [
                'response' => $response,
            ],
        ];

        $contents = $this->request('POST', $this->configuration->getFirstVerifyUrl(), $options);
        if (!$contents) {
            return false;
        }

        try {
            $result = json_decode($contents, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
        return is_object($result) && property_exists($result, 'success') && (bool)$result->success;
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function request(string $method, string $url, array $options = []): string|false
    {
        try {
            $result = $this->client->request($method, $url, $options);
        } catch (ClientException $e) {
            $this->logger->error($e->getMessage());
            return false;
        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        if ($result->getStatusCode() !== 200) {
            $this->logger->error(sprintf('Unexpected status code %d', $result->getStatusCode()));
            return false;
        }
        return $result->getBody()->getContents();
    }

    protected function getResponseFromRequest(): string
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if (!$request instanceof ServerRequest) {
            return '';
        }
        $parsedBody = $request->getParsedBody();
        if (is_array($parsedBody) && isset($parsedBody['frc-captcha-response'])) {
            $response = $parsedBody['frc-captcha-response'];
            return is_scalar($response) || $response instanceof \Stringable ? (string)$response : '';
        }
        $queryParams = $request->getQueryParams();
        $response = $queryParams['frc-captcha-response'] ?? '';
        return is_scalar($response) || $response instanceof \Stringable ? (string)$response : '';
    }
}
