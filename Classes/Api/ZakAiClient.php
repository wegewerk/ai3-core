<?php

namespace Wegewerk\Ai3Core\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Wrapper around a PSR HTTP client.
 *
 * It knows *how* to talk to the remote service (base URL, headers, auth)
 * but not *what* to ask for.
 */
class ZakAiClient
{
    private string $baseUrl;
    private string $apiKey;
    private string $secret;
    private const DEFAULT_MODEL = 'mistral-small3.2:latest';

    public function __construct(
        string $baseUrl,
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly LoggerInterface $logger
    ) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $keyRef = 'ZAKAI_API_KEY';
        $secretRef = 'ZAKAI_SECRET';
        $this->apiKey = getenv($keyRef) === false ? '' : getenv($keyRef);
        $this->secret = getenv($secretRef) === false ? '' : getenv($secretRef);
    }

    /**
     * Sends a JSON‑encoded POST request and returns the decoded JSON.
     *
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    public function postJson(string $path, array $payload, array $extraHeaders = [], bool $requireAuth = true): array
    {
        $payload['model'] = self::DEFAULT_MODEL;
        return $this->_doRequest('POST', $path, $payload, $extraHeaders, $requireAuth);
    }
    public function putJson(string $path, array $payload = [], array $extraHeaders = [], bool $requireAuth = true): array
    {
        return $this->_doRequest('PUT', $path, $payload, $extraHeaders, $requireAuth);
    }
    public function getJson(string $path, array $extraHeaders = [], bool $requireAuth = true): array
    {
        // GET requests typically do not have a payload
        return $this->_doRequest('GET', $path, [], $extraHeaders, $requireAuth);
    }

    public function hasApikey()
    {
        return $this->apiKey !== '';
    }

    public function hasSecret()
    {
        return $this->secret !== '';
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $payload
     * @param array $extraHeaders
     * @param bool $requireAuth
     * @return array
     * @throws ClientExceptionInterface
     * @throws \JsonException
     */
    private function _doRequest(string $method, string $path, array $payload = [], array $extraHeaders = [], bool $requireAuth = true): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        if ($requireAuth && $this->apiKey == '') {
            throw new \RuntimeException('authentication is required and ZakAi Api Key is not set.');
        }
        $authorization = base64_encode($this->secret . ':' . $this->apiKey);

        try {
            $body = $this->streamFactory->createStream(json_encode($payload, JSON_THROW_ON_ERROR));
            $request = $this->requestFactory->createRequest($method, $url)
                ->withHeader('Authorization', 'Basic ' . $authorization)
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Accept', 'application/json');
            foreach ($extraHeaders as $name => $value) {
                $request = $request->withHeader($name, $value);
            }
            $request = $request->withBody($body);
            $response = $this->httpClient->sendRequest($request);
            $contents = $response->getBody()->getContents();
            $decoded = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (ClientExceptionInterface $e) {
            $this->logger->error('Zak_ai API Fehler: ' . $e->getMessage());
            throw $e;
        } catch (\JsonException $e) {
            $this->logger->error('JSON Dekodierungsfehler: ' . $e->getMessage());
            throw $e;
        }
        $this->logger->debug('Zak_ai API Antwort: ' . $contents);
        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 300) {
            throw new \RuntimeException(sprintf('API error %d: %s', $status, $response->getReasonPhrase()));
        }
        return $decoded;
    }
}
