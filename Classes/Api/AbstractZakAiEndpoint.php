<?php

namespace Wegewerk\Ai3Core\Api;

use Psr\Log\LoggerInterface;

abstract class AbstractZakAiEndpoint implements ZakAiEndpointInterface
{
    /** @var string Endpoint path for the API (e.g. 'alttexts', ...) */
    protected string $endpoint = '';

    /** @var string Primary key to extract from response */
    protected string $responseKey = '';

    /** @var list<string> Required option keys */
    protected array $requiredOptions = [];

    /** @var list<string> Optional keys */
    protected array $optionalOptions = [];

    public function __construct(
        protected readonly ZakAiClient $client,
        protected readonly ?LoggerInterface $logger = null
    ) {}

    public function generate(array $options = []): string
    {
        $this->validateRequiredOptions($options);
        $payload = $this->buildPayload($options);
        $payload = $this->preparePayload($payload, $options);

        $response = $this->client->postJson($this->endpoint, $payload);

        if ($this->logger !== null) {
            $this->logger->debug('Zak_ai API Antwort: ', $response);
        }

        return $this->extractResult($response);
    }

    protected function validateRequiredOptions(array $options): void
    {
        foreach ($this->requiredOptions as $requiredKey) {
            if (!isset($options[$requiredKey]) || $options[$requiredKey] === '') {
                throw new \InvalidArgumentException(
                    sprintf('Missing required parameter "%s" for %s.', $requiredKey, static::class)
                );
            }
        }
    }

    protected function buildPayload(array $options): array
    {
        $payload = [];

        foreach ($this->requiredOptions as $key) {
            if (isset($options[$key])) {
                $payload[$key] = $options[$key];
            }
        }

        foreach ($this->optionalOptions as $optionKey ) {
            if (!empty($options[$optionKey] ?? null)) {
                $payload[$optionKey] = $options[$optionKey];
            }
        }

        return $payload;
    }

    /**
     * Hook to modify or enrich payload before sending (e.g. base64 image conversion).
     */
    protected function preparePayload(array $payload, array $options): array
    {
        return $payload;
    }

    protected function extractResult(array $response): string
    {
        $result = $response[$this->responseKey] ?? '';
        if (is_string($result)) {
            return $result;
        }
        if (is_array($result)) {
            return json_encode($result, JSON_THROW_ON_ERROR);
        }
        return (string)$result;
    }
}
